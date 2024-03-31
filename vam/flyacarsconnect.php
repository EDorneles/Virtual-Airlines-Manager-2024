<?php
/*
 * FlyAcarsConnect
 * Description: this is the code for receive and send data to FlyAcars
 * Author: Rodrigo Sousa
 * Version:1.3.0
 */

//info
$version = "1.0.1";
$url = "https://dreamsky.com.br/vam/downloads/flyacarsdsy.exe";
$debug = false;

if($debug){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

//read input data
$data = json_decode(file_get_contents('php://input'), true);
$action = $data["action"];
$pilot = strtoupper($data["pilot"]);
$password = $data['password'];
$id = "";

/*
 * check version
 */
if ($action == 'version') {
    $json[]= array(
        'number' => $version,
        'url' => $url
    );
    echo json_encode($json);
    die();
}

/*
 *database connection
 */
include('db_login.php');
$db = new mysqli($db_host , $db_username , $db_password , $db_database);
$db->set_charset("utf8");
if ($db->connect_errno > 0) {
    echo json_encode(array(array('error'=>'501', 'message'=>'Unable to connect to database [' . $db->connect_error . ']')));
	die();
}

//get user data
$userExists = 0;
$sql = "SELECT * FROM gvausers where activation=1 and UPPER(callsign)='" . $pilot . "' and password='" . $password . "'";
if (!$result = $db->query($sql)) {
    echo json_encode(array(array('error'=>'502', 'message'=>'There was an error running the query [' . $db->error . ']')));
	die();
}
while ($row = $result->fetch_assoc()) {
	$userExists = 1;
	$id = $row['gvauser_id'];
}
if($userExists == 0){
    echo json_encode(array(array('error'=>'503', 'message'=>'User not found')));
    die();
}

/*
 * get user last scheduled flight
 */
if ($action == 'connection') {
	$exists = 0;
	$id='FAIL';

	$sql = "SELECT * FROM gvausers where activation=1 and UPPER(callsign)='" . $pilot . "' and password='" . $password . "'";
	if (!$result = $db->query($sql)) {
		echo json_encode(array(array('error'=>'511', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
	}
	while ($row = $result->fetch_assoc()) {
		$exists = 1;
		$id = $row['gvauser_id'];
	}

	if ($exists != 0) {
        $sql = "update fleets set booked=0 ,gvauser_id=NULL , booked_at=NULL where booked=1 and hangar=0 and gvauser_id=$id and fleet_id not in (select fleet_id from reserves)";
        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'512', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }

        $sql = "select departure, arrival, flproute,alternative,flight,etd,duration, plane_icao,registry, re.pax as pax, re.cargo as cargo from reserves re inner join fleets f on re.fleet_id=f.fleet_id inner join routes r on re.route_id=r.route_id inner join fleettypes ft on ft.fleettype_id = f.fleettype_id where re.gvauser_id=$id";
        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'513', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        while ($row = $result->fetch_assoc()) {
            $exists = 1;
            $departure = $row['departure'];
            $arrival = $row['arrival'];
            $route = $row['flproute'];
            $flight = $row['flight'];
            $etd = $row['etd'];
            $plane_icao = $row['plane_icao'];
            $duration = $row['duration'];
            $registry = $row['registry'];
            $pax = $row['pax'];
            $cargo = $row['cargo'];
        }

        $json[]= array(
            'id' => $id,
            'departure' => $departure,
            'arrival' => $arrival,
            'route' => $route,
            'number' => $flight,
            'departure_time' => $etd,
            'aircraft_type' => $plane_icao,
            'aircraft_registration' => $registry,
            'duration' => $duration,
            'passengers' => $pax,
            'cargo' => $cargo
        );
		$jsonstring = json_encode($json);
		echo $jsonstring;
        die();
	}
	else{
		$json[]= array(
            'id' => $id,
            'departure' => "",
            'arrival' => "",
            'route' => "",
            'number' => "",
            'departure_time' => "",
            'aircraft_type' => "",
            'aircraft_registration' => "",
            'duration' => "",
            'passengers' => "",
            'cargo' => ""
        );
		$jsonstring = json_encode($json);
		echo $jsonstring;
        die();
	}
}

/*
 * book aircraft
 */
if($action == "bookaircraft"){
    $registration = $data["aircraft"];
	if ($userExists != 0)
	{
		$sql = "update fleets set booked=1, booked_at = now(), gvauser_id=$id where registry='$registration'";
		if (!$result = $db->query($sql)) {
			die('There was an error running the query [' . $db->error . ']');
		}
	}
    echo json_encode(array(array('error'=>'0', 'success'=>'888', 'message'=>'Aircraft booked!')));
}

/*
 * get user last scheduled flight
 */
if ($action == 'aircrafts') {
	$i=0;

    //verify if charter flights is allowed
	$sql = "SELECT * FROM va_parameters";
	if (!$result = $db->query($sql)) {
		echo json_encode(array(array('error'=>'531', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
	}
	while ($row = $result->fetch_assoc()) {
		$allow_select = $row['allow_select_aircraft_for_charter'];
	}

    //generate response
	if ($allow_select==1) {
		$sql = "select gva.location as location ,f.fleet_id,f.name, f.registry,ft.plane_icao  from fleettypes_gvausers ftgva inner join gvausers gva on gva.gvauser_id = ftgva.gvauser_id inner join fleets f on f.fleettype_id = ftgva.fleettype_id inner join fleettypes ft on ft.fleettype_id=f.fleettype_id and f.fleettype_id=ftgva.fleettype_id and f.location=gva.location and f.booked=0 and f.hangar=0 and gva.gvauser_id=$id";

		if (!$result = $db->query($sql)) {
			echo json_encode(array(array('error'=>'532', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
		}
		$json = NULL;
		while ($row = $result->fetch_assoc()) {
			$json[$i]= array(
			'airport' => $row['location'],
			'fleet_id' => $row['fleet_id'],
			'name' => $row['name'],
			'aircraft_type' => $row['plane_icao'],
			'aircraft_registration' => $row['registry']
			)	;

			$i=$i + 1;
		}

		echo json_encode($json);
	}
	else{
		echo json_encode(array(array('error'=>'533', 'message'=>'Charter flight not allowed.')));
        die();
	}
}

/*
 * get airports info
 */
if ($action == 'airport') {
    $ident = strtoupper($data["ident"]);

    //get
    $departure = null;
	$sql = "select ident, name, latitude_deg, longitude_deg, elevation_ft from airports where ident='$ident'";
	if (!$result = $db->query($sql)) {
        echo json_encode(array(array('error'=>'541', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
	}
	while ($rowDeparture = $result->fetch_assoc()) {
        $departure = $rowDeparture;
	}

    
    //make response
    $json[]= array(
        'ident'=> $departure['ident'],
        'latitude'=> $departure['latitude_deg'],
        'longitude'=> $departure['longitude_deg'],
        'altitude'=> $departure['elevation_ft'],
        'name'=> $departure['name']
    );
    echo json_encode($json);
    die();
}

/*
 * reset flight status
 */
if ($action == 'reset') {
	$sql = "SELECT * FROM gvausers where activation=1 and UPPER(callsign)='" . $pilot . "' and password='" . $password . "'";
	if (!$result = $db->query($sql)) {
        echo json_encode(array(array('error'=>'551', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
	}
    $exists = 0;
	while ($row = $result->fetch_assoc()) {
		$exists = 1;
		$gvauser = $row["gvauser_id"];
	}
	if ($exists != 0)
	{
		$sql = "update fleets set booked=0 ,gvauser_id=NULL , booked_at=NULL where booked=1 and hangar=0 and fleet_id not in (select fleet_id from reserves) and (UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(booked_at)) >86400";
		if (!$result = $db->query($sql)) {
			echo json_encode(array(array('error'=>'552', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
		}
	}

    echo json_encode(array(array('error'=>'0', 'success'=>'666', 'message'=>'Flight data has been reset!')));
}

/*
 * send report
 */
if ($action == 'report') {
	$dataLenght = count($data);
    for ($x=0; $x < $dataLenght ;$x++)
    {
        $gvauser_id= $data["user_id"];
        $departure= $data["departure"];
        $arrival= $data["arrival"];
        $flightId = $data["flight_id"];
        $ias = $data["indicated_air_speed"];
        $heading = $data["heading"];
        $gs = $data["ground_speed"];
        $altitude = $data["altitude"];
        $fuel = $data["fuel"];
        $fuel_used = $data["fuel_used"];
        $latitude = $data["latitude"];
        $longitude = $data["longitude"];
        $time_passed = $data["time_passed"];
        $perc_completed = $data["percent_completed"];
        $oat = $data["outside_air_temperature"];
        $wind_deg = $data["wind_direction"];
        $wind_knots = $data["wind_speed"];
        $flight_status = $data["flight_status"];
        $plane_type = $data["aircraft_type"];
        $pending_nm = $data["pending_distance"];
        $network = $data["network"];
        $sql = "insert into vam_live_acars (gvauser_id,flight_id,departure,arrival,ias,heading,gs,altitude,fuel,fuel_used,latitude,longitude,time_passed,perc_completed,oat,wind_deg,wind_knots,flight_status,plane_type,pending_nm,network)
        values ('$gvauser_id ','$flightId','$departure' ,'$arrival' ,'$ias','$heading','$gs','$altitude','$fuel','$fuel_used'
        ,'$latitude','$longitude','$time_passed','$perc_completed','$oat','$wind_deg','$wind_knots','$flight_status','$plane_type','$pending_nm','$network')";
        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'561', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        $sql = "delete from vam_live_flights where flight_id='$flightId'";
        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'562', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        $sql = "insert into vam_live_flights (gvauser_id,flight_id,departure,arrival,ias,heading,gs,altitude,fuel,fuel_used,latitude,longitude,time_passed,perc_completed,oat,wind_deg,wind_knots,flight_status,plane_type,pending_nm,network)
        values ('$gvauser_id ','$flightId ','$departure' ,'$arrival' ,'$ias','$heading','$gs','$altitude','$fuel','$fuel_used'
        ,'$latitude','$longitude','$time_passed','$perc_completed','$oat','$wind_deg','$wind_knots','$flight_status','$plane_type','$pending_nm','$network')";
        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'563', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
    }
    $db->close();
    echo json_encode(array(array(
        'error'=>'0', 'success'=>'888', 'message'=>'Report saved!')));
}

/*
 * check pirep info
 */
if ($action == 'check') {
    $flightId = strtoupper($data["ident"]);

	$numevents=0;
	$numtracks=0;
	$sql = "SELECT count(*) numinserted FROM vamevents where flight_id='". $flightId."'";
	if (!$result = $db->query($sql)) {
		echo json_encode(array(array('error'=>'625', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
	}
	while ($row = $result->fetch_assoc()) {
		$numevents = $row["numinserted"];
	}
	$sql = "SELECT count(*) numinserted FROM vam_track where flight_id='". $flightId."'";
	if (!$result = $db->query($sql)) {
		echo json_encode(array(array('error'=>'626', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
	}
	while ($row = $result->fetch_assoc()) {
		$numtracks = $row["numinserted"];
	}
	$json[]= array(
        'error'=>'0', 
        'success'=>'777', 
        'message'=>'Pirep successfully received!',
        'flight_id' => $flightId,
		'events' => $numevents,
		'reports' => $numtracks,
		);
    echo json_encode($json);
    die();
}

/*
 * send pirep
 */
if ($action == 'pirep') {
    include('./classes/class_vam_mailer.php');

    $weight_unit = $data["pirep"]["weight_unit"];
    $dep = $data["pirep"]["departure"];
	$arr = $data["pirep"]["arrival"];
	$callsign = $data["pirep"]["callsign"];
	$flight_id = $data["pirep"]["flight_id"];
	$gvauser_id = $data["pirep"]["user_id"];
	$departure_time= $data["pirep"]["departure_time"];
	$cruise_speed= $data["pirep"]["cruise_speed"];
	$flight_level= $data["pirep"]["flight_level"];
	$pax= $data["pirep"]["passengers"];
	$cargo= $data["pirep"]["cargo"];
	$eet= $data["pirep"]["estimated_time"];
	$endurance= $data["pirep"]["endurance"];
	$alt1= $data["pirep"]["alternative"];
	$alt2= $data["pirep"]["alternative_2"];
	$route= $data["pirep"]["route"];
	$remarks= $data["pirep"]["remarks"];
	$flight_type= $data["pirep"]["flight_type"];
	$aircraft= $data["pirep"]["aircraft"];
	$aircraft_type= $data["pirep"]["aircraft_type"];
	$aircraft_registry= $data["pirep"]["aircraft_registration"];
	$flight_status= $data["pirep"]["flight_status"];
	$flight_duration= $data["pirep"]["flight_duration"];
	$flight_fuel= $data["pirep"]["flight_fuel"];
	$block_fuel= $data["pirep"]["block_fuel"];
	$flight_date= $data["pirep"]["flight_date"];
	$landing_vs= $data["pirep"]["landing_vs"];
	$distance= $data["pirep"]["distance"];
	$landing_ias	= $data["pirep"]["landing_ias"];
	$landing_forceg	= $data["pirep"]["landing_forceg"];
	$landing_bank	= $data["pirep"]["landing_bank"];
	$landing_pitch	= $data["pirep"]["landing_pitch"];
	$landing_winddeg	= $data["pirep"]["landing_wind_direction"];
	$landing_windknots	= $data["pirep"]["landing_wind_speed"];
	$landing_oat	= $data["pirep"]["landing_oat"];
	$landing_flaps	= $data["pirep"]["landing_flaps"];
	$landing_light_bea	= $data["pirep"]["landing_light_beacon"];
	$landing_light_nav	= $data["pirep"]["landing_light_navigation"];
	$landing_light_ldn	= $data["pirep"]["landing_light_landing"];
	$landing_light_str	= $data["pirep"]["landing_light_strobes"];
	$log_start	= $data["pirep"]["start_timestamp"];
	$flight_start	= $data["pirep"]["flight_start_timestamp"];
	$log_end	= $data["pirep"]["end_timestamp"];
	$flight_end	= $data["pirep"]["flight_end_timestamp"];
	$zfw	= $data["pirep"]["zfw"];
	$departure_metar	= $data["pirep"]["departure_metar"];
	$arrival_metar	= $data["pirep"]["arrival_metar"];
	$network	= $data["pirep"]["network"];
	$comments	= $data["pirep"]["comments"];
	$pause_time = $data["pirep"]["pause_time"];
	$crash = $data["pirep"]["flag_crash"];
	$beacon_off = $data["pirep"]["flag_beacon_off"];
	$ias_below_10000_ft = $data["pirep"]["flag_ias_below_10000_ft"];
	$lights_below_10000_ft = $data["pirep"]["flag_lights_below_10000_ft"];
	$lights_above_10000_ft = $data["pirep"]["flag_lights_above_10000_ft"];
	$stall = $data["pirep"]["flag_stall"];
	$overspeed = $data["pirep"]["flag_overspeed"];
	$pause = $data["pirep"]["flag_pause"];
	$refuel = $data["pirep"]["flag_refuel"];
	$slew = $data["pirep"]["flag_slew"];
	$taxi_no_lights = $data["pirep"]["flag_taxi_and_lights_off"];
	$takeoff_ldn_lights_off = $data["pirep"]["flag_takeoff_landing_lights_off"];
	$landing_ldn_lights_off = $data["pirep"]["flag_landing_landing_lights_off"];
	$landed_not_in_planned = $data["pirep"]["flag_landed_in_another_airport"];
	$taxi_speed = $data["pirep"]["flag_taxi_speed"];
	$qnh_takeoff = $data["pirep"]["flag_qnh_takeoff"];
	$qnh_landing = $data["pirep"]["flag_qnh_landing"];
	$final_fuel = $data["pirep"]["final_fuel"];
	$landing_hdg = $data["pirep"]["landing_heading"];
	$flight = $data["pirep"]["flight_id"];
    $aircraftreg = $data["pirep"]["aircraft_registration"];
	$fob =0;
	$is_charter=1;
	include('db_login.php');
	$db = new mysqli($db_host , $db_username , $db_password , $db_database);
	$db->set_charset("utf8");
	if ($db->connect_errno > 0) {
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$duplicado= check_duplicated_flight($flight , $db);
	if ($duplicado==0){
		$is_charter_var = is_charter_flight($gvauser_id, $dep, $arr , $flight_duration,  $block_fuel, $distance , $landing_vs , $flight ,$db);
		instert_flight ($flight, $data , $db);
		check_regular_flight ($gvauser_id, $dep, $arr , $flight_duration,  $block_fuel, $distance , $landing_vs , $flight ,$db);
		move_pilot ($gvauser_id, $arr , $db);
		auto_approval ($flight_id , $db);
		if ($is_charter_var==1)
		{
			move_charter_aircraft ($aircraft_registry,$gvauser_id, $dep, $arr , $flight_duration,  $block_fuel, $distance , $landing_vs , $flight ,$db);
		}
		clean_reserve($gvauser_id, $db);
		flight_rating($flight, $db);
		// Send mail to the staff
		$type='VAM ACARS';
		$mail = new vam_mailer();
		$mail->mail_flight_report_compose($gvauser_id,$type,$dep,$arr);

        $tracksCount = insert_tracks($data, $db);

        $eventsCount = insert_events($data, $db);

        update_schedule($flight, $db);

        echo json_encode(array(array(
            'error'=>'0',
            'success'=>'555',
            'message'=>'Pirep successfully received!'
        )));
        die();
	}
	else
	{
		echo json_encode(array(array('error'=>'571', 'message'=>'Duplicated flight.')));
        die();
	}
}


/*
 * PIREP FUNCTIONS
 */
/**
 * atualiza a tabela de escala de voo caso exista esse voo na escala
 */
function update_schedule($flight, $db){

    //recupera o ID do pirep para colocar na tabela de escala
    $sql="select * from vampireps where flightid=?";
    $stmt = $db->prepare($sql); 
    $stmt->bind_param("s", $flight);
    $stmt->execute();

    if (!$result = $stmt->get_result()) {
        echo json_encode(array(array('error'=>'573', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
    $row = $result->fetch_assoc();

    $sql="UPDATE schedule_pilot SET vampireps_id = ? WHERE gvauser_id = ? AND departure=? AND arrival = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iiss", $row["id"], $row["gvauser_id"], $row["departure"], $row["arrival"]);
    $stmt->execute();

} // END function update_schedule

function instert_flight($flight , $data ,$db){
    $weight_unit = $data["pirep"]["weight_unit"];
    $dep = $data["pirep"]["departure"];
	$arr = $data["pirep"]["arrival"];
	$callsign = $data["pirep"]["callsign"];
	$flight_id = $data["pirep"]["flight_id"];
	$gvauser_id = $data["pirep"]["user_id"];
	$departure_time= $data["pirep"]["departure_time"];
	$cruise_speed= $data["pirep"]["cruise_speed"];
	$flight_level= $data["pirep"]["flight_level"];
	$pax= $data["pirep"]["passengers"];
	$cargo= $data["pirep"]["cargo"];
	$eet= $data["pirep"]["estimated_time"];
	$endurance= $data["pirep"]["endurance"];
	$alt1= $data["pirep"]["alternative"];
	$alt2= $data["pirep"]["alternative_2"];
	$route= $data["pirep"]["route"];
	$remarks= $data["pirep"]["remarks"];
	$flight_type= $data["pirep"]["flight_type"];
	$aircraft= $data["pirep"]["aircraft"];
	$aircraft_type= $data["pirep"]["aircraft_type"];
	$aircraft_registry= $data["pirep"]["aircraft_registration"];
	$flight_status= $data["pirep"]["flight_status"];
	$flight_duration= $data["pirep"]["flight_duration"];
	$flight_fuel= $data["pirep"]["flight_fuel"];
	$block_fuel= $data["pirep"]["block_fuel"];
	$flight_date= $data["pirep"]["flight_date"];
	$landing_vs= $data["pirep"]["landing_vs"];
	$distance= $data["pirep"]["distance"];
	$landing_ias	= $data["pirep"]["landing_ias"];
	$landing_forceg	= $data["pirep"]["landing_forceg"];
	$landing_bank	= $data["pirep"]["landing_bank"];
	$landing_pitch	= $data["pirep"]["landing_pitch"];
	$landing_winddeg	= $data["pirep"]["landing_wind_direction"];
	$landing_windknots	= $data["pirep"]["landing_wind_speed"];
	$landing_oat	= $data["pirep"]["landing_oat"];
	$landing_flaps	= $data["pirep"]["landing_flaps"];
	$landing_light_bea	= $data["pirep"]["landing_light_beacon"];
	$landing_light_nav	= $data["pirep"]["landing_light_navigation"];
	$landing_light_ldn	= $data["pirep"]["landing_light_landing"];
	$landing_light_str	= $data["pirep"]["landing_light_strobes"];
	$log_start	= $data["pirep"]["start_timestamp"];
	$flight_start	= $data["pirep"]["flight_start_timestamp"];
	$log_end	= $data["pirep"]["end_timestamp"];
	$flight_end	= $data["pirep"]["flight_end_timestamp"];
	$zfw	= $data["pirep"]["zfw"];
	$departure_metar	= $data["pirep"]["departure_metar"];
	$arrival_metar	= $data["pirep"]["arrival_metar"];
	$network	= $data["pirep"]["network"];
	$comments	= $data["pirep"]["comments"];
	$pause_time = $data["pirep"]["pause_time"];
	$crash = $data["pirep"]["flag_crash"];
	$beacon_off = $data["pirep"]["flag_beacon_off"];
	$ias_below_10000_ft = $data["pirep"]["flag_ias_below_10000_ft"];
	$lights_below_10000_ft = $data["pirep"]["flag_lights_below_10000_ft"];
	$lights_above_10000_ft = $data["pirep"]["flag_lights_above_10000_ft"];
	$stall = $data["pirep"]["flag_stall"];
	$overspeed = $data["pirep"]["flag_overspeed"];
	$pause = $data["pirep"]["flag_pause"];
	$refuel = $data["pirep"]["flag_refuel"];
	$slew = $data["pirep"]["flag_slew"];
	$taxi_no_lights = $data["pirep"]["flag_taxi_and_lights_off"];
	$takeoff_ldn_lights_off = $data["pirep"]["flag_takeoff_landing_lights_off"];
	$landing_ldn_lights_off = $data["pirep"]["flag_landing_landing_lights_off"];
	$landed_not_in_planned = $data["pirep"]["flag_landed_in_another_airport"];
	$taxi_speed = $data["pirep"]["flag_taxi_speed"];
	$qnh_takeoff = $data["pirep"]["flag_qnh_takeoff"];
	$qnh_landing = $data["pirep"]["flag_qnh_landing"];
	$final_fuel = $data["pirep"]["final_fuel"];
	$landing_hdg = $data["pirep"]["landing_heading"];
	$flight = $data["pirep"]["flight_id"];
    $aircraftreg = $data["pirep"]["aircraft_registration"];
    $fob =0;
    $sql = "insert into vampireps (
        flightid,
       gvauser_id,
      departure,
      arrival ,
      callsign ,
      departure_time,
      cruise_speed ,
      flight_level ,
      pax  ,
      cargo ,
      eet ,
      endurance ,
      alt1 ,
      alt2 ,
      route ,
      remarks ,
      flight_type ,
      aircraft ,
      aircraft_type ,
      aircraft_registry ,
      flight_status ,
      flight_fuel ,
      block_fuel,
      flight_duration ,
      flight_date ,
      landing_vs,
      distance,
      landing_ias,
      landing_forceg,
      landing_bank,
      landing_pitch,
      landing_winddeg,
      landing_windknots,
      landing_oat,
      landing_flaps,
      landing_light_bea,
      landing_light_nav,
      landing_light_ldn,
      landing_light_str,
      log_start,
      flight_start,
      log_end,
      flight_end,
      zfw,
      departure_metar,
      arrival_metar,
      weight_unit,
      network ,
      comments,
      pause_time,
      crash,
      beacon_off,
      ias_below_10000_ft ,
      lights_below_10000_ft ,
      lights_above_10000_ft ,
      stall ,
      overspeed ,
      pause ,
      refuel ,
      slew ,
      taxi_no_lights ,
      takeoff_ldn_lights_off ,
      landing_ldn_lights_off ,
      landed_not_in_planned,
      taxi_speed,
      qnh_takeoff,
      qnh_landing,
      final_fuel,
      landing_hdg,
      fob) values (
      '$flight_id',
      '$gvauser_id',
      '$dep',
      '$arr',
      '$callsign',
      '$departure_time',
    '$cruise_speed',
    '$flight_level',
    '$pax',
    '$cargo',
    '$eet',
    '$endurance',
    '$alt1',
    '$alt2',
    '$route',
    '$remarks',
    '$flight_type',
    '$aircraft',
    '$aircraft_type',
    '$aircraft_registry',
    '$flight_status',
    '$flight_fuel',
    '$block_fuel' ,
    '$flight_duration',
    '$flight_date',
    '$landing_vs',
    '$distance',
    '$landing_ias',
    '$landing_forceg',
    '$landing_bank',
    '$landing_pitch',
    '$landing_winddeg',
    '$landing_windknots',
    '$landing_oat',
    '$landing_flaps',
    '$landing_light_bea',
    '$landing_light_nav',
    '$landing_light_ldn',
    '$landing_light_str',
    '$log_start',
    '$flight_start',
    '$log_end',
    '$flight_end',
    '$zfw',
    '$departure_metar',
    '$arrival_metar',
    '$weight_unit',
    '$network',
    '$comments',
    '$pause_time',
    '$crash',
    '$beacon_off',
    '$ias_below_10000_ft' ,
    '$lights_below_10000_ft' ,
    '$lights_above_10000_ft' ,
    '$stall' ,
    '$overspeed' ,
    '$pause' ,
    '$refuel' ,
    '$slew' ,
    '$taxi_no_lights' ,
    '$takeoff_ldn_lights_off' ,
    '$landing_ldn_lights_off' ,
    '$landed_not_in_planned',
    '$taxi_speed',
    '$qnh_takeoff',
    '$qnh_landing',
    '$final_fuel',
    '$landing_hdg',
    '$fob'
    )";
    if (!$result = $db->query($sql)) {
        echo json_encode(array(array('error'=>'572', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
} // END function instert_flight(
function check_duplicated_flight($flight, $db){
    $duplicated=1;
    $counter=0;
    $sql="select * from vampireps where flightid='".$flight."'";
    if (!$result = $db->query($sql)) {
        echo json_encode(array(array('error'=>'573', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
    $counter=mysqli_num_rows($result);
    if ($counter>0){
    return $duplicated;
    }
    else{
        $duplicated=0;
        return $duplicated;
    }
} // END function check_duplicated_flight
function move_charter_aircraft ($aircraftreg_para,$gvauser_id, $dep, $arr , $flight_duration,  $block_fuel, $distance , $landing_vs , $flight ,$db){
    if (!empty($aircraftreg_para)) // if to process logic only on charter aircraft when a VA aircraft was booked
    {
        $avion='';
        $sql="update fleets set location='$arr', booked=0,gvauser_id=NULL, booked_at=null, hours=hours+$flight_duration  where registry='$aircraftreg_para'";
        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'574', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        $sql="select * from  fleets where registry='$aircraftreg_para'";
        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'575', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        while ($row = $result->fetch_assoc()) {
            $avion = $row['fleet_id'];
        }
        // Calculate and update aircraft status
        // get VA parameters
        $sql = "select * from va_parameters";
        $va_name = '';
        $landing_vs_penalty1 = '';
        $landing_vs_penalty2 = '';
        $landing_penalty1 = '';
        $landing_penalty2 = '';
        $landing_penalty3 = '';
        $landing_crash = '';
        $plane_status_hangar = '';
        $hangar_maintenance_days = '';
        $hangar_maintenance_value = '';
        $hangar_crash_value = '';
        $hangar_crash_days = '';
        $pilot_crash_penalty = '';
        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'576', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        while ($row = $result->fetch_assoc()) {
            $va_name = $row["va_name"];
            $landing_vs_penalty1 = abs($row["landing_vs_penalty1"]);
            $landing_vs_penalty2 = abs($row["landing_vs_penalty2"]);
            $landing_penalty1 = $row["landing_penalty1"];
            $landing_penalty2 = $row["landing_penalty2"];
            $landing_penalty3 = $row["landing_penalty3"];
            $flight_wear = $row["flight_wear"];
            $landing_crash = abs($row["landing_crash"]);
            $plane_status_hangar = $row["plane_status_hangar"];
            $hangar_maintenance_days = $row["hangar_maintenance_days"];
            $hangar_maintenance_value = $row["hangar_maintenance_value"];
            $hangar_crash_value = $row["hangar_crash_value"];
            $hangar_crash_days = $row["hangar_crash_days"];
            $pilot_crash_penalty = $row["pilot_crash_penalty"];
        }
        // Update the plane
        $penalizacion_vs = 0;
        $landing_vs=abs($landing_vs);
        if ($landing_vs >= $landing_crash) {
            $penalizacion_vs = 999999; // $penalizacion_vs==999999 means a crash

        } elseif ($landing_vs >= $landing_vs_penalty2 && $landing_vs < $landing_crash) {
            $penalizacion_vs = $landing_penalty3;

        } elseif ($landing_vs >=$landing_vs_penalty1 && $landing_vs < $landing_vs_penalty2) {
            $penalizacion_vs = $landing_penalty2;

        } else {
            $penalizacion_vs = $landing_penalty1;

        }
        // update the aircraft status based on landing_vs
        if ($penalizacion_vs==999999)
        {
            $query2="update fleets set  status=status-$penalizacion_vs ,gvauser_id=NULL, booked_at=null where registry='$aircraftreg_para'";
        }
        else
        {
            $query2="update fleets set  status=status - $flight_wear - $penalizacion_vs ,gvauser_id=NULL, booked_at=null where registry='$aircraftreg_para'";
        }
        if (!$result2 = $db->query($query2)) {
            echo json_encode(array(array('error'=>'577', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        // check damage in plane and send it to the hangar if needed
        $query3 = "select * from  fleets  where registry='$aircraftreg_para'";
        if (!$result3 = $db->query($query3)) {
            echo json_encode(array(array('error'=>'578', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        $estado = 0;
        $matricula ='';
        $location ='';
        while ($row3 = $result3->fetch_assoc()) {
            $estado = $row3["status"];
            $avion = $row3["fleet_id"];
            $matricula = $row3["registry"];
            $location = $row3["location"];
        }
        $pilot = $gvauser_id;
        $origen = $dep;
        if ($estado < $plane_status_hangar && $estado > 0) {
            $query1 = "insert into hangar (registry,gvauser_id,fleet_id,departure,location,date_in,date_out,reason) values ('$matricula',$pilot,$avion,'$origen','$location',CURDATE(),ADDDATE(CURDATE(),$hangar_maintenance_days),'Maintenance')";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'579', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
            $query1 = "update fleets set booked=1 ,hangar=1, hangardate=now() where fleet_id=$avion";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'580', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
            $query1 = "insert into vaprofits (value,date,gvauser_id,description) values (-$hangar_maintenance_value, now(),$pilot ,'Aircraft Maintenace $matricula')";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'581', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
            // Cost for the VA for the maintenance
            $query1 = "insert into va_finances (amount,parameter_id,finance_date,gvauser_id,description,report_type,report_id) values (-$hangar_maintenance_value, '99997', now(),$pilot ,'Aircraft Maintenace $matricula','SIM ACARS', '$flight')";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'582', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
        }
        if ($estado <= 0) {
            $query1 = "insert into hangar (registry,gvauser_id,fleet_id,departure,location,date_in,date_out,reason) values ('$matricula',$pilot,$avion,'$origen','$location',CURDATE(),ADDDATE(CURDATE(),$hangar_crash_days),'Crash')";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'583', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
            $query1 = "update fleets set status=0, booked=1 where fleet_id=$avion";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'584', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
            $query1 = "insert into vaprofits (value,date,gvauser_id,description) values (-$hangar_crash_value, now(),$pilot ,'Aircraft Maintenance $matricula')";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'585', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
            $query1 = "insert into bank (gvauser_id, date,quantity,jump) values ($pilot,now(),-$pilot_crash_penalty,'Aircraft Crash $matricula')";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'586', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
            // Cost for the VA for the Crash repair
            $query1 = "insert into va_finances (amount,parameter_id,finance_date,gvauser_id,description,report_type,report_id) values (-$hangar_crash_value, '99996',now(),$pilot ,'Aircraft crash repair $matricula','SIM ACARS', '$flight')";
            if (!$result_sta = $db->query($query1)) {
                echo json_encode(array(array('error'=>'587', 'message'=>'There was an error running the query [' . $db->error . ']')));
                die();
            }
        }
        // Save flight historic
        $query = "insert into regular_flights_tracks (gvauser_id,date,departure,arrival,route_id, fuel,distance,fleet_id) values  (" . $gvauser_id . ",now(),'" . $dep . "','" . $arr ."','".'-1'. "'," . $block_fuel . "," . $distance . ",'$avion');";
        if (!$result_sta = $db->query($query)) {
            echo json_encode(array(array('error'=>'588', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
    }
    else
    {
        $xp=1;
    }
} // END function move_charter_aircraft
function is_charter_flight ($gvauser_id, $dep, $arr , $flight_duration,  $block_fuel, $distance , $landing_vs , $flight ,$db){
    $query = 'select * from gvausers gu, routes r where gu.gvauser_id="' . $gvauser_id . '" and gu.route_id <> 0 and r.departure="' . $dep . '" and r.arrival="' . $arr . '" and gu.route_id=r.route_id;';
    if (!$result = $db->query($query)) {
        echo json_encode(array(array('error'=>'589', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
    $number_of_rows = mysqli_num_rows($result);
    // if there is one record found means this flight is regular
    if ($number_of_rows > 0)
    {
        return 0;
    }
    else
    {
        return 1;
    }
} // END function is_charter_flight
function check_regular_flight ($gvauser_id, $dep, $arr , $flight_duration,  $block_fuel, $distance , $landing_vs , $flight ,$db){
    // get VA parameters
		$sql = "select * from va_parameters";
		$va_name = '';
		$landing_vs_penalty1 = '';
		$landing_vs_penalty2 = '';
		$landing_penalty1 = '';
		$landing_penalty2 = '';
		$landing_penalty3 = '';
		$landing_crash = '';
		$plane_status_hangar = '';
		$hangar_maintenance_days = '';
		$hangar_maintenance_value = '';
		$hangar_crash_value = '';
		$hangar_crash_days = '';
		$pilot_crash_penalty = '';
		if (!$result = $db->query($sql)) {
			die('There was an error running the query [' . $db->error . ']');
		}
		while ($row = $result->fetch_assoc()) {
			$va_name = $row["va_name"];
			$landing_vs_penalty1 = $row["landing_vs_penalty1"];
			$landing_vs_penalty2 = $row["landing_vs_penalty2"];
			$landing_penalty1 = $row["landing_penalty1"];
			$landing_penalty2 = $row["landing_penalty2"];
			$landing_penalty3 = $row["landing_penalty3"];
			$flight_wear = $row["flight_wear"];
			$landing_crash = $row["landing_crash"];
			$plane_status_hangar = $row["plane_status_hangar"];
			$hangar_maintenance_days = $row["hangar_maintenance_days"];
			$hangar_maintenance_value = $row["hangar_maintenance_value"];
			$hangar_crash_value = $row["hangar_crash_value"];
			$hangar_crash_days = $row["hangar_crash_days"];
			$pilot_crash_penalty = $row["pilot_crash_penalty"];
		}
		// try to find if there is a route booked for this pilot
		$query = 'select * from gvausers gu, routes r where gu.gvauser_id="' . $gvauser_id . '" and gu.route_id <> 0 and r.departure="' . $dep . '" and r.arrival="' . $arr . '" and gu.route_id=r.route_id;';
		$charter = 0;
		if (!$result = $db->query($query)) {
			die('There was an error running the query [' . $db->error . ']');
		}
		$number_of_rows = mysqli_num_rows($result);
		// if there is one record found means this flight is regular
		if ($number_of_rows > 0)
		{
			while ($row = $result->fetch_array())
			{
				$is_charter=0;
				$route_id = $row['route_id'];
				$regularflight = $row['flight'];
				$vs=abs($landing_vs);
				////////////////////////////
				// Update the plane
				///////////////////////////
				$penalizacion_vs = 0;
				if ($vs >= $landing_crash) {
					$penalizacion_vs = 999999;
				} elseif ($vs >= $landing_vs_penalty2 && $vs < $landing_crash) {
					$penalizacion_vs = $landing_penalty3;
				} elseif ($vs >= $landing_vs_penalty1 && $vs < $landing_vs_penalty2) {
					$penalizacion_vs = $landing_penalty2;
				} else {
					$penalizacion_vs = $landing_penalty1;
				}
				// set aircraft free, sum time, move the aircraft and set status based on landing v/s
				$query2 = 'update fleets set booked=0 ,gvauser_id=NULL, booked_at=NULL, status=status - ' . $flight_wear . ' - ' . $penalizacion_vs . ' , hours = hours + ' . $flight_duration . ' , location = "' . $arr . '" where fleet_id = (select fleet_id from  reserves where gvauser_id=' . $gvauser_id . ');';
				if (!$result2 = $db->query($query2)) {
					die('There was an error running the query [' . $db->error . ']');
				}
				// check damage in plane and send it to the hangar if needed
				$query3 = 'select * from  fleets  where fleet_id = (select fleet_id from  reserves where gvauser_id=' . $gvauser_id . ');';
				if (!$result3 = $db->query($query3)) {
					die('There was an error running the query [' . $db->error . ']');
				}
				$estado = 0;
				while ($row3 = $result3->fetch_assoc()) {
					$estado = $row3["status"];
					$avion = $row3["fleet_id"];
					$matricula = $row3["registry"];
					$location = $row3["location"];
				}
				$pilot = $gvauser_id;
				$origen = $dep;
				if ($estado < $plane_status_hangar && $estado > 0) {
					$query1 = "insert into hangar (registry,gvauser_id,fleet_id,departure,location,date_in,date_out,reason) values ('$matricula',$pilot,$avion,'$origen','$location',CURDATE(),ADDDATE(CURDATE(),$hangar_maintenance_days),'Maintenance')";
					if (!$result_sta = $db->query($query1)) {
						die('There was an error running the query [' . $db->error . ']');
					}
					$query1 = "update fleets set booked=1 ,hangar=1, hangardate=now() where fleet_id=$avion";
					if (!$result_sta = $db->query($query1)) {
						die('There was an error running the query [' . $db->error . ']');
					}
					$query1 = "insert into vaprofits (value,date,gvauser_id,description) values (-$hangar_maintenance_value, now(),$pilot ,'Maintenance $matricula')";
					if (!$result_sta = $db->query($query1)) {
						die('There was an error running the query [' . $db->error . ']');
					}
				}
				if ($estado < 0) {
					$query1 = "insert into hangar (registry,gvauser_id,fleet_id,departure,location,date_in,date_out,reason) values ('$matricula',$pilot,$avion,'$origen','$location',CURDATE(),ADDDATE(CURDATE(),$hangar_crash_days),'Crash')";
					if (!$result_sta = $db->query($query1)) {
						die('There was an error running the query [' . $db->error . ']');
					}
					$query1 = "update fleets set booked=1 , status=0 where fleet_id=$avion";
					if (!$result_sta = $db->query($query1)) {
						die('There was an error running the query [' . $db->error . ']');
					}
					$query1 = "insert into vaprofits (value,date,gvauser_id,description) values (-$hangar_crash_value, now(),$pilot ,'Maintenance $matricula')";
					if (!$result_sta = $db->query($query1)) {
						die('There was an error running the query [' . $db->error . ']');
					}
					$query1 = "insert into bank (gvauser_id, date,quantity,jump) values ($pilot,now(),-$pilot_crash_penalty,'Crash $matricula')";
					if (!$result_sta = $db->query($query1)) {
						die('There was an error running the query [' . $db->error . ']');
					}
				}
				// Historico de vuelos
				$query = "insert into regular_flights_tracks (gvauser_id,date,departure,arrival,route_id, fuel,distance,fleet_id) values  (" . $gvauser_id . ",now(),'" . $dep . "','" . $arr . "',$route_id," . $block_fuel . "," . $distance . ",'$avion');";
				if (!$result_sta = $db->query($query)) {
					die('There was an error running the query [' . $db->error . ']');
				}
				// Get pax and cargo from regular flight
				$assigned_pax=0;
				$assigned_cargo=0;
				$query = "select * from reserves where gvauser_id=$gvauser_id";
				if (!$result_sta = $db->query($query)) {
					die('There was an error running the query [' . $db->error . ']');
				}
				while ($row = $result_sta->fetch_assoc()) {
					$assigned_pax = $row["pax"];
					$assigned_cargo = $row["cargo"];
				}
				// update vampirep with regular flights data: cargo, pax .... it overwrites value for pax and cargo based on the reserve
				$query2 = "update vampireps set route_id=$route_id ,assigned_pax=$assigned_pax, pax=$assigned_pax, assigned_cargo= $assigned_cargo , cargo=$assigned_cargo,  charter=0 ,flight='".$regularflight."' where flightid='".$flight."'";
				if (!$result2 = $db->query($query2)) {
					die('There was an error running the query [' . $db->error . ']');
				}
			}
		}
		else {
		}
}
function move_pilot ($gvauser_id, $arr , $db){
    $query = 'UPDATE gvausers SET route_id=0 ,location="' . $arr . '"where gvauser_id=' . $gvauser_id . ';';
    if (!$result = $db->query($query)) {
        echo json_encode(array(array('error'=>'620', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
}
function clean_reserve ($gvauser_id, $db){
    $query = 'delete from reserves where gvauser_id=' . $gvauser_id . ';';
    if (!$result = $db->query($query)) {
        echo json_encode(array(array('error'=>'604', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
}
function flight_rating ($flight, $db){
    //  Get vamacars parameters
    $query = "select * from vamacars_parameters ";
    if (!$result = $db->query($query)) {
        echo json_encode(array(array('error'=>'605', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
    while ($row = $result->fetch_assoc()) {
        $beacon_off = $row["beacon_off"];
        $ias_below_10000_ft	 = $row["ias_below_10000_ft"];
        $lights_below_10000_ft = $row["lights_below_10000_ft"];
        $lights_above_10000_ft = $row["lights_above_10000_ft"];
        $stall = $row["stall"];
        $overspeed = $row["overspeed"];
        $pause = $row["pause"];
        $refuel = $row["refuel"];
        $crash = $row["crash"];
        $incorrect_arrival = $row["incorrect_arrival"];
        $slew = $row["slew"];
        $taxi_lights = $row["taxi_lights"];
        $takeoff_lights = $row["takeoff_lights"];
        $land_lights = $row["land_lights"];
        $taxi_speed = $row["taxi_speed"];
        $landing_vs_0_100 = $row["landing_vs_0_100"];
        $landing_vs_100_200 = $row["landing_vs_100_200"];
        $landing_vs_200_300 = $row["landing_vs_200_300"];
        $landing_vs_300_400 = $row["landing_vs_300_400"];
        $landing_vs_400_500 = $row["landing_vs_400_500"];
        $landing_vs_500_600 = $row["landing_vs_500_600"];
        $landing_vs_greater_600 = $row["landing_vs_greater_600"];
    }
    // Get flight data
    $query = "select * from vampireps where flightid='".$flight."'";
    if (!$result = $db->query($query)) {
        echo json_encode(array(array('error'=>'606', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
    while ($row = $result->fetch_assoc()) {
        $beacon_off_flight = $row["beacon_off"];
        $ias_below_10000_ft_flight = $row["ias_below_10000_ft"];
        $lights_below_10000_ft_flight = $row["lights_below_10000_ft"];
        $lights_above_10000_ft_flight = $row["lights_above_10000_ft"];
        $stall_flight = $row["stall"];
        $overspeed_flight = $row["overspeed"];
        $pause_flight = $row["pause"];
        $refuel_flight = $row["refuel"];
        $crash_flight = $row["crash"];
        $slew_flight = $row["slew"];
        $taxi_no_lights_flight = $row["taxi_no_lights"];
        $takeoff_ldn_lights_off_flight = $row["takeoff_ldn_lights_off"];
        $landing_ldn_lights_off_flight = $row["landing_ldn_lights_off"];
        $landed_not_in_planned_flight = $row["landed_not_in_planned"];
        $taxi_speed_flight = $row["taxi_speed"];
        $landing_vs_flight = abs($row["landing_vs"]);
    }
    $rating=100;
    $rating = $rating - ($beacon_off * $beacon_off_flight);
    $rating = $rating - ($ias_below_10000_ft * $ias_below_10000_ft_flight);
    $rating = $rating - ($lights_below_10000_ft * $lights_below_10000_ft_flight);
    $rating = $rating - ($lights_above_10000_ft * $lights_above_10000_ft_flight);
    $rating = $rating - ($stall * $stall_flight);
    $rating = $rating - ($overspeed * $overspeed_flight);
    $rating = $rating - ($pause * $pause_flight);
    $rating = $rating - ($refuel * $refuel_flight);
    $rating = $rating - ($crash * $crash_flight);
    $rating = $rating - ($slew * $slew_flight);
    $rating = $rating - ($taxi_lights * $taxi_no_lights_flight);
    $rating = $rating - ($takeoff_lights * $takeoff_ldn_lights_off_flight);
    $rating = $rating - ($land_lights * $landing_ldn_lights_off_flight);
    $rating = $rating - ($incorrect_arrival * $landed_not_in_planned_flight);
    $rating = $rating - ($taxi_speed * $taxi_speed_flight);
    $landingvs=0;
    if ($landing_vs_flight>0 && $landing_vs_flight<=100)
    {
        $landingvs = $landing_vs_0_100;
    }
    if ($landing_vs_flight>100 && $landing_vs_flight<=200)
    {
        $landingvs = $landing_vs_100_200;
    }
    if ($landing_vs_flight>200 && $landing_vs_flight<=300)
    {
        $landingvs = $landing_vs_200_300;
    }
    if ($landing_vs_flight>300 && $landing_vs_flight<=400)
    {
        $landingvs = $landing_vs_300_400;
    }
    if ($landing_vs_flight>400 && $landing_vs_flight<=500)
    {
        $landingvs = $landing_vs_400_500;
    }
    if ($landing_vs_flight>500 && $landing_vs_flight<=600)
    {
        $landingvs = $landing_vs_500_600;
    }
    if ($landing_vs_flight>600)
    {
        $landingvs = $landing_vs_greater_600;
    }
    $rating = $rating - $landingvs;
    // Update fligth rating
    $query = "update vampireps set rating=$rating where flightid='".$flight."'";
    if (!$result = $db->query($query)) {
        echo json_encode(array(array('error'=>'607', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }
}
function auto_approval ($flight, $db){
        $query = "select * from vampireps where flightid='$flight'";
        if (!$result = $db->query($query)) {
            echo json_encode(array(array('error'=>'608', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        while ($row = $result->fetch_assoc()) {
            $flight = $row["id"];
            $pilot = $row["gvauser_id"];
            $departure = $row["departure"];
            $arrival = $row["arrival"];
        }
    $type = 'vamacars';
    $charter = '';
    require('auto_acept_flight.php');
}

/*
 * REPORTS/EVENTS FUNCTIONS
 */
function insert_tracks($data, $db){
    $tracksInserted=0;
    $dataLenght = count($data["reports"]);

    $flightid='';
    for ($x=0; $x < $dataLenght ;$x++)
    {
        $flight_id = $data["reports"][$x]["flight_id"];
        $track_id = $data["reports"][$x]["track_id"];
        $ias = $data["reports"][$x]["indicated_air_speed"];
        $gs = $data["reports"][$x]["ground_speed"];
        $heading = $data["reports"][$x]["heading"];
        $altitude = $data["reports"][$x]["altitude"];
        $radio_altitude = $data["reports"][$x]["radio_altitude"];
        $fuel = $data["reports"][$x]["fuel"];
        $fuel_used = $data["reports"][$x]["fuel_used"];
        $latitude = $data["reports"][$x]["latitude"];
        $longitude = $data["reports"][$x]["longitude"];
        $time_passed = $data["reports"][$x]["time_passed"];
        $time_flag = $data["reports"][$x]["flag_time"];
        $oat = $data["reports"][$x]["outside_air_temperature"];
        $wind_deg = $data["reports"][$x]["wind_direction"];
        $wind_knots = $data["reports"][$x]["wind_speed"];
        $perc_completed = $data["reports"][$x]["percent_completed"];
        $flight_status = $data["reports"][$x]["flight_status"];
        $plane_type = $data["reports"][$x]["aircraft_type"];
        $pending_nm = $data["reports"][$x]["pending_distance"];

        $sql = "insert into vam_track (
        flight_id,
        track_id,
        ias,
        gs,
        heading,
        radio_altitude,
        altitude,
        fuel,
        fuel_used,
        latitude,
        longitude,
        time_passed,
        time_flag,
        perc_completed,
        oat,
        wind_deg,
        wind_knots,
        flight_status,
        plane_type,
        pending_nm
        )
        values
        (
        '$flight_id',
        '$track_id',
        '$ias',
        '$gs',
        '$heading',
        '$radio_altitude',
        '$altitude',
        '$fuel',
        '$fuel_used',
        '$latitude',
        '$longitude',
        '$time_passed',
        '$time_flag',
        '$perc_completed',
        '$oat',
        '$wind_deg',
        '$wind_knots',
        '$flight_status',
        '$plane_type',
        '$pending_nm'
        )";

        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'609', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        $tracksInserted = $tracksInserted + 1;
    }

    $sql= "DELETE FROM vam_live_acars where flight_id='$flight_id'";
    if (!$result = $db->query($sql)) {
        echo json_encode(array(array('error'=>'610', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }

    $sql= "DELETE FROM vam_live_flights where flight_id='$flight_id'";
    if (!$result = $db->query($sql)) {
        echo json_encode(array(array('error'=>'611', 'message'=>'There was an error running the query [' . $db->error . ']')));
        die();
    }

    return $tracksInserted;
}

function insert_events($data, $db){
    $eventsInserted=0;
    $dataLenght = count($data["events"]);
    include('db_login.php');
        $db = new mysqli($db_host , $db_username , $db_password , $db_database);
        $db->set_charset("utf8");
        if ($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

    for ($x=0; $x < $dataLenght ;$x++)
    {
        $event_id = $data["events"][$x]["event_id"];
        $flight_id = $data["events"][$x]["flight_id"];
        $event_description = $data["events"][$x]["event_description"];
        $event_timestamp = $data["events"][$x]["event_timestamp"];
        $ias = $data["events"][$x]["indicated_air_speed"];
        $altitude = $data["events"][$x]["altitude"];
        $radio_altimeter = $data["events"][$x]["radio_altitude"];
        $fuel = $data["events"][$x]["fuel"];
        $fuel_used = $data["events"][$x]["fuel_used"];
        $log_time = $data["events"][$x]["time_passed"];
        $landing_vs = $data["events"][$x]["landing_vs"];
        $flaps = $data["events"][$x]["flaps"];
        $light_nav = $data["events"][$x]["light_navigation"];
        $light_taxi = $data["events"][$x]["light_taxi"];
        $light_sto = $data["events"][$x]["light_strobes"];
        $light_lnd = $data["events"][$x]["light_landing"];
        $light_bea = $data["events"][$x]["light_beacon"];
        $heading = $data["events"][$x]["heading"];
        $critical = $data["events"][$x]["critical"];
        $sql = "insert into vamevents (event_id,
        flight_id,
        event_description,
        event_timestamp,
        ias,
        altitude,
        radio_altimeter,
        heading,
        fuel,
        fuel_used,
        log_time,
        landing_vs,
        flaps,
        light_nav,
        light_taxi,
        light_sto,
        light_lnd,
        light_bea,
        critical) 
        values (
        '$event_id',
        '$flight_id',
        '$event_description',
        '$event_timestamp',
        '$ias',
        '$altitude',
        '$radio_altimeter',
        '$heading',
        '$fuel',
        '$fuel_used',
        '$log_time',
        '$landing_vs',
        '$flaps',
        '$light_nav',
        '$light_taxi',
        '$light_sto',
        '$light_lnd',
        '$light_bea',
        '$critical')";

        if (!$result = $db->query($sql)) {
            echo json_encode(array(array('error'=>'612', 'message'=>'There was an error running the query [' . $db->error . ']')));
            die();
        }
        $eventsInserted = $eventsInserted + 1;
    }
    return $eventsInserted;
}


?>