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

error_log("postCSR post");

require_once("engine/bo/AccountBo.php");
require_once("engine/bo/VpnBo.php");

$connection = openConnection();
$accountBo = AccountBo::newInstance($connection);
$vpnBo = VpnBo::newInstance($connection);

$account = json_decode(urldecode($arguments["account"]), true);
$serial = $arguments["serial"];

$login = $account["login"];
$password = $account["password"];

$account = $accountBo->login($account["login"], $account["password"]);

if (!$account) {
	echo json_encode(array("ko" => "ko"));
	exit();
}

$vpn = array();
$vpn["vpn_account_id"] = $account["acc_id"];
$vpn["vpn_cn"] = $account["per_firstname"] . " " . $account["per_lastname"] . " - " . $serial;
$vpn["vpn_csr"] = urldecode($arguments["csr"]);

// Get by this way the hash of the configuration
$vpnBo->save($vpn);

$filepath = $config["vpn"]["csr_path"] . $vpn["vpn_hash"];

file_put_contents($filepath . ".csr", $vpn["vpn_csr"]);
error_log("api/signCsr.sh $filepath");
shell_exec("api/signCsr.sh $filepath");
$vpn["vpn_cert"] = file_get_contents($filepath . ".crt");

unlink($filepath . ".csr");
unlink($filepath . ".crt");

$vpnBo->save($vpn);

//error_log(print_r($vpn, true));

echo json_encode(array("ok" => "ok", "vpn_id" => $vpn["vpn_hash"]));

?>