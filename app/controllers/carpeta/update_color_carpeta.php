<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_carpeta = $_POST['id_carpeta'];
$color = $_POST['color'];

$sentencia = $pdo->prepare("UPDATE tb_carpetas 
    SET color = :color
    WHERE id_carpeta = :id_carpeta
      AND id_usuario = :id_usuario");

$sentencia->bindParam(':color', $color);
$sentencia->bindParam(':id_carpeta', $id_carpeta);
$sentencia->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$sentencia->execute();

header("Location: $URL/unidad");
?>
