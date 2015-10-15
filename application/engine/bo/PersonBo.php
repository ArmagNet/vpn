<?php
class PersonBo {
	var $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	static function newInstance($pdo) {
		return new PersonBo($pdo);
	}

	function save(&$person) {
// 		error_log(print_r($person, true));
// 		$person["per_id"] = 666;
		if (!isset($person["per_id"]) || $person["per_id"] == "0") {
			$person["per_id"] = $this->insert();
		}

		$this->update($person);
	}

	function insert() {
		$query = "	INSERT INTO persons (per_id) VALUES (NULL)";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $this->pdo->lastInsertId();
	}

	function update($person) {
		$query = "	UPDATE persons SET	";
		$separator = "";
		foreach($person as $field => $value) {
			if (is_numeric($field)) {
				unset($person[$field]);
				continue;
			}
			$query .= $separator;
			$query .= $field . " = :". $field;
			$separator = ", \n";
		}
		$query .= "	WHERE per_id = :per_id ";

		$statement = $this->pdo->prepare($query);
		$statement->execute($person);
	}
}
?>