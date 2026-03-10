<?php
include('../../config.php');
include('../../../layout/sesion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $URL/unidad");
    exit();
}

$nombre = $_POST['nombre'];
$carpeta_padre_id = $_POST['carpeta_padre_id'];

if ($nombre === '' || $carpeta_padre_id === '') {
    $_SESSION['mensaje'] = "Datos invalidos para crear la subcarpeta.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/unidad");
    exit();
}

$validar_padre = $pdo->prepare("SELECT id_carpeta FROM tb_carpetas WHERE id_carpeta = :id_carpeta AND id_usuario = :id_usuario LIMIT 1");
$validar_padre->bindParam(':id_carpeta', $carpeta_padre_id, PDO::PARAM_INT);
$validar_padre->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$validar_padre->execute();

if (!$validar_padre->fetch(PDO::FETCH_ASSOC)) {
    $_SESSION['mensaje'] = "No tienes permiso para crear subcarpetas aqui.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/unidad");
    exit();
}

$sentencia = $pdo->prepare("INSERT INTO tb_carpetas (nombre, carpeta_padre_id, id_usuario) VALUES (:nombre, :carpeta_padre_id, :id_usuario)");
$sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$sentencia->bindParam(':carpeta_padre_id', $carpeta_padre_id, PDO::PARAM_INT);
$sentencia->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Se registro la subcarpeta de forma correcta";
    $_SESSION['icono'] = "success";
    header("Location: $URL/unidad/show.php?id=$carpeta_padre_id");
    exit();
}

$_SESSION['mensaje'] = "Error al registrar";
$_SESSION['icono'] = "error";
header("Location: $URL/unidad");
exit();
?>
