<?php
include('../../config.php');
include('../../../layout/sesion.php');
proteger_admin();

$nombres = $_POST['nombre'];
$email = $_POST['email'];
$rol = $_POST['rol'];
$password_user = $_POST['password_user'];
$password_repeat = $_POST['password_repeat'];

if (strlen($password_user) < 6) {
    session_start();
    $_SESSION['mensaje'] = "La contrasena debe tener al menos 6 caracteres";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/create.php');
    exit();
}

if ($password_user != $password_repeat) {
    session_start();
    $_SESSION['mensaje'] = "Error las contrasenas no son iguales";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/usuarios/create.php');
    exit();
}

$password_hash = password_hash($password_user, PASSWORD_DEFAULT);

$sentencia = $pdo->prepare("INSERT INTO tb_users
    (nombre, email, id_rol, password_user, created_at)
    VALUES (:nombre, :email, :id_rol, :password_user, :created_at)");

$sentencia->bindParam('nombre', $nombres);
$sentencia->bindParam('email', $email);
$sentencia->bindParam('id_rol', $rol);
$sentencia->bindParam('password_user', $password_hash);
$sentencia->bindParam('created_at', $fechaHora);

if ($sentencia->execute()) {
    session_start();
    $_SESSION['mensaje'] = "Se registro al usuario de forma correcta";
    $_SESSION['icono'] = "success";
    header('Location:' . $URL . '/usuarios/');
    exit();
}

session_start();
$_SESSION['mensaje'] = "Error al registrar el usuario";
$_SESSION['icono'] = "error";
header('Location:' . $URL . '/usuarios/create.php');
exit();
?>
