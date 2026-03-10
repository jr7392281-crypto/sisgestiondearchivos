<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_archivo = isset($_GET['id']) ? $_GET['id'] : '';
$descargar = isset($_GET['descargar']) ? $_GET['descargar'] : '0';

if ($id_archivo === '' || !ctype_digit($id_archivo) || $id_archivo == '0') {
    http_response_code(400);
    exit('Archivo invalido');
}

$sql = "SELECT ar.id_archivos, ar.nombre, ar.tipo, ar.ruta, ar.id_carpeta, ar.estado_archivo
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
    http_response_code(403);
    exit('Sin permiso');
}

$ruta = $archivo['ruta'] ?? '';
if ($ruta === '') {
    $ruta = 'storage/' . $archivo['id_carpeta'] . '/' . $archivo['nombre'];
}

if (strpos($ruta, 'private/') === 0) {
    $ruta_fisica = rtrim($PRIVATE_STORAGE, "/\\") . '/' . substr($ruta, 8);
} else {
    $ruta_fisica = '../../../' . ltrim($ruta, '/');
}

if (!is_file($ruta_fisica)) {
    http_response_code(404);
    exit('No existe el archivo');
}

$tipo = strtolower($archivo['tipo'] ?? pathinfo($archivo['nombre'], PATHINFO_EXTENSION));
$mime = 'application/octet-stream';
if ($tipo === 'jpg' || $tipo === 'jpeg') $mime = 'image/jpeg';
if ($tipo === 'png') $mime = 'image/png';
if ($tipo === 'webp') $mime = 'image/webp';
if ($tipo === 'pdf') $mime = 'application/pdf';
if ($tipo === 'mp4') $mime = 'video/mp4';
if ($tipo === 'mp3') $mime = 'audio/mpeg';

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($ruta_fisica));
if ($descargar == '1') {
    header('Content-Disposition: attachment; filename="' . basename($archivo['nombre']) . '"');
} else {
    header('Content-Disposition: inline; filename="' . basename($archivo['nombre']) . '"');
}

readfile($ruta_fisica);
exit();
?>
