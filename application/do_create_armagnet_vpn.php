<?php /*
	Copyright 2014-2015 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of VPN.

    VPN is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    VPN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with VPN.  If not, see <http://www.gnu.org/licenses/>.
*/
set_time_limit(0);
session_start();

require_once 'config/database.php';
require_once("engine/bo/AccountBo.php");
require_once("engine/bo/PersonBo.php");
require_once("engine/bo/VpnBo.php");
require_once("engine/utils/SessionUtils.php");

$connection = openConnection();
$accountBo = AccountBo::newInstance($connection);
$personBo = PersonBo::newInstance($connection);
$vpnBo = VpnBo::newInstance($connection);

$person = array();
$account = array();

$accountId = SessionUtils::getUserId($_SESSION);

if ($accountId) {
	$account = $accountBo->getAccount($accountId);
	$person = $account;
}
else if (isset($_REQUEST["hasAccount"]) && $_REQUEST["hasAccount"] == 1) {
	$account["acc_login"] = $_REQUEST["loginInput"];
	$account["acc_password"] = $_REQUEST["passwordInput"];

	$account = $accountBo->login($account["acc_login"], $account["acc_password"], $_SESSION);

	if (!$account) {
		echo json_encode(array("ko" => "ko", "message" => "badCredentials"));
		exit();
	}

	$person = $account;
}
else {
	$account["acc_login"] = $_REQUEST["loginInput"];
	$account["acc_password"] = $_REQUEST["passwordInput"];

	$confirmPassword = $_REQUEST["confirmInput"];

	if ($confirmPassword != $account["acc_password"]) {
		echo json_encode(array("ko" => "ko", "message" => "notSamePasswords", "focus" => "confirmInput"));
		exit();
	}

	$person["per_mail"] = $_REQUEST["emailInput"];
	$person["per_firstname"] = $_REQUEST["firstnameInput"];
	$person["per_lastname"] = $_REQUEST["lastnameInput"];
	$person["per_address_1"] = $_REQUEST["addressInput"];
	$person["per_address_2"] = $_REQUEST["address2Input"];
	$person["per_zip_code"] = $_REQUEST["zipcodeInput"];
	$person["per_city"] = $_REQUEST["cityInput"];

	if (!$person["per_lastname"]) {
		echo json_encode(array("ko" => "ko", "message" => "lastnameMandatory", "focus" => "lastnameInput"));
		exit();
	}

	if (!$person["per_firstname"]) {
		echo json_encode(array("ko" => "ko", "message" => "firstnameMandatory", "focus" => "firstnameInput"));
		exit();
	}

	if (!$person["per_mail"]) {
		echo json_encode(array("ko" => "ko", "message" => "mailMandatory", "focus" => "emailInput"));
		exit();
	}

	if ($accountBo->exists($account["acc_login"], $person["per_mail"])) {
		echo json_encode(array("ko" => "ko", "message" => "alreadyExists"));
		exit();
	}

	$personBo->save($person);
	$account["acc_person_id"] = $person["per_id"];

	$password = $account["acc_password"];
	$account["acc_password"] = AccountBo::computePassword($account["acc_password"]);

	$accountBo->save($account);

	$accountBo->login($account["acc_login"], $password, $_SESSION);
}

// Private key and CSR part
if (isset($_REQUEST["hasPrivateKey"]) && $_REQUEST["hasPrivateKey"] != "0") {
	$privateKeyContent = null;

	$csrContent = $_REQUEST["csrInput"];

	$subject = openssl_csr_get_subject($csrContent);

	$cn = $subject["CN"];
}
else
{
	// We create the private key and the CSR
	$serial = $vpnBo->getSerial();

	$openSslConfig = array(
			"digest_alg" => "sha512",
			"private_key_bits" => 4096,
			"private_key_type" => OPENSSL_KEYTYPE_RSA,
	);

	// Create the private and public key
	$res = openssl_pkey_new($openSslConfig);

	$cn = $person["per_firstname"] . " " . $person["per_lastname"] . " - " . $serial;

	$dn = array(
			"countryName" => "FR",
			"stateOrProvinceName" => "France",
			"organizationName" => "Armagnet",
			"commonName" => $cn,
			"emailAddress" => $person["per_mail"]
	);

	// Create the Certificate Signature Request
	$csr = openssl_csr_new($dn, $res);
	openssl_csr_export($csr, $csrContent);

	$keyPath = "key_" . time();

	$defaultPassword = "withapasswordforthisphase";

	// Extract the private key from $res to $privKey
	// No password for openvpn in deamon mode
	openssl_pkey_export($res, $privKey, $defaultPassword);

	// Find a better way
	file_put_contents($keyPath . ".pkey", $privKey);
	shell_exec("openssl pkey -in $keyPath" . ".pkey -passin pass:$defaultPassword -out $keyPath" . ".key");
	$privateKeyContent = file_get_contents($keyPath . ".key");
	unlink("$keyPath" . ".pkey");
	unlink("$keyPath" . ".key");
}

if ($vpnBo->cnExists($cn)) {
	echo json_encode(array("ko" => "ko", "message" => "cnAlreadyExists", "focus" => "csrInput"));
	exit();
}

$vpn = array();
$vpn["vpn_account_id"] = $account["acc_id"];
$vpn["vpn_cn"] = $cn;
$vpn["vpn_csr"] = $csrContent;
if ($privateKeyContent) {
	$vpn["vpn_key"] = $privateKeyContent;
}

// Get by this way the hash of the configuration
$vpnBo->save($vpn);

$filepath = $config["vpn"]["csr_path"] . $vpn["vpn_hash"];

file_put_contents($filepath . ".csr", $vpn["vpn_csr"]);
shell_exec("api/signCsr.sh $filepath");
$vpn["vpn_cert"] = file_get_contents($filepath . ".crt");

unlink($filepath . ".csr");
unlink($filepath . ".crt");

$vpnBo->save($vpn);

echo json_encode(array("ok" => "ok", "vpnHash" => $vpn["vpn_hash"]));

?>