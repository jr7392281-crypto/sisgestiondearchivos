<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_archivo = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$descargar = isset($_GET['descargar']) && $_GET['descargar'] == '1';

if ($id_archivo <= 0) {
    http_response_code(400);
    exit('Archivo invalido');
}

// Privado: solo el propietario puede ver el archivo.
$sql = "SELECT ar.nombre, ar.tipo, ar.ruta
        FROM tb_archivos ar
        INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
        WHERE ar.id_archivos = :id_archivo
          AND ca.id_usuario = :id_usuario
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindValue(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->bindValue(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    http_response_code(403);
    exit('Sin permiso');
}

$ruta = ltrim((string) ($archivo['ruta'] ?? ''), '/');
if ($ruta === '') {
    http_response_code(404);
    exit('No existe el archivo');
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

$mime = 'application/octet-stream';
if (function_exists('finfo_open')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        $detectado = finfo_file($finfo, $ruta_fisica);
        if ($detectado) {
            $mime = $detectado;
        }
        finfo_close($finfo);
    }
}

if ($mime === 'application/octet-stream') {
    $tipo = strtolower((string) ($archivo['tipo'] ?? pathinfo((string) $archivo['nombre'], PATHINFO_EXTENSION)));
    if ($tipo === 'jpg' || $tipo === 'jpeg') {
        $mime = 'image/jpeg';
    } elseif ($tipo === 'png') {
        $mime = 'image/png';
    } elseif ($tipo === 'webp') {
        $mime = 'image/webp';
    } elseif ($tipo === 'pdf') {
        $mime = 'application/pdf';
    } elseif ($tipo === 'mp4') {
        $mime = 'video/mp4';
    } elseif ($tipo === 'mp3') {
        $mime = 'audio/mpeg';
    }
}

$nombre_archivo = basename((string) $archivo['nombre']);
$disposition = $descargar ? 'attachment' : 'inline';

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($ruta_fisica));
header('Content-Disposition: ' . $disposition . '; filename="' . addslashes($nombre_archivo) . '"');
header('X-Content-Type-Options: nosniff');

readfile($ruta_fisica);
exit();
?>
