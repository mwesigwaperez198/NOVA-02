<?php
$app_env = getenv('APP_ENV') ?: 'production';

if ($app_env === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Africa/Kampala');

$dbhost = getenv('DB_HOST') ?: 'localhost';
$dbport = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'ecommerceweb';
$dbuser = getenv('DB_USER') ?: 'root';
$dbpass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

if (!defined('BASE_URL')) {
    $env_base_url = getenv('BASE_URL');
    if (!empty($env_base_url)) {
        define("BASE_URL", rtrim($env_base_url, '/') . '/');
    } else {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $script_dir = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
        $script_dir = str_replace('\\', '/', $script_dir);
        if (strpos($script_dir, '/admin') !== false) {
            $script_dir = substr($script_dir, 0, strpos($script_dir, '/admin'));
        }
        $script_dir = rtrim($script_dir, '/');
        define("BASE_URL", $protocol . $host . ($script_dir ? $script_dir . '/' : '/'));
    }
}

if (!defined('ADMIN_URL')) {
    define("ADMIN_URL", BASE_URL . "admin/");
}

try {
    $pdo = new PDO("mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset=utf8mb4", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    error_log("Database connection failure: " . $exception->getMessage());
    if ($app_env === 'development') {
        die("Database connection error: " . htmlspecialchars($exception->getMessage()));
    } else {
        die("We are currently experiencing technical difficulties. Please try again later.");
    }
}
