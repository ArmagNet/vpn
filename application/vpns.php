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

require_once("engine/bo/VpnBo.php");

$vpnBo = VpnBo::newInstance($connection);
$vpns = $vpnBo->getVpns(array("with_account" => 1, "with_servers" => 1, "vpn_account_id" => $accountId));

?>

<style>
.form-horizontal .control-label {
	padding-top: 0px;
}
</style>

<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_vpns"); ?></li>
	</ol>

	<div class="col-md-12">

<?php 	foreach($vpns as $vpn) {
			$vpnId = "vpn-" . $vpn["vpn_id"] . "-" . $vpn["vse_id"];
?>

	<div class="panel-group">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-target="#<?php echo $vpnId; ?>" href="#<?php echo $vpnId; ?>" class="collapsed">
						<?php echo $config["vpn"]["service_name"]; ?> - <?php echo $vpn["vpn_cn"]; ?> - <?php echo $vpn["vse_label"]; ?>
					</a>
				</h4>
			</div>
			<div id="<?php echo $vpnId; ?>" class="panel-collapse collapse">
				<div class="panel-body">

					<form class="form-horizontal">
						<fieldset>

							<div class="form-group">
								<label class="col-md-2 control-label" for="keyButton"><?php echo lang("vpn_configuration_key");?> :</label>
								<div class="col-md-4">
									<?php if ($vpn["vpn_key"]) {?>
									<a href="do_retrieveKey.php?vid=<?php echo $vpn["vpn_id"]; ?>&sid=<?php echo $vpn["vse_id"]; ?>&type=key"><?php echo lang("vpn_configuration_retrievefile"); ?></a>
									<?php } else {?>
										<?php echo lang("vpn_configuration_nokey"); ?>
									<?php }?>
								</div>
								<label class="col-md-2 control-label" for="certButton"><?php echo lang("vpn_configuration_cert");?> :</label>
								<div class="col-md-4">
									<a href="do_retrieveKey.php?vid=<?php echo $vpn["vpn_id"]; ?>&sid=<?php echo $vpn["vse_id"]; ?>&type=cert"><?php echo lang("vpn_configuration_retrievefile"); ?></a>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="caButton"><?php echo lang("vpn_configuration_cacert");?> :</label>
								<div class="col-md-4">
									<a href="do_retrieveKey.php?vid=<?php echo $vpn["vpn_id"]; ?>&sid=<?php echo $vpn["vse_id"]; ?>&type=cacert"><?php echo lang("vpn_configuration_retrievefile"); ?></a>
								</div>
								<label class="col-md-2 control-label" for="caButton"><?php echo lang("vpn_configuration_dh");?> :</label>
								<div class="col-md-4">
									<a href="do_retrieveKey.php?vid=<?php echo $vpn["vpn_id"]; ?>&sid=<?php echo $vpn["vse_id"]; ?>&type=dh"><?php echo lang("vpn_configuration_retrievefile"); ?></a>
								</div>
							</div>

							<!-- Select -->
							<div class="form-group">
								<label class="col-md-2 control-label" for="devInput"><?php echo lang("vpn_configuration_dev");?> :</label>
								<div class="col-md-4">
									<?php echo $vpn["vse_dev"]; ?>
								</div>
								<label class="col-md-2 control-label" for="protoInput"><?php echo lang("vpn_configuration_proto");?> :</label>
								<div class="col-md-4">
									<?php echo $vpn["vse_proto"]; ?>
								</div>
							</div>

							<!-- Select -->
							<div class="form-group">
								<label class="col-md-2 control-label" for="cipherInput"><?php echo lang("vpn_configuration_cipher");?> :</label>
								<div class="col-md-4">
									<?php echo $vpn["vse_cipher"]; ?>
								</div>
								<label class="col-md-2 control-label" for="compLzoInput"><?php echo lang("vpn_configuration_complzo");?> :</label>
								<div class="col-md-4">
									<?php echo $vpn["vse_comp_lzo"]; ?>
								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-2 control-label" for="remoteIpInput"><?php echo lang("vpn_configuration_remoteip");?> :</label>
								<div class="col-md-4">
									<?php echo $vpn["vse_remote_ip"]; ?>
								</div>
								<label class="col-md-2 control-label" for="remoteIpInput"><?php echo lang("vpn_configuration_remoteport");?> :</label>
								<div class="col-md-4">
									<?php echo $vpn["vse_remote_port"]; ?>
								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-2 control-label" for="remoteCertTlsInput"><?php echo lang("vpn_configuration_remotecerttls");?> :</label>
								<div class="col-md-4">
									<?php echo $vpn["vse_remote_cert_tls"]; ?>
								</div>
							</div>

						</fieldset>
						<fieldset id="opvnFieldset">
							<legend></legend>

							<div class="form-group">
								<label class="col-md-8 control-label" for="ovpnLink"><?php echo lang("vpn_configuration_ovpn");?> :</label>
								<div class="col-md-4">
									<a href="do_retrieveKey.php?vid=<?php echo $vpn["vpn_id"]; ?>&sid=<?php echo $vpn["vse_id"]; ?>&type=ovpn"><?php echo lang("vpn_configuration_retrievefile"); ?></a>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-8 control-label" for="endDateLabel"><?php echo lang("vpn_configuration_end_date");?> :</label>
								<div class="col-md-4">
									<?php

									$date = new DateTime($vpn["vpn_end_date"]);

									echo $date->format("d/m/Y");

									?>
								</div>
							</div>

						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>

<?php 	}?>

	</div>
</div>

<?php include("footer.php");?>