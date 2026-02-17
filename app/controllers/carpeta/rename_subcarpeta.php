<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_carpeta = $_POST['id_carpeta'];
$nuevo_nombre = $_POST['nuevo_nombre'];
$carpeta_padre_id = $_POST['carpeta_padre_id'];

$sentencia = $pdo->prepare("UPDATE tb_carpetas
    SET nombre = :nuevo_nombre,
        updated_at = :updated_at
    WHERE id_carpeta = :id_carpeta
      AND id_usuario = :id_usuario");

$sentencia->bindParam(':nuevo_nombre', $nuevo_nombre);
$sentencia->bindParam(':updated_at', $fechaHora);
$sentencia->bindParam(':id_carpeta', $id_carpeta, PDO::PARAM_INT);
$sentencia->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "La subcarpeta fue renombrada correctamente.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "Error al intentar renombrar la subcarpeta.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $carpeta_padre_id);
exit();
?>
