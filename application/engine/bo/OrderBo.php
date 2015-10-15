<?php
class OrderBo {
	var $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	static function newInstance($pdo) {
		return new OrderBo($pdo);
	}

	function save(&$order) {
		if (!isset($order["ord_id"]) || $order["ord_id"] == "0") {
			$order["ord_id"] = $this->insert();
		}

		$this->update($order);

		foreach($order["ord_lines"] as $orderLine) {
			$orderLine["oli_order_id"] = $order["ord_id"];
			$this->saveLine($orderLine);
		}
	}

	function insert() {
		$query = "	INSERT INTO orders (ord_id) VALUES (NULL)";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $this->pdo->lastInsertId();
	}

	function update($order) {
		$query = "	UPDATE orders SET	";

		unset($order["ord_lines"]);

		$separator = "";
		foreach($order as $field => $value) {
			if (is_numeric($field)) {
				unset($order[$field]);
				continue;
			}
			$query .= $separator;
			$query .= $field . " = :". $field;
			$separator = ", \n";
		}
		$query .= "	WHERE ord_id = :ord_id ";

		$statement = $this->pdo->prepare($query);
		$statement->execute($order);
	}

	function saveLine(&$orderLine) {
		if (!isset($orderLine["oli_id"]) || $orderLine["oli_id"] == "0") {
			$orderLine["oli_id"] = $this->insertLine();
		}

		$this->updateLine($orderLine);
	}

	function insertLine() {
		$query = "	INSERT INTO order_lines (oli_id) VALUES (NULL)";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $this->pdo->lastInsertId();
	}

	function updateLine($orderLine) {
		$query = "	UPDATE order_lines SET	";

		$separator = "";
		foreach($orderLine as $field => $value) {
			if (is_numeric($field)) {
				unset($orderLine[$field]);
				continue;
			}
			else if ($field == "oli_additional_information" && is_array($value)) {
				$orderLine[$field] = json_encode($value);
			}

			$query .= $separator;
			$query .= $field . " = :". $field;
			$separator = ", \n";
		}
		$query .= "	WHERE oli_id = :oli_id ";

		$statement = $this->pdo->prepare($query);
		$statement->execute($orderLine);
	}

	function get($orderId) {
		$orders = $this->getOrders(array("ord_id" => $orderId));

		if (count($orders) == 0) return null;

		return $orders[$orderId];
	}

	function getOrders($filters) {
		$query = "	SELECT * FROM orders
					JOIN order_lines ON oli_order_id = ord_id ";
		$args = array();

		$query .= "	WHERE 1 = 1 ";

		if ($filters && isset($filters["ord_id"]) && $filters["ord_id"]) {
			$args["ord_id"] = $filters["ord_id"];
			$query .= "	AND ord_id = :ord_id";
		}

		$query .= "	ORDER BY ord_id, oli_id ";

		//		echo showQuery($query, $args);

		$statement = $this->pdo->prepare($query);
		$statement->execute($args);
		$results = $statement->fetchAll();

		foreach($results as $index => $line) {
			foreach($line as $key => $value) {
				if (is_int($key)) {
					unset($results[$index][$key]);
				}
				else if ($key == "oli_additional_information" && $value) {
					$results[$index][$key] = json_decode($value, true);
				}
			}
		}

		$orders = array();

		foreach($results as $index => $line) {
			if (!isset($orders[$line["ord_id"]])) {
				$orders[$line["ord_id"]] = $line;
				$orders[$line["ord_id"]]["ord_lines"] = array();
			}

			$orders[$line["ord_id"]]["ord_lines"][] = $line;
		}

		return $orders;
	}

	function execute($orderLine) {
		$code = $orderLine["oli_product_code"];

		switch ($code) {
			case "vpn_membership":
			case "vpn_year":
			case "vpn_6months":
				$vpnId = $orderLine["oli_additional_information"]["vpnId"];

				$vpnBo = VpnBo::newInstance($this->pdo);
				$vpns = $vpnBo->getVpns(array("vpn_hash" => $vpnId));

//				echo "VpnId : $vpnId \n";

//				print_r($vpns);

				if (count($vpns)) {
					$vpn = $vpns[0];
//					echo "Activate : " . $vpn["vpn_cn"] . "\n";
//					echo
//					shell_exec("api/activateCn.sh \"BIBI BOBO\"");
//					echo "\n";
					shell_exec("api/activateCn.sh \"".$vpn["vpn_cn"]."\"");
				}

				break;
		}
	}
}
?>