<?php
// ===== CARGAR .ENV =====
$envPath = dirname(__DIR__) . '/.env';
$env = file_exists($envPath) ? parse_ini_file($envPath) : [];

function envv($key, $default = null) {
    global $env;
    return $env[$key] ?? $default;
}

// ===== DATOS DESDE .ENV =====
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

$PRIVATE_STORAGE = dirname(__DIR__) . "/storage/private";
$MAIL_DEBUG_PATH = dirname(__DIR__) . "/storage/mail_outbox";

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