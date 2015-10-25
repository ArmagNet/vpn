<?php
class VpnBo {
	var $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	static function newInstance($pdo) {
		return new VpnBo($pdo);
	}

	function save(&$vpn) {
		// 		error_log(print_r($vpn, true));
		// 		$vpn["per_id"] = 666;
		if (!isset($vpn["vpn_id"]) || $vpn["vpn_id"] == "0") {
			$vpn["vpn_id"] = $this->insert();
			$vpn["vpn_hash"] = AccountBo::computePassword($vpn["vpn_id"]);
		}

		$this->update($vpn);
	}

	function getSerial() {
		$query = "	INSERT INTO vpn_serial (serial) VALUES (NULL)";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $this->pdo->lastInsertId();
	}

	function insert() {
		$query = "	INSERT INTO vpns (vpn_id) VALUES (NULL)";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $this->pdo->lastInsertId();
	}

	function update($vpn) {
		$query = "	UPDATE vpns SET	";
		$separator = "";
		foreach($vpn as $field => $value) {
			if (is_numeric($field)) {
				unset($vpn[$field]);
				continue;
			}
			$query .= $separator;
			$query .= $field . " = :". $field;
			$separator = ", \n";
		}
		$query .= "	WHERE vpn_id = :vpn_id ";

		$statement = $this->pdo->prepare($query);
		$statement->execute($vpn);
	}

	function getVpns($filters = null) {
		$query = "	SELECT * FROM vpns ";
		$args = array();

		if ($filters && isset($filters["with_account"])) {
			$query .= "	JOIN accounts ON vpn_account_id = acc_id JOIN persons ON acc_person_id = per_id ";
		}

		if ($filters && (isset($filters["vse_id"]) || isset($filters["with_servers"]))) {
			$query .= "	JOIN vpn_servers ";
		}

		$query .= "	WHERE 1 = 1 ";

		if ($filters && isset($filters["vpn_account_id"]) && $filters["vpn_account_id"]) {
			$args["vpn_account_id"] = $filters["vpn_account_id"];
			$query .= "	AND vpn_account_id = :vpn_account_id";
		}

		if ($filters && isset($filters["vpn_id"]) && $filters["vpn_id"]) {
			$args["vpn_id"] = $filters["vpn_id"];
			$query .= "	AND vpn_id = :vpn_id";
		}

		if ($filters && isset($filters["vpn_cn"]) && $filters["vpn_cn"]) {
			$args["vpn_id"] = $filters["vpn_cn"];
			$query .= "	AND vpn_cn = :vpn_cn";
		}

		if ($filters && isset($filters["vpn_hash"]) && $filters["vpn_hash"]) {
			$args["vpn_hash"] = $filters["vpn_hash"];
			$query .= "	AND vpn_hash = :vpn_hash";
		}

		if ($filters && isset($filters["vse_id"]) && $filters["vse_id"]) {
			$args["vse_id"] = $filters["vse_id"];
			$query .= "	AND vse_id = :vse_id";
		}

//		echo showQuery($query, $args);

		$statement = $this->pdo->prepare($query);
		$statement->execute($args);
		$results = $statement->fetchAll();

		foreach($results as $index => $line) {
			foreach($line as $key => $value) {
				if (is_int($key)) unset($results[$index][$key]);
			}
		}

		return $results;
	}

	function cnExists($cn) {
		$vpns = $this->getVpns(array("vpn_cn" => $cn));

		return count($vpns) > 0;
	}

	function addValidity($vpn, $validity) {
		$query = "	UPDATE vpns
					SET vpn_end_date = DATE_ADD(vpn_end_date, INTERVAL :validity MONTH)
					WHERE vpn_id = :vpn_id";

		$args = array("validity" => $validity, "vpn_id" => $vpn["vpn_id"]);

		$statement = $this->pdo->prepare($query);
		$statement->execute($args);
	}
}
?>