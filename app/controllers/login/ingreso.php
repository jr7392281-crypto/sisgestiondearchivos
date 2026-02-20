<?php
include('../../config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location:' . $URL . '/login');
    exit();
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password_user = isset($_POST['password_user']) ? $_POST['password_user'] : '';

if ($email === '' || $password_user === '') {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Error Datos Incorrectos";
    header('location:' . $URL . '/login');
    exit();
}

$sql = "SELECT * FROM tb_users WHERE email = :email LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    $email = $usuario['email'];
    $nombres = $usuario['nombre'];
    $password_user_tb = $usuario['password_user'];
    $id_rol = $usuario['id_rol'];
}

$credenciales_validas = false;
if ($usuario) {
    $credenciales_validas = password_verify($password_user, $password_user_tb) || $password_user === $password_user_tb;
}

if ($credenciales_validas) {
    //echo"Datos correctos";
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['sesion_email']=$email;
    // Guardar el rol del usuario en la sesión (1=Admin, 2=Usuario)
    $_SESSION['id_rol'] = $id_rol;

    // Guardar permisos del usuario en la sesión
    $sentencia = $pdo->prepare("SELECT nombre_permiso FROM tb_permision WHERE id_rol = :id_rol");
    $sentencia->bindParam(':id_rol', $id_rol);
    $sentencia->execute();
    $permisos = $sentencia->fetchAll(PDO::FETCH_COLUMN);
    $_SESSION['permisos'] = $permisos;

    header('location:'.$URL.'/index.php');
    exit();
}else{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['mensaje'] = "Error Datos Incorrectos";
    header('location:'.$URL.'/login');
    exit();
}
