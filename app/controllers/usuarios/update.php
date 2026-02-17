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

// Se verifica si las contraseñas coinciden (aunque estén vacías)
if ($password_user == "") {
    if ($password_user == $password_repeat) {
        $password_user = password_hash($password_user, PASSWORD_DEFAULT);
        // Se prepara una sentencia SQL para actualizar nombre, email y rol (sin contraseña)
        $sentencia = $pdo->prepare("UPDATE tb_users
        SET nombre = :nombre,
            email = :email,
            id_rol = :id_rol,
            updated_at = :updated_at
        WHERE id_usuario = :id_usuario");

        // Se enlazan los parámetros con sus valores
        $sentencia->bindParam('nombre', $nombres);
        $sentencia->bindParam('email', $email);
        $sentencia->bindParam('id_rol', $rol);
        $sentencia->bindParam('updated_at', $fechaHora);
        $sentencia->bindParam('id_usuario', $id_usuario);
        $sentencia->execute();
        session_start();
        $_SESSION['mensaje'] = "Se actualizó al usuario de forma correcta";
        $_SESSION['icono'] = "success";
        header('Location:' . $URL . '/usuarios/');
    } else {
        //echo "Error, las contraseñas no son iguales";
        session_start();
        $_SESSION['mensaje'] = "Error las contraseñas no son iguales";
        $_SESSION['icono'] = "error";
        header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    }

} else {
        // Verificamos si la nueva contraseña y la repetición coinciden
    if ($password_user == $password_repeat) {
                // Encriptamos la nueva contraseña antes de guardarla
        $password_user = password_hash($password_user, PASSWORD_DEFAULT);
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
        $sentencia->bindParam('password_user', $password_user);
        $sentencia->bindParam('updated_at', $fechaHora);
        $sentencia->bindParam('id_usuario', $id_usuario);
        $sentencia->execute();
        session_start();
        $_SESSION['mensaje'] = "Se actualizó al usuario de forma correcta";
        $_SESSION['icono'] = "success";
        header('Location:' . $URL . '/usuarios/');
    } else {
        //echo "Error, las contraseñas no son iguales";
        session_start();
        $_SESSION['mensaje'] = "Error las contraseñas no son iguales";
        $_SESSION['icono'] = "error";
        header('Location:' . $URL . '/usuarios/update.php?id=' . $id_usuario);
    }

}

