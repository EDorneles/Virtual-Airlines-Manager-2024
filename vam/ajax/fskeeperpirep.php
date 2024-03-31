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
	if (isset ($_GET['lang']))
	{
		$_SESSION['language'] = $_GET['lang'];
	}
	if (!isset($_GET['lang']) && $_SESSION['language'] == '') {
		$_SESSION['language'] = "en";
	}
	if (isset($_GET['lang'])) {
		$_SESSION['language'] = $_GET['lang'];
	} elseif (!isset($_SESSION['language'])) {
		$_SESSION['languages'] = "en";
	}
	include("../languages/lang_" . $_SESSION['language'] . ".php");
	include('../db_login.php');
	include('../classes/class_vam_mailer.php');
	include('../classes/security.php');
	$db = new mysqli($db_host , $db_username , $db_password , $db_database);
	$db->set_charset("utf8");
	if ($db->connect_errno > 0) {
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	//Fetching Values from URL
	$action=$_POST['action'];
	$comment=$_POST['comment'];
	$flightid=$_POST['flightid'];
	$comment = mysqli_real_escape_string($db, $comment);
	// Payment for the flight
	$type = 'keeper';
	$pilot = '';
	$departure = '';
	$arrival = '';
	$charter = '';
	$sql = "select * from pirepfsfk where pirepfsfk_id='$flightid'";
	if (!$result = $db->query($sql)) {
		die('There was an error running the query [' . $db->error . ']');
	}
	while ($row = $result->fetch_assoc()) {
		$pilot = $row["gvauser_id"];
		$duration = $row["FlightTime"];
		$departure = substr($row["OriginAirport"] , 0 , 4);
		$arrival = substr($row["DestinationAirport"] , 0 , 4);
		$charter = $row["charter"];
		$paid = $row["paid"];
	}
	if ($action=="deleteflight")
	{
		$sql = "delete from  pirepfsfk  where pirepfsfk_id='$flightid'";
		if (!$result = $db->query($sql)) {
			die('There was an error running the query [' . $db->error . ']');
		}
	}
	if ($action=="addcomment")
	{
		$sql = "update pirepfsfk set updated_at=NOW(), validator_comments='$comment' where pirepfsfk_id='$flightid'";
		if (!$result = $db->query($sql)) {
			die('There was an error running the query [' . $db->error . ']');
		}
	}
	if ($action=="acceptflight")
	{
		if ($paid!=1)
		{
			// get the salary per hour for the pilot's rank
			$sql = "select * from va_parameters ";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$charter_reduction = $row["charter_reduction"] / 100;
			}
			// get the salary per hour for the pilot's rank
			$sql = "select * from ranks r , gvausers g where g.gvauser_id=$pilot and g.rank_id=r.rank_id ";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			while ($row = $result->fetch_assoc()) {
				$salary_hour = $row["salary_hour"];
			}
			$fligth_type = VALIDATE_FLIGHT_REGULAR;
			$reduction = 0; // assumes it is regular
			if ($charter == 1) {
				$fligth_type = VALIDATE_FLIGHT_CHARTER;
				$reduction = $charter_reduction;
			}
			$quantity = ($duration * $salary_hour) - ($duration * $salary_hour * $reduction);
			$sql = "insert into bank (gvauser_id,date,pirep,quantity,jump) values ($pilot,now(),$flightid,$quantity,'$fligth_type:$departure - $arrival')";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
			// insert pilot salary in Va finance module
			$sql = "insert into va_finances (amount,parameter_id,finance_date,gvauser_id,description,report_type,report_id) values ($quantity, '99995',now(),$pilot ,'$fligth_type:$departure - $arrival','FS KEEPER', '$flightid')";
			if (!$result = $db->query($sql)) {
				die('There was an error running the query [' . $db->error . ']');
			}
		}
		$sql = "update pirepfsfk set updated_at=NOW(), validator_comments='$comment', validated=1, paid=1 where pirepfsfk_id='$flightid'";
		if (!$result = $db->query($sql)) {
			die('There was an error running the query [' . $db->error . ']');
		}
	}
	if ($action=="rejectflight")
	{
		$sql = "update pirepfsfk set updated_at=NOW(), validator_comments='$comment', validated=2  where pirepfsfk_id='$flightid'";
		if (!$result = $db->query($sql)) {
			die('There was an error running the query [' . $db->error . ']');
		}
	}
	// Send mail to the pilot
	$mail = new vam_mailer();
	$mail->mail_vampirep_compose($pilot,$action,$departure,$arrival,$comment);
	$db->close();
?>
