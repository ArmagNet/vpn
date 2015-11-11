<?php
class TicketBo {
	var $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	static function newInstance($pdo) {
		return new TicketBo($pdo);
	}

	function save(&$ticket) {
		if (!isset($ticket["tic_id"]) || $ticket["tic_id"] == "0") {
			$ticket["tic_id"] = $this->insert();
		}

		$this->update($ticket);
	}

	function insert() {
		$query = "	INSERT INTO tickets (tic_id) VALUES (NULL)";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $this->pdo->lastInsertId();
	}

	function update($ticket) {
		$query = "	UPDATE tickets SET	";

// 		if (is_array($ticket["tic_request"])) {
// 			$ticket["tic_request"] = json_encode($ticket["tic_request"]);
// 		}

// 		if (is_array($ticket["tic_response"])) {
// 			$ticket["tic_response"] = json_encode($ticket["tic_response"]);
// 		}

		$separator = "";
		foreach($ticket as $field => $value) {
			if (is_numeric($field)) {
				unset($ticket[$field]);
				continue;
			}
			$query .= $separator;
			$query .= $field . " = :". $field;
			$separator = ", \n";
		}
		$query .= "	WHERE tic_id = :tic_id ";

		$statement = $this->pdo->prepare($query);
		$statement->execute($ticket);
	}

	function getTicketByKey($key) {
		$tickets = $this->getTickets(array("tic_key" => $key));

		if (count($tickets) == 0) return null;

		return $tickets[0];
	}

	function getTickets($filters) {
		$query = "	SELECT * FROM tickets ";
		$args = array();

		$query .= "	WHERE 1 = 1 ";

		if ($filters && isset($filters["tic_key"]) && $filters["tic_key"]) {
			$args["tic_key"] = $filters["tic_key"];
			$query .= "	AND tic_key = :tic_key";
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
// 				else if (($key == "tic_request" || $key == "tic_response") && $value) {
// 					$results[$index][$key] = json_decode($value, true);
// 				}
			}
		}

		return $results;
	}
}
?>