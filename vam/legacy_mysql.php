<?php
/**
 * Small compatibility layer for the bundled legacy FSACARS endpoints.
 * New code should use mysqli directly. Connection arguments from old scripts
 * are intentionally ignored; credentials always come from the shared config.
 */
require_once __DIR__ . '/db_login.php';

if (!defined('MYSQL_NUM') && defined('MYSQLI_NUM')) {
	define('MYSQL_NUM', MYSQLI_NUM);
}

if (!function_exists('mysql_connect')) {
	function mysql_connect($server = null, $username = null, $password = null, $newLink = false, $clientFlags = 0) {
		global $db_host, $db_username, $db_password, $db_database, $db_charset;
		if (!empty($GLOBALS['vamLegacyMysqlConnection']) && $GLOBALS['vamLegacyMysqlConnection'] instanceof mysqli) {
			return $GLOBALS['vamLegacyMysqlConnection'];
		}

		$connection = new mysqli($db_host, $db_username, $db_password, $db_database);
		if ($connection->connect_errno) {
			return false;
		}
		if (!empty($db_charset)) {
			$connection->set_charset($db_charset);
		}
		$GLOBALS['vamLegacyMysqlConnection'] = $connection;
		return $connection;
	}

	function mysql_select_db($database, $connection = null) {
		return !empty($GLOBALS['vamLegacyMysqlConnection']);
	}

	function mysql_query($query, $connection = null) {
		$link = ($connection instanceof mysqli) ? $connection : (isset($GLOBALS['vamLegacyMysqlConnection']) ? $GLOBALS['vamLegacyMysqlConnection'] : null);
		return $link ? $link->query($query) : false;
	}

	function mysql_fetch_array($result, $resultType = MYSQLI_BOTH) {
		return ($result instanceof mysqli_result) ? $result->fetch_array($resultType) : false;
	}

	function mysql_fetch_object($result, $className = 'stdClass', $constructorArgs = array()) {
		return ($result instanceof mysqli_result) ? $result->fetch_object($className, $constructorArgs) : false;
	}

	function mysql_num_rows($result) {
		return ($result instanceof mysqli_result) ? $result->num_rows : 0;
	}

	function mysql_numrows($result) {
		return mysql_num_rows($result);
	}

	function mysql_result($result, $row, $field = 0) {
		if (!($result instanceof mysqli_result) || !$result->data_seek((int)$row)) {
			return false;
		}
		$data = $result->fetch_array(MYSQLI_BOTH);
		if (!is_array($data)) {
			return false;
		}
		return array_key_exists($field, $data) ? $data[$field] : false;
	}

	function mysql_affected_rows($connection = null) {
		$link = ($connection instanceof mysqli) ? $connection : (isset($GLOBALS['vamLegacyMysqlConnection']) ? $GLOBALS['vamLegacyMysqlConnection'] : null);
		return $link ? $link->affected_rows : -1;
	}

	function mysql_free_result($result) {
		if ($result instanceof mysqli_result) {
			$result->free();
			return true;
		}
		return false;
	}

	function mysql_error($connection = null) {
		$link = ($connection instanceof mysqli) ? $connection : (isset($GLOBALS['vamLegacyMysqlConnection']) ? $GLOBALS['vamLegacyMysqlConnection'] : null);
		return $link ? $link->error : '';
	}

	function mysql_close($connection = null) {
		$link = ($connection instanceof mysqli) ? $connection : (isset($GLOBALS['vamLegacyMysqlConnection']) ? $GLOBALS['vamLegacyMysqlConnection'] : null);
		if (!$link) {
			return false;
		}
		$link->close();
		unset($GLOBALS['vamLegacyMysqlConnection']);
		return true;
	}
}

if (!function_exists('split')) {
	function split($pattern, $string, $limit = -1) {
		$limit = ((int)$limit === 0) ? -1 : (int)$limit;
		$pattern = str_replace('~', '\\~', (string)$pattern);
		return preg_split('~' . $pattern . '~', (string)$string, $limit);
	}
}
