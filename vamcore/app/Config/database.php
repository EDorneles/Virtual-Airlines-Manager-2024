<?php
/**
 * CakePHP 2 database settings. Values are loaded from the same private
 * configuration used by the public VAM endpoints.
 */
require_once dirname(__DIR__, 3) . '/vam/config.php';

class DATABASE_CONFIG {
	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => '',
		'login' => '',
		'password' => '',
		'database' => '',
		'prefix' => '',
		'encoding' => 'utf8mb4',
	);

	public function __construct() {
		$this->default['host'] = isset($GLOBALS['db_host']) ? $GLOBALS['db_host'] : '';
		$this->default['database'] = isset($GLOBALS['db_database']) ? $GLOBALS['db_database'] : '';
		$this->default['login'] = isset($GLOBALS['db_username']) ? $GLOBALS['db_username'] : '';
		$this->default['password'] = isset($GLOBALS['db_password']) ? $GLOBALS['db_password'] : '';
		$this->default['encoding'] = isset($GLOBALS['db_charset']) ? $GLOBALS['db_charset'] : 'utf8mb4';
	}
}
