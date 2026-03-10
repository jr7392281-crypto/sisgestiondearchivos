<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_archivo = $_POST['id'];

$sql = "SELECT ar.id_carpeta, ar.ruta
        FROM tb_archivos ar
        INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
        WHERE ar.id_archivos = :id_archivo
          AND ca.id_usuario = :id_usuario
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':id_archivo', $id_archivo);
$query->bindParam(':id_usuario', $id_usuario_sesion);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    $_SESSION['mensaje'] = "No tienes permiso para eliminar este archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$id_carpeta = $archivo['id_carpeta'];
$ruta = $archivo['ruta'];

$ruta_fisica = '';
if (strpos($ruta, 'private/') === 0) {
    $ruta_fisica = rtrim($PRIVATE_STORAGE, "/\\") . '/' . substr($ruta, 8);
} else {
    $ruta_fisica = '../../../' . ltrim($ruta, '/');
}

if (is_file($ruta_fisica)) {
    unlink($ruta_fisica);
}
$delete = $pdo->prepare("DELETE FROM tb_archivos WHERE id_archivos = :id_archivo");
$delete->bindParam(':id_archivo', $id_archivo);

if ($delete->execute()) {
    $_SESSION['mensaje'] = "Archivo eliminado correctamente.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "No se pudo eliminar el archivo.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
exit();
?>
