<?php
include('../../config.php');
include('../../../layout/sesion.php');
require_once('../../helpers/verificacion_email.php');
proteger_admin();

$nombres = trim((string) ($_POST['nombre'] ?? ''));
$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$password_user = isset($_POST['password_user']) ? $_POST['password_user'] : '';
$password_repeat = isset($_POST['password_repeat']) ? $_POST['password_repeat'] : '';
$id_usuario = isset($_POST['id_usuario']) ? (int) $_POST['id_usuario'] : 0;
$rol = isset($_POST['rol']) ? (int) $_POST['rol'] : 0;

if ($nombres === '' || $email === '' || $id_usuario <= 0 || $rol <= 0) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Completa todos los campos obligatorios.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Debes ingresar un email valido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    exit();
}

$usuario_actual_query = $pdo->prepare("SELECT email, email_verificado, email_verificado_at FROM tb_users WHERE id_usuario = :id_usuario LIMIT 1");
$usuario_actual_query->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$usuario_actual_query->execute();
$usuario_actual = $usuario_actual_query->fetch(PDO::FETCH_ASSOC);

if (!$usuario_actual) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "No se encontro el usuario a actualizar.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/');
    exit();
}

$email_anterior = strtolower((string) $usuario_actual['email']);
$email_cambio = $email !== $email_anterior;

$existe_email = $pdo->prepare("SELECT id_usuario FROM tb_users WHERE email = :email AND id_usuario <> :id_usuario LIMIT 1");
$existe_email->bindParam(':email', $email, PDO::PARAM_STR);
$existe_email->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$existe_email->execute();
if ($existe_email->fetch(PDO::FETCH_ASSOC)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Ese email ya esta registrado por otro usuario.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    exit();
}

if ($password_user === '' && $password_repeat === '') {
    $sentencia = $pdo->prepare("UPDATE tb_users
        SET nombre = :nombre,
            email = :email,
            id_rol = :id_rol,
            email_verificado = :email_verificado,
            email_verificado_at = :email_verificado_at,
            updated_at = :updated_at
        WHERE id_usuario = :id_usuario");

    $email_verificado = $email_cambio ? 0 : (int) $usuario_actual['email_verificado'];
    $email_verificado_at = $email_cambio ? null : $usuario_actual['email_verificado_at'];
    $tipo_email_verificado_at = is_null($email_verificado_at) ? PDO::PARAM_NULL : PDO::PARAM_STR;
    $sentencia->bindParam('nombre', $nombres);
    $sentencia->bindParam('email', $email);
    $sentencia->bindParam('id_rol', $rol);
    $sentencia->bindParam('email_verificado', $email_verificado, PDO::PARAM_INT);
    $sentencia->bindParam('email_verificado_at', $email_verificado_at, $tipo_email_verificado_at);
    $sentencia->bindParam('updated_at', $fechaHora);
    $sentencia->bindParam('id_usuario', $id_usuario);

    if ($sentencia->execute()) {
        if ($email_cambio) {
            $error_envio = '';
            $ruta_debug = '';
            $correo_enviado = enviar_verificacion_email($pdo, $id_usuario, $email, $URL, $fechaHora, $MAIL_DEBUG_PATH, $error_envio, $ruta_debug);
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($email_cambio) {
            if (!empty($correo_enviado)) {
                $_SESSION['mensaje'] = "Usuario actualizado. Se envio un nuevo correo de verificacion.";
                $_SESSION['icono'] = "success";
            } else {
                $_SESSION['mensaje'] = "Usuario actualizado, pero no se pudo enviar el nuevo correo de verificacion.";
                $_SESSION['icono'] = "warning";
                error_log('No se pudo reenviar verificacion a ' . $email . ': ' . $error_envio);
            }
            if (!empty($ruta_debug)) {
                $_SESSION['mensaje'] .= " Revisa storage/mail_outbox si estas en modo local.";
            }
        } else {
            $_SESSION['mensaje'] = "Se actualizo al usuario de forma correcta";
            $_SESSION['icono'] = "success";
        }

        header('Location:' . $URL . '/usuarios/');
        exit();
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "No se pudo actualizar el usuario";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    exit();
}

if (strlen($password_user) < 6) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "La contrasena debe tener al menos 6 caracteres";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    exit();
}

if ($password_user != $password_repeat) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Error las contrasenas no son iguales";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    exit();
}

$password_hash = password_hash($password_user, PASSWORD_DEFAULT);

$sentencia = $pdo->prepare("UPDATE tb_users
    SET nombre = :nombre,
        email = :email,
        id_rol = :id_rol,
        password_user = :password_user,
        email_verificado = :email_verificado,
        email_verificado_at = :email_verificado_at,
        updated_at = :updated_at
    WHERE id_usuario = :id_usuario");

$email_verificado = $email_cambio ? 0 : (int) $usuario_actual['email_verificado'];
$email_verificado_at = $email_cambio ? null : $usuario_actual['email_verificado_at'];
$tipo_email_verificado_at = is_null($email_verificado_at) ? PDO::PARAM_NULL : PDO::PARAM_STR;
$sentencia->bindParam('nombre', $nombres);
$sentencia->bindParam('email', $email);
$sentencia->bindParam('id_rol', $rol);
$sentencia->bindParam('password_user', $password_hash);
$sentencia->bindParam('email_verificado', $email_verificado, PDO::PARAM_INT);
$sentencia->bindParam('email_verificado_at', $email_verificado_at, $tipo_email_verificado_at);
$sentencia->bindParam('updated_at', $fechaHora);
$sentencia->bindParam('id_usuario', $id_usuario);

if ($sentencia->execute()) {
    if ($email_cambio) {
        $error_envio = '';
        $ruta_debug = '';
        $correo_enviado = enviar_verificacion_email($pdo, $id_usuario, $email, $URL, $fechaHora, $MAIL_DEBUG_PATH, $error_envio, $ruta_debug);
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if ($email_cambio) {
        if (!empty($correo_enviado)) {
            $_SESSION['mensaje'] = "Usuario actualizado. Se envio un nuevo correo de verificacion.";
            $_SESSION['icono'] = "success";
        } else {
            $_SESSION['mensaje'] = "Usuario actualizado, pero no se pudo enviar el nuevo correo de verificacion.";
            $_SESSION['icono'] = "warning";
            error_log('No se pudo reenviar verificacion a ' . $email . ': ' . $error_envio);
        }
        if (!empty($ruta_debug)) {
            $_SESSION['mensaje'] .= " Revisa storage/mail_outbox si estas en modo local.";
        }
    } else {
        $_SESSION['mensaje'] = "Se actualizo al usuario de forma correcta";
        $_SESSION['icono'] = "success";
    }

    header('Location:' . $URL . '/usuarios/');
    exit();
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$_SESSION['mensaje'] = "No se pudo actualizar el usuario";
$_SESSION['icono'] = "error";
header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
exit();
?>
