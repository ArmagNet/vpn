<?php
class AccountBo {
	var $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	static function newInstance($pdo) {
		return new AccountBo($pdo);
	}

	function save(&$account) {
		// 		error_log(print_r($account, true));
		// 		$account["per_id"] = 666;
		if (!isset($account["acc_id"]) || $account["acc_id"] == "0") {
			$account["acc_id"] = $this->insert();
		}

		$this->update($account);
	}

	function insert() {
		$query = "	INSERT INTO accounts (acc_id) VALUES (NULL)";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $this->pdo->lastInsertId();
	}

	function update($account) {
		$query = "	UPDATE accounts SET	";
		$separator = "";
		foreach($account as $field => $value) {
			if (is_numeric($field)) {
				unset($account[$field]);
				continue;
			}
			$query .= $separator;
			$query .= $field . " = :". $field;
			$separator = ", \n";
		}
		$query .= "	WHERE acc_id = :acc_id ";

		$statement = $this->pdo->prepare($query);
		$statement->execute($account);
	}

	static function computePassword($password) {
		global $config;

		$computed = hash("sha256", $config["salt"] . $password . $config["salt"], false);
		//		error_log("Computed password : " . $computed);

		return $computed;
	}

	function exists($login, $mail) {
		$args = array("login" => $login, "mail" => $mail);
		$query = "	SELECT *
					FROM accounts
					JOIN persons ON acc_person_id = per_id
					WHERE (acc_login = :login OR per_mail = :mail) AND 1 = 1 ";

		$statement = $this->pdo->prepare($query);

		$statement->execute($args);
		$results = $statement->fetchAll();

		return count($results) > 0;
	}

	function getAccount($accountId) {
		$args = array("acc_id" => $accountId);
		$query = "	SELECT *
					FROM accounts
					JOIN persons ON acc_person_id = per_id
					LEFT_JOIN ticketers ON acc_id = tic_account_id
					WHERE acc_id = :acc_id AND 1 = 1 ";

		$statement = $this->pdo->prepare($query);

		$statement->execute($args);
		$results = $statement->fetchAll();

		foreach($results as $index => $line) {
			foreach($line as $key => $value) {
				if (is_int($key)) unset($results[$index][$key]);
			}
		}

		if (count($results)) {
			$account = $results[0];
			return $account;
		}

		return false;
	}

	function login($login, $password, &$session = null) {
		$args = array("login" => $login);
		$query = "	SELECT *
					FROM accounts
					JOIN persons ON acc_person_id = per_id
					LEFT_JOIN ticketers ON acc_id = tic_account_id
					WHERE (acc_login = :login OR per_mail = :login) AND 1 = 1 ";

		$statement = $this->pdo->prepare($query);

		$statement->execute($args);
		$results = $statement->fetchAll();

		foreach($results as $index => $line) {
			foreach($line as $key => $value) {
				if (is_int($key)) unset($results[$index][$key]);
			}
		}

		if (count($results)) {
			$account = $results[0];

//			error_log($account["acc_password"] . " vs " . AccountBo::computePassword($password));

			if ($account["acc_password"] == AccountBo::computePassword($password)) {
				if (is_array($session)) {
					SessionUtils::login($session, $account);
				}
				return $account;
			}
		}

		return false;
	}
}
?>