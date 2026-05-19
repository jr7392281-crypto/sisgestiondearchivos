<?php
$projectRoot = dirname(__DIR__);
$envPath = __DIR__ . '/.env';
$env = file_exists($envPath) ? parse_ini_file($envPath, false, INI_SCANNER_RAW) : [];

if (!is_array($env)) {
    $env = [];
}

function envv($key, $default = null)
{
    global $env;
    return $env[$key] ?? $default;
}

define('SERVIDOR', envv('DB_HOST', 'localhost'));
define('USUARIO', envv('DB_USER', 'root'));
define('PASSWORD', envv('DB_PASS', ''));
define('BD', envv('DB_NAME', 'sisgestiondearchivos'));

$servidor = "mysql:dbname=" . BD . ";host=" . SERVIDOR;

try {
    $pdo = new PDO($servidor, USUARIO, PASSWORD);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos");
}

// ===== URL DESDE .ENV =====
$URL = envv('APP_URL', "http://localhost/sistemagestion");

$PRIVATE_STORAGE = envv('PRIVATE_STORAGE_PATH', $projectRoot . "/storage/private");
$MAIL_DEBUG_PATH = envv('MAIL_DEBUG_PATH', $projectRoot . "/storage/mail_outbox");

// ===== SMTP DESDE .ENV =====
define('SMTP_HOST', envv('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', envv('SMTP_PORT', 465));
define('SMTP_USER', envv('SMTP_USER'));
define('SMTP_PASS', envv('SMTP_PASS'));
define('SMTP_FROM_EMAIL', envv('SMTP_FROM_EMAIL'));
define('SMTP_FROM_NAME', envv('SMTP_FROM_NAME', 'Sistema de Gestion'));

date_default_timezone_set("America/Lima");
$fechaHora = date('Y-m-d H:i:s');

if (!function_exists('e')) {
    function e($valor)
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}
