<?php
// Incluimos el archivo de configuración general, donde probablemente están:
// - La conexión a la base de datos ($pdo)
// - Variables globales como $URL o $fechaHora
include('../../config.php');
include('../../../layout/sesion.php');
proteger_admin();
// Obtenemos los datos enviados desde un formulario mediante POST
$nombres = $_POST['nombre'];                // Nombre del usuario
$email = $_POST['email'];                  // Correo electrónico
$rol = $_POST['rol'];                      // ID del rol (por ejemplo: admin, usuario, etc.)
$password_user = $_POST['password_user'];  // Contraseña
$password_repeat = $_POST['password_repeat']; // Repetición de la contraseña para validación

// Validamos que ambas contraseñas coincidan
if($password_user == $password_repeat){

    // Encriptamos la contraseña usando password_hash con el algoritmo por defecto (actualmente BCRYPT)
    $password_user = password_hash($password_user, PASSWORD_DEFAULT);

    // Preparamos la sentencia SQL para insertar los datos del nuevo usuario
    $sentencia = $pdo->prepare("INSERT INTO tb_users 
        (nombre, email, id_rol, password_user, created_at)
        VALUES (:nombre, :email, :id_rol, :password_user, :created_at)");

    // Enlazamos los valores con los parámetros nombrados
    $sentencia->bindParam('nombre', $nombres);
    $sentencia->bindParam('email', $email);
    $sentencia->bindParam('id_rol', $rol);
    $sentencia->bindParam('password_user', $password_user);
    $sentencia->bindParam('created_at', $fechaHora); // Se espera que $fechaHora esté definido en config.php

    // Ejecutamos la sentencia SQL
    $sentencia->execute();

    // Iniciamos sesión y preparamos un mensaje de éxito
    session_start();
    $_SESSION['mensaje'] = "Se registró al usuario de forma correcta";
    $_SESSION['icono'] = "success";

    // Redirigimos al listado de usuarios
    header('Location:' . $URL . '/usuarios/');
    
} else {
    // Si las contraseñas no coinciden

    session_start();
    $_SESSION['mensaje'] = "Error las contraseñas no son iguales";
    $_SESSION['icono'] = "error";

    // Redirigimos nuevamente al formulario de creación de usuarios
    header('Location:' . $URL . '/usuarios/create.php');
}
?>
