<?php
include('../../config.php');
require_once('../../helpers/verificacion_email.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location:' . $URL . '/login');
    exit();
}

$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
$password_user = isset($_POST['password_user']) ? $_POST['password_user'] : '';

if ($email === '' || $password_user === '') {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Error Datos Incorrectos";
    $_SESSION['icono'] = "error";
    header('location:' . $URL . '/login');
    exit();
}

$sql = "SELECT * FROM tb_users WHERE email = :email LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

$credenciales_validas = false;
if ($usuario) {
    $credenciales_validas = password_verify($password_user, (string) $usuario['password_user']);
}

if (!$credenciales_validas) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Error Datos Incorrectos";
    $_SESSION['icono'] = "error";
    header('location:' . $URL . '/login');
    exit();
}

$id_usuario = (int) $usuario['id_usuario'];
$email = (string) $usuario['email'];
$id_rol = (int) $usuario['id_rol'];
$email_verificado = isset($usuario['email_verificado']) ? (int) $usuario['email_verificado'] : 0;

if ($email_verificado !== 1) {
    $error_envio = '';
    $ruta_debug = '';
    $correo_enviado = enviar_verificacion_email($pdo, $id_usuario, $email, $URL, $fechaHora, $MAIL_DEBUG_PATH, $error_envio, $ruta_debug);

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if ($correo_enviado) {
        $_SESSION['mensaje'] = "Tu correo aun no esta verificado. Te enviamos un nuevo enlace de verificacion.";
        $_SESSION['icono'] = "warning";
    } else {
        $_SESSION['mensaje'] = "Tu correo aun no esta verificado y no se pudo reenviar el enlace.";
        $_SESSION['icono'] = "error";
        error_log('No se pudo reenviar verificacion a ' . $email . ': ' . $error_envio);
    }

    if ($ruta_debug !== '') {
        $_SESSION['mensaje'] .= " Revisa storage/mail_outbox si estas en modo local.";
    }

    header('location:' . $URL . '/login');
    exit();
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
session_regenerate_id(true);
$_SESSION['sesion_email'] = $email;
$_SESSION['id_rol'] = $id_rol;

$sentencia = $pdo->prepare("SELECT nombre_permiso FROM tb_permision WHERE id_rol = :id_rol");
$sentencia->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
$sentencia->execute();
$permisos = $sentencia->fetchAll(PDO::FETCH_COLUMN);
$_SESSION['permisos'] = $permisos;

header('location:' . $URL . '/index.php');
exit();
?>
