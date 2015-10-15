<?php
class PaymentBo {
	var $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	static function newInstance($pdo) {
		return new PaymentBo($pdo);
	}

	function save(&$payment) {
		if (!isset($payment["pay_id"]) || $payment["pay_id"] == "0") {
			$payment["pay_id"] = $this->insert();
		}

		$this->update($payment);
	}

	function insert() {
		$query = "	INSERT INTO payments (pay_id) VALUES (NULL)";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $this->pdo->lastInsertId();
	}

	function update($payment) {
		$query = "	UPDATE payments SET	";

		if (is_array($payment["pay_request"])) {
			$payment["pay_request"] = json_encode($payment["pay_request"]);
		}

		if (is_array($payment["pay_response"])) {
			$payment["pay_response"] = json_encode($payment["pay_response"]);
		}

		$separator = "";
		foreach($payment as $field => $value) {
			if (is_numeric($field)) {
				unset($payment[$field]);
				continue;
			}
			$query .= $separator;
			$query .= $field . " = :". $field;
			$separator = ", \n";
		}
		$query .= "	WHERE pay_id = :pay_id ";

		$statement = $this->pdo->prepare($query);
		$statement->execute($payment);
	}

	function getPaymentByOrderId($orderId) {
		$payments = $this->getPayments(array("pay_order_id" => $orderId));

		if (count($payments) == 0) return null;

		return $payments[0];
	}

	function getPayments($filters) {
		$query = "	SELECT * FROM payments ";
		$args = array();

		$query .= "	WHERE 1 = 1 ";

		if ($filters && isset($filters["pay_order_id"]) && $filters["pay_order_id"]) {
			$args["pay_order_id"] = $filters["pay_order_id"];
			$query .= "	AND pay_order_id = :pay_order_id";
		}

		//		echo showQuery($query, $args);

		$statement = $this->pdo->prepare($query);
		$statement->execute($args);
		$results = $statement->fetchAll();

		foreach($results as $index => $line) {
			foreach($line as $key => $value) {
				if (is_int($key)) {
					unset($results[$index][$key]);
				}
				else if (($key == "pay_request" || $key == "pay_response") && $value) {
					$results[$index][$key] = json_decode($value, true);
				}
			}
		}

		return $results;
	}
}
?>