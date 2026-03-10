<?php
include('../../config.php');
include('../../../layout/sesion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $URL/unidad");
    exit();
}

$nombre = $_POST['nombre'];

if ($nombre === '') {
    $_SESSION['mensaje'] = "Debes ingresar un nombre de carpeta.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/unidad");
    exit();
}

$sentencia = $pdo->prepare("INSERT INTO tb_carpetas (nombre, id_usuario) VALUES (:nombre, :id_usuario)");
$ok = $sentencia->execute([
    ':nombre' => $nombre,
    ':id_usuario' => $id_usuario_sesion
]);

if ($ok) {
    $_SESSION['mensaje'] = "Carpeta creada correctamente";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "Error al crear carpeta";
    $_SESSION['icono'] = "error";
}

header("Location: $URL/unidad");
exit();
?>
