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

$vamDatabaseSetupMessage = null;
foreach (array('host', 'database', 'username', 'password') as $vamRequiredSetting) {
	$vamSettingValue = isset($vamDatabaseConfig[$vamRequiredSetting]) ? (string)$vamDatabaseConfig[$vamRequiredSetting] : '';
	if ($vamSettingValue === '' || preg_match('/^(YOUR_PLESK_|CHANGE_THIS_)/', $vamSettingValue)) {
		$vamDatabaseSetupMessage = 'Banco de dados não configurado. Crie private/vam-db.php a partir do exemplo, preencha as credenciais do Plesk e importe os scripts descritos em database/README.md.';
		break;
	}
}

if ($vamDatabaseSetupMessage !== null) {
	if (PHP_SAPI !== 'cli') {
		http_response_code(503);
		header('Content-Type: text/html; charset=utf-8');
		echo '<!doctype html><html lang="pt-BR"><meta charset="utf-8"><title>Configuração necessária</title><h1>Banco de dados ainda não configurado</h1><p>' . htmlspecialchars($vamDatabaseSetupMessage, ENT_QUOTES, 'UTF-8') . '</p></html>';
		exit;
	}
	throw new RuntimeException($vamDatabaseSetupMessage);
}

$vamMissingExtensions = array();
foreach (array('mysqli', 'pdo_mysql') as $vamRequiredExtension) {
	if (!extension_loaded($vamRequiredExtension)) {
		$vamMissingExtensions[] = $vamRequiredExtension;
	}
}
if ($vamMissingExtensions) {
	$vamExtensionSetupMessage = 'Ative as extensões PHP necessárias no Plesk: ' . implode(', ', $vamMissingExtensions) . '.';
	if (PHP_SAPI !== 'cli') {
		http_response_code(503);
		header('Content-Type: text/html; charset=utf-8');
		echo '<!doctype html><html lang="pt-BR"><meta charset="utf-8"><title>Configuração necessária</title><h1>Extensão PHP necessária</h1><p>' . htmlspecialchars($vamExtensionSetupMessage, ENT_QUOTES, 'UTF-8') . '</p></html>';
		exit;
	}
	throw new RuntimeException($vamExtensionSetupMessage);
}

$db_host = trim((string)$vamDatabaseConfig['host']);
// PHP's MySQL drivers interpret "localhost" as a Unix socket connection.
// Plesk PHP-FPM may not have the same socket path as MariaDB; use TCP on the
// local interface for HelioHost's default local MariaDB server instead.
if (preg_match('/^localhost(?::3306)?$/i', $db_host)) {
	$db_host = '127.0.0.1';
}
$db_database = (string)$vamDatabaseConfig['database'];
$db_username = (string)$vamDatabaseConfig['username'];
$db_password = (string)$vamDatabaseConfig['password'];
$db_charset = isset($vamDatabaseConfig['charset']) ? (string)$vamDatabaseConfig['charset'] : 'utf8mb4';

// PHP 8.1+ enables strict mysqli exceptions by default. Older VAM code checks
// query return values explicitly, so keep those checks working consistently.
if (function_exists('mysqli_report')) {
	mysqli_report(MYSQLI_REPORT_OFF);
}
