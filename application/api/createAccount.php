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

// security
if (!isset($arguments)) {
	echo json_encode(array("error" => "not_a_service"));
	exit();
}

error_log("createAccount");

require_once("engine/bo/AccountBo.php");
require_once("engine/bo/PersonBo.php");

$connection = openConnection();
$personBo = PersonBo::newInstance($connection);
$accountBo = AccountBo::newInstance($connection);

$account = json_decode(urldecode($arguments["account"]), true);
$person = json_decode(urldecode($arguments["person"]), true);

// login unicity

if ($accountBo->exists($account["login"], $person["mail"])) {
	echo json_encode(array("ko" => "ko", "message" => "alreadyExists"));
	exit();
}

$_person = array();
$_person["per_firstname"] = $person["firstname"];
$_person["per_lastname"] = $person["lastname"];
//$_person["per_telephone"] = $person["telephone"];
$_person["per_mail"] = $person["mail"];
$_person["per_address_1"] = $person["address_1"];
$_person["per_address_2"] = $person["address_2"];
$_person["per_zip_code"] = $person["zip_code"];
$_person["per_city"] = $person["city"];

$_account = array();
$_account["acc_login"] = $account["login"];
$_account["acc_password"] = AccountBo::computePassword($account["password"]);
$_account["acc_language"] = "fr";

$personBo->save($_person);
$_account["acc_person_id"] = $_person["per_id"];
$accountBo->save($_account);

echo json_encode(array("ok" => "ok"));

?>