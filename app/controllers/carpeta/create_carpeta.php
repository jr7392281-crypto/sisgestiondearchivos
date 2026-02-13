<?php
include('../../config.php');
include('../../../layout/sesion.php');

// Recibimos datos
$nombre = $_POST['nombre'];
$id_usuario_post = $_POST['id_usuario']; // VIENE POR POST

// Seguridad: aseguramos que el usuario no modifique el ID
$id_usuario_real = $id_usuario_sesion; // VIENE DE SESION

// Si los IDs no coinciden → intento de modificación
if ($id_usuario_post != $id_usuario_real) {
    $_SESSION['mensaje'] = "Error de seguridad: ID inválido";
    $_SESSION['icono'] = "error";
    header("Location: $URL/unidad");
    exit();
}

// Preparamos la inserción
$sentencia = $pdo->prepare(" INSERT INTO tb_carpetas (nombre, id_usuario)
    VALUES (:nombre, :id_usuario)");

$sentencia->bindParam(':nombre', $nombre);
$sentencia->bindParam(':id_usuario', $id_usuario_real);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Carpeta creada correctamente";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "Error al crear carpeta";
    $_SESSION['icono'] = "error";
}

header("Location: $URL/unidad");
exit();
?>