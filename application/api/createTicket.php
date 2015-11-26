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

error_log("createTicket");

require_once("engine/bo/AccountBo.php");
require_once("engine/bo/TicketBo.php");

$connection = openConnection();
$accountBo = AccountBo::newInstance($connection);
$ticketBo = TicketBo::newInstance($connection);

$account = json_decode(urldecode($arguments["account"]), true);

$login = $account["login"];
$password = $account["password"];

$account = $accountBo->login($login, $password);

if (!$account || !$account["tic_account_id"]) {
	echo json_encode(array("ko" => "ko", "message" => "badCredentials"));
	exit();
}

$product = $arguments["product"];

$ticket = array("tic_product_code" => $product, "tic_account_id" => $account["acc_id"]);
$ticket["tic_creation_date"] = new DateTime();
$ticket["tic_creation_date"] = $ticket["tic_creation_date"]->format("Y-m-d H:i:s");

switch ($ticket["tic_product_code"]) {
	case "pave_vpn_year":
		$ticket["tic_amount"] = 55;
		break;
	case "vpn_year":
		$ticket["tic_amount"] = 12;
		break;
	case "vpn_6months":
		$ticket["tic_amount"] = 6;
		break;
	default:
		echo json_encode(array("ko" => "ko", "message" => "badProduct"));
		exit();
}

// Create the key
do {
	$source = $ticket["tic_creation_date"] . $ticket["tic_amount"] . $ticket["tic_account_id"];
	$source .= microtime(true);
	$source .= rand(0, 10000000);

	$key = strtoupper(substr(md5($source, false), 0, 10));

	$existingTicket = $ticketBo->getTicketByKey($key);
}
while($existingTicket);

$ticket["tic_key"] = $key;
$ticketBo->save($ticket);

echo json_encode(array("ok" => "ok", "ticket" => $key, "amount" => $amount, "product" => $product));

?>