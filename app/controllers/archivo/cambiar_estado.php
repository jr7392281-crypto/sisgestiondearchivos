<?php
include('../../config.php');
include('../../../layout/sesion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . $URL . '/unidad');
    exit();
}

$id_archivo = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$estado = $_POST['estado'] ?? '';

if ($id_archivo <= 0) {
    $_SESSION['mensaje'] = "Archivo invalido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

if ($estado != 'privado' && $estado != 'publico') {
    $_SESSION['mensaje'] = "Estado invalido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$sql = "SELECT ar.id_archivos, ar.id_carpeta, ar.nombre, ar.ruta
        FROM tb_archivos ar
        INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
        WHERE ar.id_archivos = :id_archivo
          AND ca.id_usuario = :id_usuario
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    $_SESSION['mensaje'] = "No tienes permiso para este archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$id_carpeta = $archivo['id_carpeta'];
$nombre = $archivo['nombre'];
$ruta_actual = $archivo['ruta'] ?? '';

if ($ruta_actual === '') {
    $_SESSION['mensaje'] = "Ruta del archivo invalida.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
    exit();
}

if (strpos($ruta_actual, 'private/') === 0) {
    $ruta_fisica_actual = rtrim($PRIVATE_STORAGE, "/\\") . '/' . substr($ruta_actual, 8);
} else {
    $ruta_fisica_actual = dirname(__DIR__, 3) . '/' . ltrim($ruta_actual, '/');
}

if ($estado === 'publico') {
    $ruta_nueva_db = 'storage/public/' . $id_usuario_sesion . '/' . $id_carpeta . '/' . $nombre;
    $directorio_nuevo = dirname(__DIR__, 3) . '/storage/public/' . $id_usuario_sesion . '/' . $id_carpeta;
} else {
    $ruta_nueva_db = 'private/' . $id_usuario_sesion . '/' . $id_carpeta . '/' . $nombre;
    $directorio_nuevo = rtrim($PRIVATE_STORAGE, "/\\") . '/' . $id_usuario_sesion . '/' . $id_carpeta;
}

if (!is_dir($directorio_nuevo)) {
    mkdir($directorio_nuevo, 0777, true);
}

$ruta_fisica_nueva = rtrim($directorio_nuevo, "/\\") . '/' . $nombre;

if ($ruta_fisica_actual !== $ruta_fisica_nueva && is_file($ruta_fisica_actual)) {
    if (!rename($ruta_fisica_actual, $ruta_fisica_nueva)) {
        $_SESSION['mensaje'] = "No se pudo mover el archivo al nuevo estado.";
        $_SESSION['icono'] = "error";
        header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
        exit();
    }
}

$update = $pdo->prepare("UPDATE tb_archivos
                         SET estado_archivo = :estado,
                             ruta = :ruta,
                             updated_at = :updated_at
                         WHERE id_archivos = :id_archivo");
$update->bindParam(':estado', $estado);
$update->bindParam(':ruta', $ruta_nueva_db);
$update->bindParam(':updated_at', $fechaHora);
$update->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);

if ($update->execute()) {
    $_SESSION['mensaje'] = "Estado del archivo actualizado.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "No se pudo actualizar el estado.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
exit();
?>
