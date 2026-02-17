<?php
// Aquí normalmente están la conexión PDO ($pdo), el timezone, $URL y otras constantes
include('../../config.php');
include('../../../layout/sesion.php');
proteger_admin();
// Obtenemos el ID del usuario a eliminar desde un formulario enviado por POST
$id_usuario = $_POST['id_usuario'];

// Preparamos la consulta SQL para eliminar el usuario cuyo ID coincida
$sentencia = $pdo->prepare("DELETE FROM tb_users WHERE id_usuario = :id_usuario");

// Asociamos el valor recibido al parámetro nombrado de la consulta
$sentencia->bindParam('id_usuario', $id_usuario);

// Ejecutamos la sentencia SQL
$sentencia->execute();

// Iniciamos sesión para enviar un mensaje de feedback al usuario
session_start();
$_SESSION['mensaje'] = "Se eliminó al usuario de forma correcta";
$_SESSION['icono'] = "success";

// Redirigimos de vuelta al listado de usuarios
header('Location:' . $URL . '/usuarios/');
