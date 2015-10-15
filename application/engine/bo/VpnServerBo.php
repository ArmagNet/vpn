<?php
class VpnServerBo {
	var $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	static function newInstance($pdo) {
		return new AccountBo($pdo);
	}
}
?>