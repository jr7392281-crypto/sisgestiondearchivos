<?php
include('../../config.php');
include('../../../layout/sesion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . $URL . '/unidad');
    exit();
}

if (isset($_POST['id'])) {
    $id_archivo = (int) $_POST['id'];
} else {
    $id_archivo = 0;
}

if (isset($_POST['id_usuario_destino'])) {
    $id_usuario_destino = (int) $_POST['id_usuario_destino'];
} else {
    $id_usuario_destino = 0;
}

if ($id_archivo <= 0 || $id_usuario_destino <= 0) {
    $_SESSION['mensaje'] = "Datos invalidos para quitar acceso.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$sql = "SELECT ar.id_archivos, ar.id_carpeta
        FROM tb_archivos as ar
        INNER JOIN tb_carpetas as ca ON ca.id_carpeta = ar.id_carpeta
        WHERE ar.id_archivos = :id_archivo
        AND ca.id_usuario = :id_usuario
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    $_SESSION['mensaje'] = "No tienes permiso para quitar este acceso.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$sentencia = $pdo->prepare("DELETE FROM tb_archivos_compartidos
                            WHERE id_archivo = :id_archivo
                            AND id_usuario_destino = :id_usuario_destino
                            AND id_usuario_origen = :id_usuario_origen");
$sentencia->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$sentencia->bindParam(':id_usuario_destino', $id_usuario_destino, PDO::PARAM_INT);
$sentencia->bindParam(':id_usuario_origen', $id_usuario_sesion, PDO::PARAM_INT);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Acceso quitado correctamente.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "No se pudo quitar el acceso.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $archivo['id_carpeta'] . '&compartir=' . $id_archivo);
exit();
?>
