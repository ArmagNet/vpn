<?php /*
	Copyright 2014 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of Parpaing.

    Parpaing is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Parpaing is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Parpaing.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once("config/database.php");

$method = $_GET["method"];

error_log("VPN API Method : $method");

// Security

if (strpos($method, "..") !== false) {
	echo json_encode(array("error" => "not_a_service"));
	exit();
}

if (!file_exists("api/$method.php")) {
	echo json_encode(array("error" => "not_a_service"));
	exit();
}

$arguments = $_POST;

error_log(print_r($_POST, true));

include("api/$method.php");

?>