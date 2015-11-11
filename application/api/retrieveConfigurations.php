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

// security
if (!isset($arguments)) {
	echo json_encode(array("error" => "not_a_service"));
	exit();
}

//error_log(print_r($arguments, true));

require_once("engine/bo/AccountBo.php");
require_once("engine/bo/VpnBo.php");

$connection = openConnection();
$accountBo = AccountBo::newInstance($connection);
$vpnBo = VpnBo::newInstance($connection);

$account = json_decode(urldecode($arguments["account"]), true);

$login = $account["login"];
$password = $account["password"];

// authenticate
$account = $accountBo->login($login, $password);

if (!$account) {
	echo json_encode(array("ko" => "ko", "message" => "badCredentials"));
	exit();
}

// error_log(print_r($account, true));

// retrieve VPN configurations (json & ovpn), crt, key (if known), ca cert, dh

$vpns = $vpnBo->getVpns(array("with_account" => 1, "with_servers" => 1, "vpn_account_id" => $account["acc_id"]));
$configurations = array();

foreach($vpns as $vpn) {
	$configuration = array();
	$configuration["key"] = null;

	$configuration["cacrt"] = $vpn["vse_cacert"];
	$configuration["dh"] = $vpn["vse_dh"];

	if ($vpn["vpn_key"]) {
		$configuration["key"] = $vpn["vpn_key"];
	}
	$configuration["crt"] = $vpn["vpn_cert"];

	$configuration["json"] = array();

	$configuration["json"]["dev"] = $vpn["vse_dev"];
	$configuration["json"]["proto"] = $vpn["vse_proto"];
//	$configuration["json"]["ca"] = "ca.crt ";
//	$configuration["json"]["cert"] = "tornade.crt";
//	$configuration["json"]["key"] = "tornade.key";
	$configuration["json"]["remote_cert_tls"] = $vpn["vse_remote_cert_tls"];
	$configuration["json"]["remote"] = $vpn["vse_remote_ip"] . " " . $vpn["vse_remote_port"];
	$configuration["json"]["cipher"] = $vpn["vse_cipher"];
	$configuration["json"]["comp_lzo"] = $vpn["vse_comp_lzo"];

	$configuration["label"] = $config["vpn"]["service_name"] . " - " . $vpn["vpn_cn"] . " - " . $vpn["vse_label"];
	$configuration["id"] = $vpn["vpn_hash"] . $vpn["vse_id"];

	$configurations[] = $configuration;
}

error_log("retrieveConfigurations post");

//error_log(json_encode(array("ok" => "ok", "configurations" => $configurations)));

echo json_encode(array("ok" => "ok", "configurations" => $configurations));

?>