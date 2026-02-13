<?php
include('../../config.php');

$id_carpeta = $_POST['id_carpeta'];
$color = $_POST['color'];

$sentencia = $pdo->prepare("UPDATE tb_carpetas 
    SET color = :color
    WHERE id_carpeta = :id_carpeta");

$sentencia->bindParam(':color', $color);
$sentencia->bindParam(':id_carpeta', $id_carpeta);
$sentencia->execute();

header("Location: $URL/unidad");
?>
