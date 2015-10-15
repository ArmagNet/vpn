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
session_start();
require_once("config/database.php");
require_once("engine/utils/SessionUtils.php");
require_once("engine/bo/AccountBo.php");
require_once("engine/bo/VpnBo.php");

function getOvpn($vpn) {

	$ovpn = "";

	$ovpn .= "dev " . $vpn["vse_dev"] . "\n";
	$ovpn .= "proto " . $vpn["vse_proto"] . "\n";
	$ovpn .= "log /my/log/path/openvpn.log\n";
	$ovpn .= "verb 3\n";
	$ovpn .= "ca " . $vpn["vpn_hash"] . $vpn["vse_id"] . ".cert\n";
	$ovpn .= "cert " . $vpn["vpn_hash"] . ".crt\n";
	$ovpn .= "key " . $vpn["vpn_hash"] . ".key" . "\n";
	$ovpn .= "client 1\n";
	$ovpn .= "remote-cert-tls " . $vpn["vse_remote_cert_tls"] . "\n";
	$ovpn .= "remote " . $vpn["vse_remote_ip"] . " " . $vpn["vse_remote_port"] . "\n";
	$ovpn .= "cipher " . $vpn["vse_cipher"] . "\n";
	$ovpn .= "comp-lzo " . $vpn["vse_comp_lzo"] . "\n";

	return $ovpn;
}

if (!isset($_SERVER["HTTP_REFERER"])) exit();

$accountId = SessionUtils::getUserId($_SESSION);

if (!$accountId) {
//	echo "no session";
	header("Location: index.php");
	exit();
}

$connection = openConnection();

$serverId = intval($_REQUEST["sid"]);
$vpnId = intval($_REQUEST["vid"]);

$vpnBo = VpnBo::newInstance($connection);
$vpns = $vpnBo->getVpns(array("with_account" => 1, "vpn_account_id" => $accountId, "vpn_id" => $vpnId, "vse_id" => $serverId));

if (count($vpns) == 0) {
//	echo "no vpn";
	header("Location: index.php");
	exit();
}

$vpn = $vpns[0];

$content = null;

switch($_REQUEST["type"]) {
	case "key":
		$content = $vpn["vpn_key"];
		$filename = $vpn["vpn_hash"] . ".key";
		break;
	case "cert":
		$content = $vpn["vpn_cert"];
		$filename = $vpn["vpn_hash"] . ".cert";
		break;
	case "cacert":
		$content = $vpn["vse_cacert"];
		$filename = $vpn["vpn_hash"] . $vpn["vse_id"] . ".cert";
		break;
	case "dh":
		$content = $vpn["vse_dh"];
		$filename = $vpn["vpn_hash"] . $vpn["vse_id"] . ".pem";
		break;
	case "ovpn":
		// TODO
		$content = getOvpn($vpn);
		$filename = $vpn["vpn_hash"] . $vpn["vse_id"] . ".ovpn";
		break;
}

if (!$content) {
//	echo "null";
	header("Location: index.php");
	exit();
}

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"$filename\"");

echo $content;

?>