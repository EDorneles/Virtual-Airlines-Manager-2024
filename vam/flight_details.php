<?php
	/**
	 * @Project: Virtual Airlines Manager (VAM)
	 * @Author: Alejandro Garcia
	 * @Web http://virtualairlinesmanager.net
	 * Copyright (c) 2013 - 2016 Alejandro Garcia
	 * VAM is licensed under the following license:
	 *   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
	 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
	 */
?>
<?php
	if (empty($_SESSION['id'])) {
 	 	$sessionid = 'nosession';
	}
	else
	{
 		$sessionid = $_SESSION["id"];
	}
  if (($pilot_public != 1) && ($sessionid == 'nosession'))
	  {
	    require('check_login.php');
	  }
	  else
	  {
		$vamflightid = $_GET['flight_id'];
		/* Connect to Database */
		$db = new mysqli($db_host , $db_username , $db_password , $db_database);
		$db->set_charset("utf8");
		if ($db->connect_errno > 0) {
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		// Execute SQL query
		$sql = "select * , date_format(flight_date,'$va_date_format') as flight_date from vam_track vt , vampireps vp ,gvausers u where vt.flight_id = vp.flightid and u.gvauser_id = vp.gvauser_id and vp.flightid='" . $vamflightid . "'";
	?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading"><IMG src="images/icons/ic_flight_white_18dp_1x.png">&nbsp;<?php echo VAMACARS_FLITGH_DETAILS; ?></div>
			<?php
				if (!$result = $db->query($sql)) {
					die('There was an error running the query  [' . $db->error . ']');
				}
				while ($row = $result->fetch_assoc()) {
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					<tr>
						<td><strong><?php echo FSKEEPER_PILOT; ?></strong></td>
						<td><?php echo $row["callsign"] ." ".$row["name"] ." ".$row["surname"]; ?></td>
						<td><strong><?php echo FSKEEPER_AIRCRAFT; ?></strong></td>
						<td><?php echo $row["aircraft"]; ?></td>
						<td><strong><?php echo FSKEEPER_DISTANCE; ?></strong></td>
						<td><?php echo $row["distance"] . 'NM'; ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_DEPARTURE; ?></strong></td>
						<td><?php echo $row["departure"]; ?></td>
						<td><strong><?php echo VAMACARS_ARRIVAL; ?></strong></td>
						<td><?php echo $row["arrival"]; ?></td>
						<td><strong><?php echo VAMACARS_DURATION; ?></strong></td>
						<td><?php echo convertTime(number_format($row["flight_duration"],2),$va_time_format); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_STATUS_VALIDATION; ?></strong></td>
						<td><?php if ($row["validated"] == '1') {
								echo VAMACARS_STATUS_VALIDATED;
							} elseif
								($row["validated"] == '2'){
								echo VAMACARS_STATUS_REJECTED;
							} else {
								echo VAMACARS_STATUS_NOVALIDATED;
							} ?></td>
						<td><strong><?php echo VAMACARS_TYPE; ?></strong></td>
						<td><?php if ($row["charter"] == '1') {
								echo VAMACARS_FLIGHT_CHARTER;
							} else {
								echo VAMACARS_FLIGHT_REGULAR;
							} ?></td>
						<td><strong><?php echo VAMACARS_REGISTRY; ?></strong></td>
						<td><?php echo $row["aircraft_registry"]; ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_ZFW; ?></strong></td>
						<td><?php echo $row["zfw"]; ?></td>
						<td><strong><?php echo VAMACARS_BFUEL_FOB; ?></strong></td>
						<td><?php echo number_format($row["block_fuel"],0) .'/'.number_format($row["fob"],0); ?></td>
						<td><strong><?php echo VAMACARS_FFUEL; ?></strong></td>
						<td><?php echo number_format($row["flight_fuel"],0); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_PASSENGERS; ?></strong></td>
						<td><?php echo $row["pax"]; ?></td>
						<td><strong><?php echo VAMACARS_CARGO; ?></strong></td>
						<td><?php echo $row["cargo"]; ?></td>
						<td><strong><?php echo VAMACARS_ALTERNATE1; ?></strong></td>
						<td><?php echo $row["alt1"]; ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_DEP_TIME; ?></strong></td>
						<td><?php echo $row["departure_time"]; ?></td>
						<td><strong><?php echo VAMACARS_CRUISE_SPEED; ?></strong></td>
						<td><?php echo $row["cruise_speed"]; ?></td>
						<td><strong><?php echo VAMACARS_FLIGHT_LEVEL; ?></strong></td>
						<td><?php echo $row["flight_level"]; ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_FLIGHT_TYPE; ?></strong></td>
						<td><?php echo $row["flight_type"]; ?></td>
						<td><strong><?php echo VAMACARS_AIRCRAFT_TYPE; ?></strong></td>
						<td><?php echo $row["aircraft_type"]; ?></td>
						<td><strong><?php echo VAMACARS_WEIGHTUNIT; ?></strong></td>
						<td><?php echo $row["weight_unit"]; ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_DATEDETAIL; ?></strong></td>
						<td><?php echo $row["flight_date"]; ?></td>
						<td><strong><?php echo VAMACARS_COMMENTS; ?></strong></td>
						<td><?php echo $row["comments"]; ?></td>
						<td><strong><?php echo VAMACARS_NETWORK; ?></strong></td>
						<td><?php echo $row["network"]; ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_CRASH; ?></strong></td>
						<td><?php
							if ($row["crash"]==0)
							{
								echo VAMACARS_NO;
							}
							else
							{
								echo VAMACARS_YES;
							}
						 ?></td>
						<td><strong><?php echo VAMACARS_VALIDATOR_COMMENTS; ?></strong></td>
						<td><?php echo $row["validator_comments"]; ?></td>
						<td><strong><?php echo VAMACARS_FLIGHT_RATING; ?></strong></td>
						<td><?php echo $row["rating"]; ?></td>
					</tr>
				</table>
			</div>
			<br>
		</div>
	</div>
</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_public_white_18dp_1x.png">&nbsp;<?php echo VAMACARS_ROUTE; ?></div>
				<table class="table table-hover">
					<tr>
						<td><strong><?php echo VAMACARS_ROUTE; ?></strong></td>
						<td><?php echo $row["route"] ; ?></td>
					</tr>
					<tr>
						<td><strong><?php echo VAMACARS_REMARKS; ?></strong></td>
						<td><?php echo $row["remarks"] ; ?></td>
						<td><strong><?php echo EMPRESA; ?></strong></td>
						<td><?php echo $row["virtual_airline"] ; ?></td>
						<td><strong><?php echo SITE; ?></strong></td>
						<td><?php echo $row["va_url"] ; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_public_white_18dp_1x.png">&nbsp;<?php echo METAR; ?></div>
				<table class="table table-hover">
					<tr>
						<td><strong><?php echo $row["departure"]; ?></strong></td>
						<td><?php echo $row["departure_metar"] ; ?></td>
						<td><strong><?php echo $row["arrival"]; ?></strong></td>
						<td><?php echo $row["arrival_metar"] ; ?></td>
						
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_photo_filter_white_18dp_1x.png">&nbsp;<?php echo VAMACARS_FLIGHTFAILURES; ?></div>
				<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<td><strong><?php echo VAMACARS_CRASH; ?></strong></td>
							<td><?php echo ($row["crash"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_BEACONOFFENGINEON; ?></strong></td>
							<td><?php echo ($row["beacon_off"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_IAS1000; ?></strong></td>
							<td><?php echo ($row["ias_below_10000_ft"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_LIGHTBELOW1000; ?></strong></td>
							<td><?php echo ($row["lights_below_10000_ft"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_LIGHTABOVE1000; ?></strong></td>
							<td><?php echo ($row["lights_above_10000_ft"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_STALL; ?></strong></td>
							<td><?php echo ($row["stall"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_OVERSPEED; ?></strong></td>
							<td><?php echo ($row["overspeed"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_PAUSE; ?></strong></td>
							<td><?php echo ($row["pause"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_REFUEL; ?></strong></td>
							<td><?php echo ($row["refuel"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_SLEW; ?></strong></td>
							<td><?php echo ($row["slew"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_TAXILIGHTS; ?></strong></td>
							<td><?php echo ($row["taxi_no_lights"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_TAKEOFFLIGHTSOFF; ?></strong></td>
							<td><?php echo ($row["takeoff_ldn_lights_off"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_LANDINGLIGHTSOFF; ?></strong></td>
							<td><?php echo ($row["landing_ldn_lights_off"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_NOTARRIVED; ?></strong></td>
							<td><?php echo ($row["landed_not_in_planned"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_TAXISPEED; ?></strong></td>
							<td><?php echo ($row["taxi_speed"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_TAKEOFFQNH; ?></strong></td>
							<td><?php echo ($row["qnh_takeoff"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo VAMACARS_LANDINGQNH; ?></strong></td>
							<td><?php echo ($row["qnh_landing"] == 1 ?  VAMACARS_FAIL : VAMACARS_OK )  ; ?></td>
							<td><strong><?php echo "" ?></strong></td>
							<td><?php echo ""  ; ?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_flight_land_white_18dp_1x.png">&nbsp;<?php echo VAMACARS_LANDANALYSIS; ?></div>
				<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<td><strong><?php echo LAND_WIND; ?></strong></td>
							<td><?php echo $row["landing_winddeg"] .'º/' .$row["landing_windknots"]. ' kt'; ?></td>
							<td><strong><?php echo LAND_HDG; ?></strong></td>
							<td><?php echo $row["landing_hdg"] ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo FSKEEPER_LANDVS; ?></strong></td>
							<td><?php echo $row["landing_vs"] . ' ft/min'; ?></td>
							<td><strong><?php echo FSKEEPER_LANDIAS; ?></strong></td>
							<td><?php echo $row["landing_ias"] . ' kt'; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_LANDING_FORCEG; ?></strong></td>
							<td><?php echo $row["landing_forceg"] . ' G'; ?></td>
							<td><strong><?php echo VAMACARS_LANDING_BANK; ?></strong></td>
							<td><?php echo $row["landing_bank"] ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_LANDING_PITCH; ?></strong></td>
							<td><?php echo $row["landing_pitch"] ; ?></td>
							<td><strong><?php echo VAMACARS_LANDING_FLAPS; ?></strong></td>
							<td><?php echo $row["landing_flaps"] ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_LANDING_NAV; ?></strong></td>
							<td><?php if ($row["landing_light_nav"]==1) echo 'ON'; else echo 'OFF' ; ?></td>
							<td><strong><?php echo VAMACARS_LANDING_LDG; ?></strong></td>
							<td><?php if ($row["landing_light_ldn"]==1) echo 'ON'; else echo 'OFF' ; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo VAMACARS_LANDING_BEA; ?></strong></td>
							<td><?php if ($row["landing_light_bea"]==1) echo 'ON'; else echo 'OFF' ; ?></td>
							<td><strong><?php echo VAMACARS_LANDING_STR; ?></strong></td>
							<td><?php if ($row["landing_light_str"]==1) echo 'ON'; else echo 'OFF' ; ?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_zoom_out_map_white_18dp_1x.png">&nbsp;<?php echo VAMACARS_VAM_MAP; ?></div>
				<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<?php
								include ('vampirep_map.php');
							?>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_timeline_white_18dp_1x.png">&nbsp;<?php echo VAMACARS_VAM_CHART; ?></div>
					<tr>
						<?php
						include ('vampirep_chart.php');
						?>
					</tr>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_monetization_on_white_18dp_1x.png">&nbsp;<?php echo FLIGHT_FINANCES; ?></div>
					<tr>
						<?php
						include ('flight_financial_report.php');
						?>
					</tr>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_zoom_in_white_18dp_1x.png">&nbsp;<?php echo VAMACARS_MAIN_EVENTS; ?></div>
				<div class="table-responsive" style="height:500px;overflow:auto">
					<table class="table table-hover">
						<tr>
							<?php
							$sql = "select * from vamevents where flight_id='" . $vamflightid . "' order by id asc";
							if (!$result = $db->query($sql)) {
								die('There was an error running the query [' . $db->error . ']');
							}
							echo "<tr><th>" . VAMACARS_DATE . " </th><th>" . VAMACARS_EVENTS . "</th><th>" . VAMACARS_IAS . "</th><th>" . VAMACARS_ALTITUDE . "</th>"."</tr>";
							while ($row = $result->fetch_assoc()) {
								echo "<td>";
								echo $row["event_timestamp"] . '</td> ';
								if ($row["critical"]=='1')
								{
									$critical= '<td class="danger">';
								}
								else
								{
									$critical='<td>';
								}
								echo $critical.$row["event_description"] . '</td><td>';
								echo $row["ias"] . '</td><td>';
								echo $row["altitude"] . '</td>';
								echo '</tr>';
							}
							?>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><IMG src="images/icons/ic_zoom_in_white_18dp_1x.png">&nbsp;<?php echo VAMACARS_ACARS_DATA; ?></div>
				<div class="table-responsive" style="height:500px;overflow:auto">
					<table class="table table-hover">
						<tr>
							<?php
							$sql = "select vt.time_flag, vt.flight_status,vt.ias,vt.gs,vt.altitude,vt.fuel_used,vt.oat from vam_track vt , vampireps vp where vt.flight_id = vp.flightid and vp.flightid='" . $vamflightid . "' order by vt.id asc";
							if (!$result = $db->query($sql)) {
								die('There was an error running the query [' . $db->error . ']');
							}
							echo "<tr><th>" . VAMACARS_DATE . " </th><th>" . VAMACARS_IAS . "</th><th>" . VAMACARS_GS . "</th><th>" . VAMACARS_ALTITUDE . "</th><th>" . VAMACARS_FUEL_USED . "</th>
						<th>" . VAMACARS_OAT . "</th><th>" . VAMACARS_FLIGHTSTATUS . "</th>"."</tr>";
							while ($row = $result->fetch_assoc()) {
								echo "<td>";
								echo $row["time_flag"] . '</td><td>';
								echo $row["ias"] . '</td><td>';
								echo $row["gs"] . '</td><td>';
								echo $row["altitude"] . '</td><td>';
								echo $row["fuel_used"] . '</td><td>';
								echo $row["oat"] . '</td><td>';
								echo $row["flight_status"]. '</td>';
								echo '</tr>';
							}
							?>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php
	$db->close();
	}
}
?>
