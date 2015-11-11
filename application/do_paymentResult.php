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
define("MEMBERSHIP_PRICE", 24);
define("MINIMUM_MEMBERSHIP_VPN_PRICE", 12);
define("YEAR_VPN_PRICE", 60);
define("SIXMONTHS_PRICE", 33);

require_once("config/database.php");
require_once("engine/payname/api.php");
require_once("engine/bo/OrderBo.php");
require_once("engine/bo/PaymentBo.php");
require_once("engine/bo/TicketBo.php");
require_once("engine/bo/VpnBo.php");

$paynameApi = new PaynameApiClient($config["vpn"]["payname"]["api_url"], $config["vpn"]["payname"]["token"]);
$connection = openConnection();

$orderBo = OrderBo::newInstance($connection);
$paymentBo = PaymentBo::newInstance($connection);
$ticketBo = TicketBo::newInstance($connection);

$orderId = $_REQUEST["oid"];

$payment = $paymentBo->getPaymentByOrderId($orderId);

// print_r($payment);

if ($payment["pay_type"] == "ticket") {
	$ticket = $ticketBo->getTicket($payment["pay_ticket_id"]);

	$response = array();

	$response["createdAt"] = new DateTime();
	$response["createdAt"] = $response["createdAt"]->format("Y-m-d H:i:s");

	if (!$ticket || $ticket["tic_use_date"]) {
		$response["status"] = "failed";
	}
	else {
		$response["status"] = "finished";
		$ticket["tic_use_date"] = $response["createdAt"];

		$ticketBo->save($ticket);
	}

	$payment["pay_response"] = $response;
	$paymentBo->save($payment);
}
else {
	$response = $paynameApi->getPaymentInformation($payment["pay_request"]["hash"]);

	// print_r($response);

	if ($response["status"] != "created") {
		$payment["pay_response"] = $response;
		$paymentBo->save($payment);
	}
}

$referer = "";
if (isset($_REQUEST["referer"]) && $_REQUEST["referer"]) {
	$referer = "?referer=" . urlencode($_REQUEST["referer"]);
}

if ($response["status"] == "finished") {
	$order = $orderBo->get($payment["pay_order_id"]);

	foreach($order["ord_lines"] as $orderLine) {
//		print_r($orderLine);
		$orderBo->execute($orderLine);
	}

	header("Location: paymentDone.php" . $referer);
}
else if ($response["status"] == "failed") {
	header("Location: paymentFailed.php" . $referer);
}

//header("Location: $paymentLink");
exit();
?>