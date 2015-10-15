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

error_log("getSerial");

require_once("engine/bo/AccountBo.php");
require_once("engine/bo/VpnBo.php");

$connection = openConnection();
$accountBo = AccountBo::newInstance($connection);
$vpnBo = VpnBo::newInstance($connection);

$account = json_decode(urldecode($arguments["account"]), true);

$login = $account["login"];
$password = $account["password"];

$account = $accountBo->login($login, $password);

if (!$account) {
	echo json_encode(array("ko" => "ko", "message" => "badCredentials"));
	exit();
}

$serial = $vpnBo->getSerial();

echo json_encode(array("ok" => "ok", "serial" => $serial));

?>