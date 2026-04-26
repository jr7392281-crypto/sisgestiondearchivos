<?php
include('../../config.php');
include('../../../layout/sesion.php');
require_once('../../helpers/verificacion_email.php');
proteger_admin();

$nombres = trim((string) ($_POST['nombre'] ?? ''));
$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$rol = isset($_POST['rol']) ? (int) $_POST['rol'] : 0;
$password_user = isset($_POST['password_user']) ? $_POST['password_user'] : '';
$password_repeat = isset($_POST['password_repeat']) ? $_POST['password_repeat'] : '';

if ($nombres === '' || $email === '' || $rol <= 0) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Completa todos los campos obligatorios.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/create.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Debes ingresar un email valido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/create.php');
    exit();
}

$existe_email = $pdo->prepare("SELECT id_usuario FROM tb_users WHERE email = :email LIMIT 1");
$existe_email->bindParam(':email', $email, PDO::PARAM_STR);
$existe_email->execute();
if ($existe_email->fetch(PDO::FETCH_ASSOC)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Ese email ya esta registrado.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/create.php');
    exit();
}

if (strlen($password_user) < 6) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "La contrasena debe tener al menos 6 caracteres";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/create.php');
    exit();
}

if ($password_user != $password_repeat) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Error las contrasenas no son iguales";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/create.php');
    exit();
}

$password_hash = password_hash($password_user, PASSWORD_DEFAULT);

$sentencia = $pdo->prepare("INSERT INTO tb_users
    (nombre, email, id_rol, password_user, email_verificado, email_verificado_at, created_at, updated_at)
    VALUES (:nombre, :email, :id_rol, :password_user, :email_verificado, :email_verificado_at, :created_at, :updated_at)");

$sentencia->bindParam('nombre', $nombres);
$sentencia->bindParam('email', $email);
$sentencia->bindParam('id_rol', $rol);
$sentencia->bindParam('password_user', $password_hash);
$email_verificado = 0;
$email_verificado_at = null;
$sentencia->bindParam('email_verificado', $email_verificado, PDO::PARAM_INT);
$sentencia->bindParam('email_verificado_at', $email_verificado_at, PDO::PARAM_NULL);
$sentencia->bindParam('created_at', $fechaHora);
$sentencia->bindParam('updated_at', $fechaHora);

if ($sentencia->execute()) {
    $id_usuario_nuevo = (int) $pdo->lastInsertId();
    $error_envio = '';
    $ruta_debug = '';
    $correo_enviado = enviar_verificacion_email($pdo, $id_usuario_nuevo, $email, $URL, $fechaHora, $MAIL_DEBUG_PATH, $error_envio, $ruta_debug);

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if ($correo_enviado) {
        $_SESSION['mensaje'] = "Se registro al usuario y se envio el correo de verificacion.";
        $_SESSION['icono'] = "success";
    } else {
        $_SESSION['mensaje'] = "Se registro al usuario, pero no se pudo enviar el correo de verificacion.";
        $_SESSION['icono'] = "warning";
        error_log('No se pudo enviar verificacion a ' . $email . ': ' . $error_envio);
    }

    if ($ruta_debug !== '') {
        $_SESSION['mensaje'] .= " Revisa storage/mail_outbox si estas en modo local.";
    }
    header('Location:' . $URL . '/usuarios/');
    exit();
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$_SESSION['mensaje'] = "Error al registrar el usuario";
$_SESSION['icono'] = "error";
header('Location:' . $URL . '/usuarios/create.php');
exit();
?>
