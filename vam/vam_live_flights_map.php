<?php

?>
<!DOCTYPE html>
<html>
<head>
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBFGDOGEjgbiPt7ge4UDfnP7QqAAR_g34&callback=initMap" type="text/javascript">
</script>
	<meta http-equiv="refresh" content="300">
</head>
<body>
<?php
	include('./db_login.php');
	$db_map = new mysqli($db_host , $db_username , $db_password , $db_database);
	$db_map->set_charset("utf8");
	if ($db_map->connect_errno > 0) {
		die('Unable to connect to database [' . $db_map->connect_error . ']');
	}
	$sql_map = "select plane_type,ias,flight_id,u.gvauser_id as gvauser_id,u.callsign as callsign,u.name as name,gs,altitude,surname,departure,arrival,latitude,longitude,flight_status,heading,perc_completed,
				pending_nm, a1.latitude_deg as dep_lat, a1.longitude_deg as dep_lon , a2.latitude_deg as arr_lat, a2.longitude_deg as arr_lon , network
				from vam_live_flights lf, gvausers u , airports a1, airports a2 where u.gvauser_id=lf.gvauser_id and lf.departure=a1.ident and lf.arrival=a2.ident";
	$sql_map = "select r.flight as flightnum,r.comments,plane_type,ias,flight_id,u.gvauser_id as gvauser_id,u.callsign as callsign,u.name as name, gs,altitude,surname,lf.departure, lf.arrival,lf.latitude,lf.longitude,lf.flight_status,lf.heading,lf.perc_completed,
					pending_nm, a1.latitude_deg as dep_lat, a1.longitude_deg as dep_lon , a2.latitude_deg as arr_lat, a2.longitude_deg as arr_lon , network
				from vam_live_flights lf, gvausers u , airports a1, airports a2, routes r where u.gvauser_id=lf.gvauser_id  and lf.departure=a1.ident and lf.arrival=a2.ident and r.route_id= u.route_id";
	if (!$result = $db_map->query($sql_map)) {
		die('There was an error running the query  [' . $db_map->error . ']');
	}
	unset($flights_coordinates);
	unset($flight);
	unset($liveflights);
	unset($datos);
	unset($jsonarray);
	$flights_coordinates = array();
	$datos = array ();
	$flight = array();
	$liveflights = array ();
	$jsonarray = array ();
	$index = 0;
	$index2=0;
	$flightindex=0;
	while ($row = $result->fetch_assoc()) {
			$flight["gvauser_id"]=$row["gvauser_id"];
			$flight["callsign"]=$row["callsign"];
      $flight["flightnum"]=$row["flightnum"];
      $flight["comments"]=$row["comments"];
			$flight["name"]=$row["name"];
			$flight["gs"]=$row["gs"];
			$flight["ias"]=$row["ias"];
			$flight["altitude"]=$row["altitude"];
			$flight["surname"]=$row["surname"];
			$flight["departure"]=$row["departure"];
			$flight["arrival"]=$row["arrival"];
			$flight["latitude"]=$row["latitude"];
			$flight["longitude"]=$row["longitude"];
			$flight["flight_status"]=$row["flight_status"];
			$flight["heading"]=$row["heading"];
			$flight["dep_lat"]=$row["dep_lat"];
			$flight["dep_lon"]=$row["dep_lon"];
			$flight["arr_lat"]=$row["arr_lat"];
			$flight["arr_lon"]=$row["arr_lon"];
			$flight["perc_completed"]=$row["perc_completed"];
			$flight["pending_nm"]=$row["pending_nm"];
			$flight["network"]=$row["network"];
			$flight["plane_type"]=$row["plane_type"];
			$liveflights[$flightindex] =$flight;
		$sql_map2 = "select * from vam_live_acars where flight_id='".$row["flight_id"]."' order by id desc";  
		if (!$result2 = $db_map->query($sql_map2)) {
			die('There was an error running the query  [' . $db_map->error . ']');
		}
			while ($row2 = $result2->fetch_assoc()) {
				$flights_coordinates ["gvauser_id"] = $row2["gvauser_id"];
				$flights_coordinates ["latitude"] = $row2["latitude"];
				$flights_coordinates ["longitude"] = $row2["longitude"];
				$flights_coordinates ["heading"] = $row2["heading"];
				$datos [$index2][$index] = $flights_coordinates;
				$index ++;
				}
		$index=0  ;
		$index2 ++;
		$flightindex ++;
	}
	$jsonarray[0]=$liveflights;
	$jsonarray[1]=$datos;
?>
<div class="container"  style="margin-left:0px">
	<div class="row">
		<div id="map-outer" class="col-md-11">
			<div id="map-container" class="col-md-12"></div>
			 <div id="over_map"></div>
		</div><!-- /map-outer -->
	</div> <!-- /row -->
</div><!-- /container -->
<style>
	body { background-color:#FFFFF }
	#map-outer {
		padding: 0px;
		border: 0px solid #CCC;
		margin-bottom: 0px;
		background-color:#FFFFF }
	#map-container { height: 500px; width: 1550px; } 
	/* @media all and (max-width: 991px) { */
	/*@media all and (max-width: 1600px) {*/
		/*#map-outer  { height: 650px; width: 1500px; }*/
		
	}
</style>
<style>
   
   #wrapper { position: relative; }
   #over_map { position: absolute; top: 50px; left: 10px; z-index: 100; background: white;}

  #over_map {
   position: absolute;
   left: 10px;
   width: 150px;
   top: 0;
   z-index: 99;
  }
</style>
</body>
<script type="text/javascript">
	var mapCentre;
	var map ;
	function init_map() {
		var flights = <?php echo json_encode($jsonarray[0]); ?>;
		var locations = <?php echo json_encode($jsonarray[1]); ?>;
		var numpoints=(locations.length);
		console.log(locations);
		var var_location = new google.maps.LatLng(<?php echo $datos[0][0]["latitude"]; ?>,<?php echo $datos[0][0]["longitude"]; ?>);
		var var_mapoptions = {
			center: var_location,
			zoom: 5,
			styles: [{"featureType":"all","elementType":"all","stylers":[{"saturation":"0"},{"lightness":"0"}]},{"featureType":"all","elementType":"geometry","stylers":[{"lightness":"20"}]},{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"on"},{"color":"#716464"},{"weight":"0.01"}]},{"featureType":"administrative.country","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"geometry.stroke","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"visibility":"simplified"}]},{"featureType":"poi.attraction","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ffad99"},{"lightness":"45"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#fbac99"},{"lightness":"1"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.fill","stylers":[{"color":"#ffff80"},{"lightness":"50"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#ff6666"},{"lightness":"59"},{"visibility":"on"},{"weight":"1.10"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#fff13a"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#f2d344"},{"visibility":"off"},{"weight":"1.41"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"simplified"},{"color":"#12586f"},{"lightness":"20"},{"gamma":"6.95"},{"saturation":"-29"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"visibility":"on"}]}]
		};
		map = new google.maps.Map(document.getElementById('map-container'),	var_mapoptions);
		var mapas=[];
		var flightPlanCoordinates=[];
		var flightPath = new google.maps.Polyline({
		strokeColor: "#c3524f",
		strokeOpacity: 1,
		strokeWeight: 2,
		geodesic: true
		});
		var k=0;
		var z=0;
		var coordinate;
		while (k<numpoints) {
			while (z < locations[k].length)
			{
				coordinate =new google.maps.LatLng(locations[k][z]['latitude'],locations[k][z]['longitude']);
				flightPlanCoordinates.push(coordinate);
				z=z+1;
			}
			ruta = new google.maps.Polyline({
			geodesic: true,
			strokeColor: '#FF0000',
			strokeOpacity: 1.0,
			strokeWeight: 2
			});
			ruta.setPath(flightPlanCoordinates);
			mapas.push(ruta);
			z=0;
			k=k+1;
		};
		function createMarker(pos, t) {
		var coord=[];
		var pathcoord=[];
		var flight_id = t;
		currentPath = new google.maps.Polyline({
			geodesic: true,
			strokeColor: '#FF0000',
			strokeOpacity: 1.0,
			strokeWeight: 2
			});
		// Plane marker begin      
      
    var trueHeading = parseInt(flights[t]['heading']);      
    var plane_icon = "m 32,3.2 0.1,0 0.4,0.2 0.7,1 0.8,1.9 0.6,2.5 0.2,2.5 0.1,2.7 0,9.3 6.5,4.6 -0.3,-0.8 -0.2,0.3 -0.2,-0.3 -0.2,0.1 -0.1,-0.3 -0.2,-0.9 -0.1,-1.3 0,-1.6 0.1,-1.2 0.1,-0.4 0.2,-0.2 3.2,0 0.2,0.2 0.1,0.4 0.2,1.2 0,1.6 -0.1,1.3 -0.4,1.4 -0.1,-0.3 -0.1,0.3 -0.1,-0.3 -0.2,0.3 -0.2,-0.3 -0.4,1.6 17.5,12.3 0.6,0.6 0.4,0.6 1.2,2 0,1 -1.6,-1.4 -0.7,-0.5 -0.9,-0.5 -7.5,-3.2 -0.1,0.8 -0.2,0.3 -0.3,-0.6 -0.1,-0.8 -3.8,-1.6 -0.1,0.8 -0.2,0.3 -0.3,-0.7 -0.1,-0.7 -3.2,-1.4 -2,0 -0.1,0.7 -0.2,0.4 -0.2,-0.4 -0.1,-0.7 -5.7,0 0,9.1 -0.1,1.8 -0.1,1.6 -0.5,3.3 0,0.4 0.2,0.4 7,6.4 0.2,0.3 0.1,0.3 0.4,1.3 -9.2,-3.4 -0.7,2.9 -0.4,0 -0.7,-2.9 -9.2,3.4 0.4,-1.3 0.1,-0.3 0.2,-0.3 7,-6.4 0.2,-0.4 0,-0.4 -0.5,-3.3 -0.1,-1.7 -0.1,-1.7 0,-9.1 -5.7,0 -0.1,0.7 -0.2,0.5 -0.2,-0.5 -0.1,-0.7 -2,0 -3.3,1.4 -0.1,0.6 -0.3,0.6 -0.2,-0.3 -0.1,-0.7 -3.6,1.7 -0.1,0.6 -0.3,0.6 -0.2,-0.3 -0.1,-0.6 -7.6,3.2 -0.9,0.5 -0.7,0.5 -1.6,1.4 0,-1 1.2,-2 0.4,-0.6 0.6,-0.6 17.5,-12.3 -0.4,-1.6 -0.2,0.3 -0.2,-0.3 -0.1,0.3 -0.1,-0.3 -0.1,0.3 -0.3,-1.4 -0.1,-1.3 0,-1.6 0.1,-1.2 0.1,-0.4 0.2,-0.2 3.2,0 0.2,0.2 0.1,0.4 0.1,1.2 0,1.6 -0.1,1.3 -0.2,0.9 -0.1,0.3 -0.2,-0.1 -0.2,0.3 -0.2,-0.3 -0.3,0.8 6.5,-4.6 0,-9.3 0.1,-2.7 0.2,-2.5 0.6,-2.5 0.8,-1.9 0.7,-1 0.4,-0.2 z";
          
      var symbol = {  
      path: plane_icon,
      fillColor: "#0e6bf7",
      scale: 0.444, 
      fillOpacity: 1.0,
      anchor: new google.maps.Point(30, 40),
      strokeWeight: 0,
      rotation: trueHeading
      } 
      
    
    
    var icon_airport = new google.maps.MarkerImage("./map_icons/airport_yellow_marker.png");
		var lineSymbol = {path: 'M 0,-1 0,1', strokeOpacity: 1, scale: 1 };
		var lat1 = flights[t]["dep_lat"];
		var lat2 = flights[t]["arr_lat"];
		var lng1 = flights[t]["dep_lon"];
		var lng2 = flights[t]["arr_lon"];
		var dep = new google.maps.LatLng(lat1, lng1)
		var arr = new google.maps.LatLng(lat2, lng2)
		var icon_plane = 'images/plane.png';
		var marker = new google.maps.Marker({
			position: pos,
			icon: symbol,
			name: t ,
			contenido: '<center><span style="align="center">' +flights[t]['name'] +' ' + flights[t]['surname'] 
                  + '</span></center><br><center><span style="font-weight: bold"; align="center">' +flights[t]['flightnum'] + ' | ' + flights[t]['plane_type'] + '</span></center><br><center>' 
                  + flights[t]['departure'] + ' <i class="fa fa-angle-right"></i>  ' + flights[t]['arrival'] +'</center>',
						icao1:new google.maps.Marker({
							position: dep,
							 map: map,
							 icon: icon_airport,
							 visible: false
						}),
						icao2:new google.maps.Marker({
							position: arr,
							 map: map,
							 icon: icon_airport,
							 visible: false
						}),
                        line1:new google.maps.Polyline({
							path: [dep, pos],
							strokeColor: "#08088A",
							strokeOpacity: 1,
							strokeWeight: 2,
							geodesic: true,
							map: map,
							polylineID: t,
							visible: false
                        })	,
                        line2:new google.maps.Polyline({
							path: [pos, arr],
							strokeColor: "#FE2E2E",
							strokeOpacity: .3,
							geodesic: true,
							map: map,
							icons: [{
								icon: lineSymbol,
								offset: '0',
								repeat: '5px'
							}],
							polylineID: t,
							visible: false
                        })
		});
		// On mouse over
        google.maps.event.addListener(marker, 'mouseover', function () {
            //infowindow.open(map, marker);
            this.get('line1').setVisible(true);
            this.get('line2').setVisible(true);
			this.get('icao1').setVisible(true);
			this.get('icao2').setVisible(true);
			infowindow.open(map,marker);
		    infowindow.setContent(marker.contenido);
		    var s=0;
		    coord.length = 0;
		    pathcoord.length = 0;
		   while (s < locations[flight_id].length)
			 {
			 	coord= new google.maps.LatLng(locations[flight_id][s]['latitude'],locations[flight_id][s]['longitude']);
			 	pathcoord.push(coord);
			 	s=s+1;
			 }
			 currentPath.setPath(pathcoord);
			 currentPath.setMap(map);
        });
		// On mouse end
		// mouse out
        google.maps.event.addListener(marker, 'mouseout', function () {
            //infowindow.close();
            //this.get('line1').setVisible(false);
            //this.get('line2').setVisible(false);
			//this.get('icao1').setVisible(false);
			//this.get('icao2').setVisible(false);
			//currentPath.setMap(null);
        });
		// mouse out end
		// On Click begin
		google.maps.event.addListener(marker, 'click', function() {
		   infowindow.open(map,marker);
		   infowindow.setContent(marker.contenido);
		   var s=0;
		   coord.length = 0;
		   pathcoord.length = 0;
		  while (s < locations[flight_id].length)
			{
				coord= new google.maps.LatLng(locations[flight_id][s]['latitude'],locations[flight_id][s]['longitude']);
				pathcoord.push(coord);
				s=s+1;
			}
			currentPath.setPath(pathcoord);
			currentPath.setMap(map);
			if (this.get('line1').visible ='true')
			{
				this.get('line1').setVisible(false);
			}
			if (this.get('line2').visible ='true')
			{
				this.get('line2').setVisible(false);
			}
			if (this.get('icao1').visible ='true')
			{
				this.get('icao1').setVisible(false);
			}
			if (this.get('icao2').visible ='true')
			{
				this.get('icao2').setVisible(false);
			}
			flight_detail='<div class="box box-success panel panel-map">\
						  <!-- Default panel contents -->\
						  <div class=\"panel-heading\">'+flights[t]['departure'] + ' <i class="fa fa-arrow-right"></i> ' + flights[t]['arrival']+'</div>\
						  <table class="table-map">\
              <td>\
						    <tr>\
						    	<td><small>Flight completed</small><br><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="\
								'+flights[t]['perc_completed'] + '" aria-valuemin="0" aria-valuemax="100" style="width:'+flights[t]['perc_completed']+'%">\
								'+flights[t]['perc_completed'] + ' %</div></td>\
						    </tr>\
						  </tr>\
              <tr>\
						    	<td><small>Pilot</small><br><strong>\
						    	'+flights[t]['callsign'] + '</strong></td>\
						    </tr>\
              <tr>\
						    	<td><small>Flight</small><br><strong>\
						    	'+flights[t]['flightnum'] + '</strong></td>\
						  </tr>\
							<tr>\
						    	<td><small>Flight Status</small><br><strong>\
						    	'+flights[t]['flight_status'] + '</strong></td>\
						    </tr>\
						    <tr>\
						    	<td><small>Plane Type</small><br><strong>\
						    	'+flights[t]['plane_type'] + '</strong></td>\
						    </tr>\
						    <tr>\
						    	<td><small>Pending NM</small><br><strong>\
						    	'+flights[t]['pending_nm'] + '</strong></td>\
						    </tr>\
						    <tr>\
						    	<td><small>GS</small><br><strong>\
						    	'+flights[t]['gs'] + '</strong></td>\
						    </tr>\
                <tr>\
						    	<td><small>IAS</small><br><strong>\
						    	'+flights[t]['ias'] + '</strong></td>\
						    </tr>\
						    <tr>\
						    	<td><small>Altitude</small><br><strong>\
						    	'+flights[t]['altitude'] + 'FT' + '</strong></td>\
						    </tr>\
						    <tr>\
						    	<td><small>Heading</small><br><strong>\
						    	'+flights[t]['heading'] + 'º' + '</strong></td>\
						    </tr>\
						  </table>\
						</div>';
      $('#over_map').html("<div id='mySecondDiv'>"+flight_detail+"</div>");
		});
		// On Click end
		return marker;
	}
		var numflight=0
		while (numflight < flights.length )
		{
			var avionicon =new google.maps.LatLng(flights[numflight]['latitude'],flights[numflight]['longitude']);
			var m1 = createMarker(avionicon, numflight);
			m1.setMap(map);
			numflight = numflight +1;
		}
		var s=0;
		while (s < mapas.length)
		{
			s=s+1;
		}
		var infowindow = new google.maps.InfoWindow({
		  });
		google.maps.event.addListener(infowindow, 'closeclick', function() {
		$('#over_map').html("");
		});
	}
	google.maps.event.addDomListener(window, 'load', init_map);
	$( document ).ready(refreshflights);
	function refreshflights(){
		setInterval(function () {$.ajax({
			  url: 'get_map_coordinates.php',
			  data: "",
			  dataType: 'json',
			  success: function(data, textStatus, jqXHR) {
				init_map();
				}
			})}, 120000);
	}
</script>
</html>