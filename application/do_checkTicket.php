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

require_once("config/database.php");
require_once("engine/bo/TicketBo.php");

$connection = openConnection();

$ticketBo = TicketBo::newInstance($connection);

$ticket = $ticketBo->getTicketByKey($_REQUEST["ticket"]);

$data = array();

//$data["result"] = print_r($ticket, true);

if (!$ticket || $ticket["tic_use_date"]) {
	$data["ko"] = "ko";
}
else {
	$data["ok"] = "ok";
	$data["product"] = $ticket["tic_product_code"];
}

echo json_encode($data);

exit();

?>