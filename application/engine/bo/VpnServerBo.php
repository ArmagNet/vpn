<?php
class VpnServerBo {
	var $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	static function newInstance($pdo) {
		return new VpnServerBo($pdo);
	}

	function get($filters = null) {
		$query = "	SELECT * FROM vpn_servers ";
		$args = array();

		$query .= "	WHERE 1 = 1 ";

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

	function getLogs() {
		$query = "	SELECT vlo_server_id, vlo_log_date,
						IF((SELECT MAX(vl.vlo_log_date) FROM vpn_logs vl WHERE vl.vlo_server_id = log.vlo_server_id) = vlo_log_date, 1, 0) as vlo_last_log,
						COUNT(vlo_vpn_id) as vlo_number_of_users,
						SUM(vlo_upload) as vlo_upload, SUM(vlo_upload_rate) as vlo_upload_rate,
						SUM(vlo_download) as vlo_download, SUM(vlo_download_rate) as vlo_download_rate
					FROM `vpn_logs` log
					WHERE 1 = 1  AND vlo_log_date >= DATE_SUB(now(), INTERVAL 3 HOUR)
					GROUP BY vlo_server_id ASC, vlo_log_date DESC ";
		$args = array();

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
}
?>