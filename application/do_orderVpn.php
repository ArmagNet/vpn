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

$paynameApi = new PaynameApiClient($config["vpn"]["payname"]["api_url"], $config["vpn"]["payname"]["token"]);
$connection = openConnection();

$orderBo = OrderBo::newInstance($connection);
$paymentBo = PaymentBo::newInstance($connection);

$order = array();
$order["ord_amount"] = 0;
$order["ord_lines"] = array();

$vpnId = $_REQUEST["vpnId"];

if (isset($_REQUEST["askMembership"]) && intval($_REQUEST["askMembership"])) {
	$orderLine = array();
	$orderLine["oli_product_code"] = "membership";
	$orderLine["oli_label"] = "Adhésion à Armagnet";
	$orderLine["oli_quantity"] = "1";
	$orderLine["oli_unity_price"] = MEMBERSHIP_PRICE;
	$orderLine["oli_amount"] = $orderLine["oli_quantity"] * $orderLine["oli_unity_price"];
	$order["ord_lines"][] = $orderLine;
	$order["ord_amount"] += $orderLine["oli_amount"];

	$price = $_REQUEST["vpnMemberPrice"];
	if ($price >= MINIMUM_MEMBERSHIP_VPN_PRICE) {
		$orderLine["oli_product_code"] = "vpn_membership";
		$orderLine["oli_label"] = "VPN pour membre";
		$orderLine["oli_quantity"] = "1";
		$orderLine["oli_additional_information"] = array("vpnId" => $vpnId);
		$orderLine["oli_unity_price"] = $price;
		$orderLine["oli_amount"] = $orderLine["oli_quantity"] * $orderLine["oli_unity_price"];
		$order["ord_lines"][] = $orderLine;
		$order["ord_amount"] += $orderLine["oli_amount"];
	}
	else {
		exit();
	}
}
else {
	$orderLine["oli_product_code"] = $_REQUEST["vpnCode"];
	$orderLine["oli_additional_information"] = array("vpnId" => $vpnId);

	switch($orderLine["oli_product_code"]) {
		case "vpn_year":
			$orderLine["oli_label"] = "VPN pour un an";
			$orderLine["oli_unity_price"] = YEAR_VPN_PRICE;
			break;
		case "vpn_6months":
			$orderLine["oli_label"] = "VPN pour 6 mois";
			$orderLine["oli_unity_price"] = SIXMONTHS_PRICE;
			break;
		default:
			exit();
	}

	$orderLine["oli_quantity"] = "1";
	$orderLine["oli_amount"] = $orderLine["oli_quantity"] * $orderLine["oli_unity_price"];
	$order["ord_lines"][] = $orderLine;
	$order["ord_amount"] += $orderLine["oli_amount"];
}

$orderBo->save($order);

$backUrl = "https://www.armagnet.fr/vpn/do_paymentResult.php?oid=" . $order["ord_id"];

if ($_SERVER["HTTP_REFERER"]) {
	$backUrl .= "&referer=" . urlencode($_SERVER["HTTP_REFERER"]);
}

$payment = array("pay_request" => array());
$payment["pay_order_id"] = $order["ord_id"];
$payment["pay_type"] = "payname";
$payment["pay_amount"] = number_format($order["ord_amount"], 2, '.', '');
$payment["pay_request"] = $paynameApi->createPayment($payment["pay_amount"], "ARMAGNET_" . date("Y") . "_" . $payment["pay_order_id"], "direct", $payment["pay_order_id"], $backUrl);
$payment["pay_response"] = "";

$paymentBo->save($payment);

$paymentLink = $payment["pay_request"]["link"];

header("Location: $paymentLink");
exit();
?>