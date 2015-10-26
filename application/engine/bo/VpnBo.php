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
			$args["vpn_cn"] = $filters["vpn_cn"];
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

	function addVpnLog(&$log) {
		if (isset($log["vlo_cn"])) {
			$vpns = $this->getVpns(array("vpn_cn" => $log["vlo_cn"]));

			if (!count($vpns)) return false;

			// Search the vpn_id
			$log["vlo_vpn_id"] = $vpns[0]["vpn_id"];
			unset($log["vlo_cn"]);
		}

		$query = "	SELECT *
					FROM vpn_logs
					WHERE
						vlo_vpn_id = :vlo_vpn_id
					AND vlo_since_date = :vlo_since_date
					ORDER BY vlo_log_date DESC
					LIMIT 0, 1";

		$statement = $this->pdo->prepare($query);
		$statement->execute(array("vlo_vpn_id" => $log["vlo_vpn_id"], "vlo_since_date" => $log["vlo_since_date"]));
		$results = $statement->fetchAll();

		if (count($results)) {
			$previousLog = $results[0];

			$fromDate = new DateTime($previousLog["vlo_log_date"]);
			$toDate = new DateTime($log["vlo_log_date"]);

			$interval = $toDate->getTimestamp() - $fromDate->getTimestamp();

			$log["vlo_upload_rate"] = ($log["vlo_upload"] - $previousLog["vlo_upload"]) / $interval;
			$log["vlo_download_rate"] = ($log["vlo_download"] - $previousLog["vlo_download"]) / $interval;
		}
		else {
			$fromDate = new DateTime($log["vlo_since_date"]);
			$toDate = new DateTime($log["vlo_log_date"]);

			$interval = $toDate->getTimestamp() - $fromDate->getTimestamp();

			$log["vlo_upload_rate"] = $log["vlo_upload"] / $interval;
			$log["vlo_download_rate"] = $log["vlo_download"] / $interval;
		}

		$query = "	INSERT INTO vpn_logs
						(
							vlo_server_id, vlo_vpn_id,
							vlo_client_ip, vlo_client_port,
							vlo_upload, vlo_upload_rate,
							vlo_download, vlo_download_rate,
							vlo_since_date, vlo_log_date
						)
					VALUES
						(
							:vlo_server_id, :vlo_vpn_id,
							:vlo_client_ip, :vlo_client_port,
							:vlo_upload, :vlo_upload_rate,
							:vlo_download, :vlo_download_rate,
							:vlo_since_date, :vlo_log_date
						)";

//		echo showQuery($query, $log);

		$statement = $this->pdo->prepare($query);
		$statement->execute($log);

		$log["vlo_id"] = $this->pdo->lastInsertId();
	}

	function getLogs($vpns) {
		$ids = "";
		$separator = "";

		foreach($vpns as $vpn) {
			$ids .= $separator;
			$separator = ", ";
			$ids .= $vpn["vpn_id"];
		}

		/*
		$query = "	SELECT
						vpn_id, vpn_logs.*,
						(SELECT MAX(vlo_log_date) FROM vpn_logs WHERE vlo_vpn_id = vpn_id) AS vlo_last_log_date
					FROM `vpns`
					LEFT JOIN vpn_logs ON vlo_vpn_id = vpn_id
					WHERE vpn_id IN ($ids)
					HAVING vlo_last_log_date = vlo_log_date
					OR vlo_log_date IS NULL";
*/

//		(SELECT MAX(vlo_log_date) FROM vpn_logs WHERE vlo_vpn_id = vpn_id) AS vlo_last_log_date,

		$query = "	SELECT
						vpn_id, vpn_logs.*,
						IF((SELECT MAX(vlo_log_date) FROM vpn_logs WHERE vlo_vpn_id = vpn_id) = vlo_log_date, 1, 0) as vlo_last_log
					FROM `vpns`
					LEFT JOIN vpn_logs ON vlo_vpn_id = vpn_id
					WHERE vpn_id IN ($ids) AND vlo_log_date >= DATE_SUB(now(), INTERVAL 3 HOUR)
					ORDER BY vpn_id, vlo_log_date DESC";

		$statement = $this->pdo->prepare($query);
		$statement->execute();
		$results = $statement->fetchAll();

		foreach($results as $index => $line) {
			foreach($line as $key => $value) {
				if (is_int($key)) unset($results[$index][$key]);
			}
		}

		return $results;
	}
}
?>