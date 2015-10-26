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

// Can only be call from CLI
if (php_sapi_name() != "cli") exit();

$path = "../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

if (isset($argv) && count($argv)) {
	foreach($argv as $argIndex => $argValue) {
		if ($argValue == "-log") {
			$logFile = $argv[$argIndex + 1];
		}
		else if ($argValue == "-serverId") {
			$serverId = $argv[$argIndex + 1];
		}
	}
}

$error = 0;

if (!isset($logFile)) {
	$error += 1;
	echo "\tMissing log file\n";
}

if (!isset($serverId)) {
	$error += 2;
	echo "\tMissing server id\n";
}

if ($error) {
	exit($error);
}

require_once("config/database.php");
require_once("engine/bo/VpnBo.php");

$connection = openConnection();
$vpnBo = VpnBo::newInstance($connection);

$fh = fopen($logFile, "r");

if ($fh) {
	$readingStat = 0;

	while (($data = fgetcsv($fh, 0, ",")) !== FALSE) {
		if ($data[0] == "OpenVPN CLIENT LIST") {
			$readingStat = 1;
		}
		else if ($data[0] == "ROUTING TABLE") {
			$readingStat = 2;
		}
		else if ($data[0] == "GLOBAL STATS") {
			$readingStat = 3;
		}
		else if ($data[0] == "Updated") {
			// Data log date
			// Get log id
			$updateDate = new DateTime($data[1]);

//			echo "Date : " . $updateDate->format("Y-m-d H:i:s") . "\n";
		}
		else if ($data[0] == "Common Name" || $data[0] == "Virtual Address" || $data[0] == "Virtual Address") {
			// Skip header line
		}
		else if ($readingStat == 1) {
			// Common Name,Real Address,Bytes Received,Bytes Sent,Connected Since
//			echo "Client list";
//			print_r($data);

			$log = array();
			$log["vlo_server_id"] = $serverId;
			$log["vlo_cn"] = $data[0];

			$ip = preg_split("/:/", $data[1]);
			$log["vlo_client_port"] = $ip[1];
			$log["vlo_client_ip"] = $ip[0];
			$log["vlo_upload"] = $data[2];
			$log["vlo_download"] = $data[3];
			$log["vlo_since_date"] = new DateTime($data[4]);
			$log["vlo_since_date"] = $log["vlo_since_date"]->format("Y-m-d H:i:s");
			$log["vlo_log_date"] = $updateDate->format("Y-m-d H:i:s");

//			print_r($log);

//			echo "$cn => $ip : $port [ $download | $upload ] Since " . $sinceDate->format("Y-m-d H:i:s") . "\n";

			$vpnBo->addVpnLog($log);
		}
		else if ($readingStat == 2) {
			// Virtual Address,Common Name,Real Address,Last Ref
//			echo "Routing ip";
//			print_r($data);
		}

	}

	fclose($fh);
}

?>