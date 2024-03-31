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
<!DOCTYPE html>
<html>
<head>
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBFGDOGEjgbiPt7ge4UDfnP7QqAAR_g34&callback=initMap" type="text/javascript">
</script>
</head>
<body>
<?php
	$db_map = new mysqli($db_host , $db_username , $db_password , $db_database);
	$db_map->set_charset("utf8");
	if ($db_map->connect_errno > 0) {
		die('Unable to connect to database [' . $db_map->connect_error . ']');
	}
	$flights_coordinates = array ();
	$index = -1;
	$sql_map = "select latitude_deg, longitude_deg ,ident , airports.name as airport_name  from airports where ident='$airport'";
	if (!$result = $db_map->query($sql_map)) {
		die('There was an error running the query  [' . $db_map->error . ']');
	}
	while ($row = $result->fetch_assoc()) {
		$index++;
		$flights_coordinates [$index] = array ($row["latitude_deg"],  $row["longitude_deg"] ,  $row["ident"],  $row["airport_name"] ) ;
	}
?>
<div class="container">
	<div class="row">
		<div id="map-outer" class="col-md-11">
			<div id="map-container" class="col-md-12"></div>
		</div>
	</div>
</div>
<style>
	body { background-color:#FFFFF }
	#map-outer {
		padding: 0px;
		border: 0px solid #CCC;
		margin-bottom: 0px;
		background-color:#FFFFF }
	#map-container { height: 500px }
	@media all and (max-width: 991px) {
		#map-outer  { height: 650px }
	}
</style>
</body>
<script type="text/javascript">
	function init_map() {
		var locations = <?php echo json_encode($flights_coordinates); ?>;
		var var_location = new google.maps.LatLng(<?php echo $flights_coordinates[0][0]; ?>,<?php echo $flights_coordinates[0][1]; ?>);
		var var_mapoptions = {
			center: var_location,
			zoom: 14,
			mapTypeId: google.maps.MapTypeId.HYBRID ,
			styles: [{featureType:"road",elementType:"geometry",stylers:[{lightness:100},{visibility:"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#C6E2FF",}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#C5E3BF"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#D1D1B8"}]}]
		};
		var var_map = new google.maps.Map(document.getElementById("map-container"),
			var_mapoptions);
		var k=0;
		while (k<10) {
			dep = new google.maps.LatLng(locations[k][0], locations[k][1]);
			arr = new google.maps.LatLng(locations[k+1][0], locations[k+1][1]);
			var icon_red = 'images/airport_runway_red.png';
			var icon_green = 'images/airport_runway_green.png';
			var marker_dep = new google.maps.Marker({
				position: dep,
				icon: icon_green
			});
			var marker_arr = new google.maps.Marker({
				position: arr,
				icon: icon_green
			});
			marker_dep.setMap(var_map);
			marker_arr.setMap(var_map);
			var var_marker = new google.maps.Polyline({
				path: [dep, arr],
				geodesic: true,
				strokeColor: '#FF0000',
				strokeOpacity: 1.0,
				strokeWeight: 2
			});
			var_marker.setMap(var_map);
			var marker_dep = new google.maps.Marker({
				position: dep,
				icon: icon_green
			});
			var marker_arr = new google.maps.Marker({
				position: arr,
				icon: icon_green
			});
			marker_dep.setMap(var_map);
			marker_arr.setMap(var_map);
			k=k+2;
		}
	}
	google.maps.event.addDomListener(window, 'load', init_map);
</script>
</html>
