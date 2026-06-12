<?php
include('../../config.php');
include('../../../layout/sesion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . $URL . '/unidad/papelera.php');
    exit();
}

if (isset($_POST['id'])) {
    $id_archivo = (int) $_POST['id'];
} else {
    $id_archivo = 0;
}

if ($id_archivo <= 0) {
    $_SESSION['mensaje'] = "Archivo invalido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/papelera.php');
    exit();
}

$sql = "SELECT ar.id_archivos
        FROM tb_archivos as ar
        INNER JOIN tb_carpetas as ca ON ca.id_carpeta = ar.id_carpeta
        INNER JOIN tb_papelera_archivos as pa ON pa.id_archivo = ar.id_archivos
        WHERE ar.id_archivos = :id_archivo
        AND ca.id_usuario = :id_usuario
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    $_SESSION['mensaje'] = "No tienes permiso para restaurar este archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/papelera.php');
    exit();
}

$sentencia = $pdo->prepare("DELETE FROM tb_papelera_archivos WHERE id_archivo = :id_archivo");
$sentencia->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Archivo restaurado correctamente.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "No se pudo restaurar el archivo.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/papelera.php');
exit();
?>
