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
include_once("header.php");

?>

<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_payment"); ?></li>
	</ol>

	<div class="well well-sm">
		<p><?php echo lang("payment_failed_guide"); ?></p>
	</div>

	<div class="col-md-12 text-center">
		<?php 	if (isset($_REQUEST["referer"]) && $_REQUEST["referer"]) {?>
			<a href="<?php echo $_REQUEST["referer"]; ?>"><?php echo lang("payment_referer_link"); ?></a>
		<?php 	} else {?>
			<a href="index.php"><?php echo lang("payment_home_link"); ?></a>
		<?php 	}?>
	</div>
</div>

<?php include("footer.php");?>