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
require_once("engine/bo/VpnBo.php");

$accountId = SessionUtils::getUserId($_SESSION);

if (!$accountId) {
	echo json_encode(array("ko" => "ko", "message" => "no_session"));
	exit();
}

$connection = openConnection();
$vpnBo = VpnBo::newInstance($connection);
$vpns = $vpnBo->getVpns(array("with_account" => 1, "with_servers" => 1, "vpn_account_id" => $accountId));

if (count($vpns) == 0) {
//	echo "no vpn";
	header("Location: index.php");
	exit();
}

$logs = $vpnBo->getLogs($vpns);

echo json_encode(array("ok" => "ok", "logs" => $logs));
?>