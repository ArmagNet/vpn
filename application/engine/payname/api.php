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

class PaynameApiClient {
	var $apiUrl = null;
	var $token = null;

	function __construct($apiUrl, $token) {
		$this->apiUrl = $apiUrl;
		$this->token = $token;
	}

	function createPayment($amount, $title, $type, $orderId, $backUrl) {
		$fields = array(
				'token' => $this->token,
				'amount' => $amount,
				'title' => $title,
				'type' => $type,
				'order_id' => $orderId,
				'back_url' => $backUrl
		);

		return $this->_post("creer-un-paiement", $fields);
	}

	function getPaymentInformation($paymentHash) {
		$fields = array(
				'token' => $this->token,
				'hash' => $paymentHash
		);

		return $this->_get("paiement-information", $fields);
	}

	function _exec(&$ch) {
		// Execute request
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		// json decode the result, the api has json encoded result
		$result = json_decode($result, true);

		return $result;
	}

	function _get($method, $fields) {
		$url = $this->apiUrl;
		$url .= $method;
		$url .= "?";

		//url-ify the data for the POST
		$fieldsString = http_build_query($fields);

		$url .= $fieldsString;

		//open connection
		$ch = curl_init();

		//set the url and say that we want the result returnd not printed
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute get
		return $this->_exec($ch);
	}

	function _post($method, $fields) {

		$url = $this->apiUrl;
		$url .= $method;

		//url-ify the data for the POST
		$fieldsString = http_build_query($fields);

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data, and say that we want the result returnd not printed
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute post
		return $this->_exec($ch);
	}
}
?>