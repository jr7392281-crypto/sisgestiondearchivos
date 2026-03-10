<?php
include('../../config.php');

$id_archivo = isset($_GET['id']) ? $_GET['id'] : '';
$descargar = isset($_GET['descargar']) ? $_GET['descargar'] : '0';

if ($id_archivo === '' || !ctype_digit($id_archivo) || $id_archivo == '0') {
    http_response_code(400);
    exit('Archivo invalido');
}

$sql = "SELECT id_archivos, nombre, tipo, ruta, id_carpeta
        FROM tb_archivos
        WHERE id_archivos = :id_archivo
          AND estado_archivo = 'publico'
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    http_response_code(403);
    exit('Archivo privado o no existe');
}

$ruta = $archivo['ruta'] ?? '';
if ($ruta === '') {
    http_response_code(404);
    exit('No existe el archivo');
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
