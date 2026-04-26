<?php
include('../../config.php');
require_once('../../helpers/email.php');
session_start();

// Este controlador solo debe recibir datos por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

// Recibimos el email del formulario
$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';

// Validamos que no llegue vacio
if ($email === '') {
    $_SESSION['mensaje'] = "Debes ingresar un email.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensaje'] = "Debes ingresar un email valido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

// Buscamos al usuario por email
$sql_usuario = "SELECT id_usuario FROM tb_users WHERE email = :email LIMIT 1";
$query_usuario = $pdo->prepare($sql_usuario);
$query_usuario->bindParam(':email', $email, PDO::PARAM_STR);
$query_usuario->execute();
$usuario = $query_usuario->fetch(PDO::FETCH_ASSOC);

// Si no existe, mostramos mensaje general para no revelar datos del sistema
if (!$usuario) {
    $_SESSION['mensaje'] = "Si el email existe, recibira un enlace de recuperacion.";
    $_SESSION['icono'] = "success";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

// Si existe, creamos un token nuevo
$id_usuario = (int) $usuario['id_usuario'];
$token = bin2hex(random_bytes(32));

// Guardamos el token en BD (reemplazando tokens anteriores de ese usuario)
$pdo->beginTransaction();
try {
    $delete = $pdo->prepare("DELETE FROM tb_password_reset WHERE id_usuario = :id_usuario");
    $delete->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $delete->execute();

    $insert = $pdo->prepare("INSERT INTO tb_password_reset (id_usuario, token, created_at) VALUES (:id_usuario, :token, :created_at)");
    $insert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $insert->bindParam(':token', $token, PDO::PARAM_STR);
    $insert->bindParam(':created_at', $fechaHora, PDO::PARAM_STR);
    $insert->execute();

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['mensaje'] = "No se pudo generar el enlace. Intenta nuevamente.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

$link = $URL . '/login/reset_password.php?token=' . urlencode($token);
$link_seguro = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
$asunto = 'Recuperar contrasena';
$cuerpo = '<p>Hola.</p>'
    . '<p>Recibimos una solicitud para restablecer la contrasena de tu cuenta.</p>'
    . '<p><a href="' . $link_seguro . '">Haz clic aqui para restablecer tu contrasena</a></p>'
    . '<p>Si no solicitaste este cambio, puedes ignorar este correo.</p>'
    . '<p>Este enlace vence en 30 minutos.</p>';

$error_envio = '';
$ruta_debug = '';
$enviado = enviar_correo_o_guardar_debug($email, $asunto, $cuerpo, $MAIL_DEBUG_PATH, $error_envio, $ruta_debug);

if (!$enviado) {
    error_log('No se pudo enviar ni guardar el correo de recuperacion a ' . $email . ': ' . $error_envio);
}

$_SESSION['mensaje'] = "Si el email existe, recibira un enlace de recuperacion.";
$_SESSION['icono'] = "success";
header('Location:' . $URL . '/login/forgot_password.php');
exit();
?>
