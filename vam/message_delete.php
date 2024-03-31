<?php
	/**
	 * @Project: Virtual Airlines Manager (VAM)
	 * @Author: Alejandro Garcia
	 * @Web http://virtualairlinesmanager.net
	 * Copyright (c) 2013 - 2016 Alejandro Garcia
	 * VAM is licenced under the following license:
	 *   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
	 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
	 */
?>
<?php
	require('check_login.php');
	$id=$_SESSION["id"];
	if ($id !='')
	{
		$message_id = $_GET['mail'];
		$db = new mysqli($db_host , $db_username , $db_password , $db_database);
		$db->set_charset("utf8");
		if ($db->connect_errno > 0) {
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$sql = "UPDATE messages set status=3 , deletedate=now() where message_id=$message_id";
		if (!$result = $db->query($sql)) {
			die('There was an error running the query [' . $db->error . ']');
		}
		$db->close();
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./index_vam_op.php?page=mails">';
	}
?>
