<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_carpeta = $_POST['id_carpeta'];
$color = $_POST['color'];
$carpeta_padre_id = $_POST['carpeta_padre_id'];

$sentencia = $pdo->prepare("UPDATE tb_carpetas
    SET color = :color
    WHERE id_carpeta = :id_carpeta
      AND id_usuario = :id_usuario");

$sentencia->bindParam(':color', $color);
$sentencia->bindParam(':id_carpeta', $id_carpeta, PDO::PARAM_INT);
$sentencia->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);

$sentencia->execute();

header('Location:' . $URL . '/unidad/show.php?id=' . $carpeta_padre_id);
exit();
?>
