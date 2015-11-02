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
require_once("engine/bo/VpnServerBo.php");

$vpnServerBo = VpnServerBo::newInstance($connection);
$servers = $vpnServerBo->get();

function humanFileSize($bytes, $si, $decimals = 0) {
	$thresh = $si ? 1000 : 1024;
	if(abs($bytes) < $thresh) {
		return number_format($bytes, $decimals) . ' B';
	}

	$units = $si ?
	array('kB','MB','GB','TB','PB','EB','ZB','YB')
	: array('KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB');
	$u = -1;
	do {
		$bytes /= $thresh;
		$u++;
	}
	while(abs($bytes) >= $thresh && $u < count($units) - 1);

	return number_format($bytes, $decimals) . ' ' . $units[$u];
}

?>

<style>
.form-horizontal .control-label {
	padding-top: 0px;
}
</style>

<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_servers"); ?></li>
	</ol>

	<div class="col-md-12">

		<table class="table">
			<thead>
				<tr>
					<th class="server-name"><?php echo lang("servers_name"); ?></th>
					<th class="server-description"><?php echo lang("servers_description"); ?></th>
					<th class="server-users"><?php echo lang("servers_users"); ?></th>
					<th class="server-occupation"><?php echo lang("servers_occupation"); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php 	foreach($servers as $server) {?>
				<tr id="server-<?php echo $server["vse_id"]; ?>" data-bandwidth="<?php echo $server["vse_bandwidth"]; ?>">
					<td><?php echo $server["vse_label"]; ?></td>
					<td><?php echo lang("servers_server_description"); ?> : <?php echo str_replace("iB", "b", humanFileSize($server["vse_bandwidth"] * 8, false, 0)); ?>ps</td>
					<td class="users text-right">0</td>
					<td class="used-capacity">
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
								style="width: 0%; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
								<span style="position: relative; left: 2px;">0.0%</span>
							</div>
						</div>
					</td>
				</tr>
			<?php 	}?>
			</tbody>
		</table>

	</div>
</div>

<?php include("footer.php");?>