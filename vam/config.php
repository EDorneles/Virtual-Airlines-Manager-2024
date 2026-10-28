<?php
/**
 * Shared database settings for the VAM website and ACARS endpoints.
 *
 * Keep credentials in private/vam-db.php or provide VAM_DB_* environment
 * variables in Plesk. Do not commit production credentials to this file.
 */

$vamConfigPath = getenv('VAM_CONFIG_FILE');
if (!$vamConfigPath) {
	$vamConfigPath = dirname(__DIR__) . '/private/vam-db.php';
}

$vamDatabaseConfig = array(
	'host' => getenv('VAM_DB_HOST') ?: '',
	'database' => getenv('VAM_DB_NAME') ?: '',
	'username' => getenv('VAM_DB_USER') ?: '',
	'password' => getenv('VAM_DB_PASSWORD') ?: '',
	'charset' => getenv('VAM_DB_CHARSET') ?: 'utf8mb4',
);

if (is_file($vamConfigPath)) {
	$vamLocalDatabaseConfig = require $vamConfigPath;
	if (!is_array($vamLocalDatabaseConfig)) {
		throw new RuntimeException('O arquivo de configuração do banco deve retornar um array.');
	}
	$vamDatabaseConfig = array_merge($vamDatabaseConfig, $vamLocalDatabaseConfig);
}

foreach (array('host', 'database', 'username', 'password') as $vamRequiredSetting) {
	if (!isset($vamDatabaseConfig[$vamRequiredSetting]) || $vamDatabaseConfig[$vamRequiredSetting] === '') {
		throw new RuntimeException(
			'Banco de dados não configurado. Crie private/vam-db.php a partir do exemplo ou configure as variáveis VAM_DB_*.'
		);
	}
}

$db_host = (string)$vamDatabaseConfig['host'];
$db_database = (string)$vamDatabaseConfig['database'];
$db_username = (string)$vamDatabaseConfig['username'];
$db_password = (string)$vamDatabaseConfig['password'];
$db_charset = isset($vamDatabaseConfig['charset']) ? (string)$vamDatabaseConfig['charset'] : 'utf8mb4';

// PHP 8.1+ enables strict mysqli exceptions by default. Older VAM code checks
// query return values explicitly, so keep those checks working consistently.
if (function_exists('mysqli_report')) {
	mysqli_report(MYSQLI_REPORT_OFF);
}
