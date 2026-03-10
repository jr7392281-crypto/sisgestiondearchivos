<?php
include('../../config.php');
include('../../../layout/sesion.php');
proteger_admin();

$nombres = $_POST['nombre'];
$email = $_POST['email'];
$password_user = $_POST['password_user'];
$password_repeat = $_POST['password_repeat'];
$id_usuario = $_POST['id_usuario'];
$rol = $_POST['rol'];

if ($password_user === '' && $password_repeat === '') {
    $sentencia = $pdo->prepare("UPDATE tb_users
        SET nombre = :nombre,
            email = :email,
            id_rol = :id_rol,
            updated_at = :updated_at
        WHERE id_usuario = :id_usuario");

    $sentencia->bindParam('nombre', $nombres);
    $sentencia->bindParam('email', $email);
    $sentencia->bindParam('id_rol', $rol);
    $sentencia->bindParam('updated_at', $fechaHora);
    $sentencia->bindParam('id_usuario', $id_usuario);

    if ($sentencia->execute()) {
        session_start();
        $_SESSION['mensaje'] = "Se actualizo al usuario de forma correcta";
        $_SESSION['icono'] = "success";
        header('Location:' . $URL . '/usuarios/');
        exit();
    }

    session_start();
    $_SESSION['mensaje'] = "No se pudo actualizar el usuario";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    exit();
}

if (strlen($password_user) < 6) {
    session_start();
    $_SESSION['mensaje'] = "La contrasena debe tener al menos 6 caracteres";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    exit();
}

if ($password_user != $password_repeat) {
    session_start();
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
        updated_at = :updated_at
    WHERE id_usuario = :id_usuario");

$sentencia->bindParam('nombre', $nombres);
$sentencia->bindParam('email', $email);
$sentencia->bindParam('id_rol', $rol);
$sentencia->bindParam('password_user', $password_hash);
$sentencia->bindParam('updated_at', $fechaHora);
$sentencia->bindParam('id_usuario', $id_usuario);

if ($sentencia->execute()) {
    session_start();
    $_SESSION['mensaje'] = "Se actualizo al usuario de forma correcta";
    $_SESSION['icono'] = "success";
    header('Location:' . $URL . '/usuarios/');
    exit();
}

session_start();
$_SESSION['mensaje'] = "No se pudo actualizar el usuario";
$_SESSION['icono'] = "error";
header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
exit();
?>
