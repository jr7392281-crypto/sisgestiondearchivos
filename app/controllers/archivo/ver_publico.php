<?php
include('../../config.php');

$token = isset($_GET['token']) ? trim((string) $_GET['token']) : '';
$descargar = isset($_GET['descargar']) ? $_GET['descargar'] : '0';

if ($token === '') {
    http_response_code(400);
    exit('Enlace invalido');
}

$sql = "SELECT ar.id_archivos, ar.nombre, ar.tipo, ar.ruta, en.id_enlace
        FROM tb_enlaces_compartidos en
        INNER JOIN tb_archivos ar ON ar.id_archivos = en.id_archivo
        LEFT JOIN tb_papelera_archivos pa ON pa.id_archivo = ar.id_archivos
        WHERE en.token = :token
          AND en.activo = 1
          AND pa.id_papelera IS NULL
          AND (en.fecha_expiracion IS NULL OR en.fecha_expiracion > NOW())
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':token', $token, PDO::PARAM_STR);
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
if (strpos($ruta, '..') !== false) {
    http_response_code(404);
    exit('Ruta no valida');
}

if (strpos($ruta, 'private/') === 0) {
    $ruta_fisica = rtrim($PRIVATE_STORAGE, "/\\") . '/' . substr($ruta, 8);
} else {
    $ruta_fisica = rtrim(dirname(__DIR__, 3), "/\\") . '/' . $ruta;
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
if ($tipo === 'docx') $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
if ($tipo === 'xlsx') $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
if ($tipo === 'pptx') $mime = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($ruta_fisica));
if ($descargar == '1') {
    header('Content-Disposition: attachment; filename="' . basename($archivo['nombre']) . '"');
    $update_descargas = $pdo->prepare("UPDATE tb_enlaces_compartidos SET total_descargas = total_descargas + 1, updated_at = :updated_at WHERE id_enlace = :id_enlace");
    $update_descargas->execute([
        ':updated_at' => $fechaHora,
        ':id_enlace' => $archivo['id_enlace']
    ]);
} else {
    header('Content-Disposition: inline; filename="' . basename($archivo['nombre']) . '"');
}
header('X-Content-Type-Options: nosniff');

readfile($ruta_fisica);
exit();
?>
