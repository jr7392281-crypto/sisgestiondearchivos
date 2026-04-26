<?php
define('SERVIDOR', 'localhost');
define('USUARIO', 'root');
define('PASSWORD', '');
define('BD', 'sisgestiondearchivos');

$servidor = "mysql:dbname=" . BD . ";host=" . SERVIDOR;

try {
    $pdo = new PDO($servidor, USUARIO, PASSWORD);
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}

$URL = "http://localhost/sistemagestion";
$PRIVATE_STORAGE = dirname(__DIR__) . "/storage/private";
$MAIL_DEBUG_PATH = dirname(__DIR__) . "/storage/mail_outbox";

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('SMTP_USER', 'jr7392281@gmail.com');
define('SMTP_PASS', 'qvofgxorxacnqrnm');
define('SMTP_FROM_EMAIL', 'jr7392281@gmail.com');
define('SMTP_FROM_NAME', 'Sistema de Gestion');


date_default_timezone_set("America/Lima");
$fechaHora = date('Y-m-d H:i:s');

if (!function_exists('e')) {
    function e($valor)
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

?>
