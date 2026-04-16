<?php
include('../../config.php');

$id_archivo = isset($_GET['id']) ? $_GET['id'] : '';
$descargar = isset($_GET['descargar']) ? $_GET['descargar'] : '0';

if ($id_archivo === '' || !ctype_digit($id_archivo) || $id_archivo == '0') {
    http_response_code(400);
    exit('Archivo invalido');
}

$sql = "SELECT id_archivos, nombre, tipo, ruta
        FROM tb_archivos
        WHERE id_archivos = :id_archivo
          AND estado_archivo = 'publico'
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    http_response_code(404);
    exit('Archivo no disponible');
}

$ruta = $archivo['ruta'] ?? '';
if ($ruta === '') {
    http_response_code(404);
    exit('No existe el archivo');
}

$ruta = ltrim($ruta, '/');
if (strpos($ruta, 'storage/public/') !== 0 || strpos($ruta, '..') !== false) {
    http_response_code(404);
    exit('Ruta no valida');
}

$ruta_fisica = rtrim(dirname(__DIR__, 3), "/\\") . '/' . $ruta;
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
if ($tipo === 'docx') $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
if ($tipo === 'xlsx') $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
if ($tipo === 'pptx') $mime = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($ruta_fisica));
if ($descargar == '1') {
    header('Content-Disposition: attachment; filename="' . basename($archivo['nombre']) . '"');
} else {
    header('Content-Disposition: inline; filename="' . basename($archivo['nombre']) . '"');
}
header('X-Content-Type-Options: nosniff');

readfile($ruta_fisica);
exit();
?>
