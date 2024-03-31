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
	$db = new mysqli($db_host , $db_username , $db_password , $db_database);
	$db->set_charset("utf8");
	if ($db->connect_errno > 0) {
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$sql = "select language_name, file_sufix from languages order by language_name asc";
	if (!$result = $db->query($sql)) {
		die('There was an error running the query [' . $db->error . ']');
	}
	$linklanguage = '';
	$combolanguage = '';
	while ($row = $result->fetch_assoc()) {
		$combolanguage .= " <option value='" . $row['file_sufix'] . "'>" . $row['language_name'] . "</option>";
		$linklanguage .= "<li><a href=index.php?lang=" . $row['file_sufix'] . ">" . $row['language_name'] . "</a></li>";
	}
?>