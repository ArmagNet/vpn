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

error_log("authenticate");

require_once("engine/bo/AccountBo.php");

$connection = openConnection();
$accountBo = AccountBo::newInstance($connection);

$account = json_decode(urldecode($arguments["account"]), true);

$login = $account["login"];
$password = $account["password"];

$account = $accountBo->login($login, $password);

if (!$account) {
	echo json_encode(array("ko" => "ko", "message" => "badCredentials"));
	exit();
}

$person = array();
$person["firstname"] = $account["per_firstname"];
$person["lastname"] = $account["per_lastname"];
$person["telephone"] = $account["per_telephone"];
$person["mail"] = $account["per_mail"];
$person["address_1"] = $account["per_address_1"];
$person["address_2"] = $account["per_address_2"];
$person["zip_code"] = $account["per_zip_code"];
$person["city"] = $account["per_city"];

echo json_encode(array("ok" => "ok", "person" => $person));

?>