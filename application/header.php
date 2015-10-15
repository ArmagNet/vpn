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
session_start();
require_once("config/database.php");
include_once("language/language.php");
require_once("engine/bo/AccountBo.php");
include_once("engine/utils/bootstrap_forms.php");
require_once("engine/utils/SessionUtils.php");

$page = $_SERVER["SCRIPT_NAME"];
if (strrpos($page, "/") !== false) {
	$page = substr($page, strrpos($page, "/") + 1);
}
$page = str_replace(".php", "", $page);

$isConnected = false;
$accountId = SessionUtils::getUserId($_SESSION);

if ($accountId != null) {
	$isConnected = true;
}
else {
	if ($page != "index" && $page != "create_vpn" && strpos($page, "payment") === false) {
		header("Location: index.php");
		exit();
	}
}

$language = SessionUtils::getLanguage($_SESSION);

$connection = openConnection();

?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo lang("vpn_title"); ?></title>

<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link href="css/vpn.css" rel="stylesheet" />
<link href="css/flags.css" rel="stylesheet" />
<link href="css/social.css" rel="stylesheet" />
<link href="css/jquery.template.css" rel="stylesheet" />
    <!--link href="css/fileinput.min.css" rel="stylesheet" /-->

<link rel="shortcut icon" type="image/png" href="favicon.png" />

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery-1.11.1.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#otb-navbar-collapse">
					<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php"><img src="images/logo.svg" style="position: relative; top: -14px; width: 48px; height: 48px; background-color: #ffffff;"
					data-toggle="tooltip" data-placement="bottom"
					title="VPN" /> </a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="otb-navbar-collapse">
				<ul class="nav navbar-nav">
					<li <?php if ($page == "index") echo 'class="active"'; ?>><a href="index.php"><?php echo lang("menu_index"); ?><?php if ($page == "index") echo ' <span class="sr-only">(current)</span>'; ?></a></li>
					<li <?php if ($page == "create_vpn") echo 'class="active"'; ?>><a href="create_vpn.php"><?php echo lang("menu_create_vpn"); ?><?php if ($page == "create_vpn") echo ' <span class="sr-only">(current)</span>'; ?></a></li>
					<?php if ($isConnected) {?>
					<li <?php if ($page == "vpns") echo 'class="active"'; ?>><a href="vpns.php"><?php echo lang("menu_vpns"); ?><?php if ($page == "vpns") echo ' <span class="sr-only">(current)</span>'; ?></a></li>
					<?php 	if (false) {?>
					<li <?php if ($page == "wifi") echo 'class="active"'; ?>><a href="wifi.php"><?php echo lang("menu_wifi"); ?><?php if ($page == "wifi") echo ' <span class="sr-only">(current)</span>'; ?></a></li>
					<li <?php if ($page == "tv") echo 'class="active"'; ?>><a href="tv.php"><?php echo lang("menu_tv"); ?><?php if ($page == "tv") echo ' <span class="sr-only">(current)</span>'; ?></a></li>
					<li <?php if ($page == "telephone") echo 'class="active"'; ?>><a href="telephone.php"><?php echo lang("menu_telephone"); ?><?php if ($page == "telephone") echo ' <span class="sr-only">(current)</span>'; ?></a></li>
					<?php 	}?>
					<?php }?>
				</ul>

				<ul class="nav navbar-nav navbar-right">

					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo str_replace("{language}", lang("language_$language"), lang("menu_language")); ?> <span
							class="caret"></span> </a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="do_changeLanguage.php?lang=en"><span class="flag en" title="<?php echo lang("language_en"); ?>"></span> <?php echo lang("language_en"); ?></a></li>
							<li><a href="do_changeLanguage.php?lang=fr"><span class="flag fr" title="<?php echo lang("language_fr"); ?>"></span> <?php echo lang("language_fr"); ?></a></li>
						</ul>
					</li>

					<?php 	if ($isConnected) {?>
					<li><a class="logoutLink" href="do_logout.php"><span class="glyphicon glyphicon-log-out" title="<?php echo lang("menu_logout"); ?>"></span><span class="sr-only">Logout</span> </a></li>
					<?php 	}?>
				</ul>
			</div>
		</div>
	</nav>