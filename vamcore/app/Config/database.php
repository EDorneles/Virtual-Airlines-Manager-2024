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
		global $db_host, $db_database, $db_username, $db_password, $db_charset;
		$this->default['host'] = $db_host;
		$this->default['database'] = $db_database;
		$this->default['login'] = $db_username;
		$this->default['password'] = $db_password;
		$this->default['encoding'] = $db_charset;
	}
}
