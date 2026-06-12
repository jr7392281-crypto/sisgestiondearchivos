<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_archivo = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$descargar = isset($_GET['descargar']) && $_GET['descargar'] == '1';

if ($id_archivo <= 0) {
    http_response_code(400);
    exit('Archivo invalido');
}

// Permite ver archivos propios o archivos compartidos con el usuario.
$sql = "SELECT ar.nombre, ar.tipo, ar.ruta,
               ca.id_usuario AS id_dueno,
               ac.permiso AS permiso_compartido
        FROM tb_archivos ar
        INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
        LEFT JOIN tb_archivos_compartidos ac ON ac.id_archivo = ar.id_archivos
          AND ac.id_usuario_destino = :id_usuario_compartido
        LEFT JOIN tb_papelera_archivos pa ON pa.id_archivo = ar.id_archivos
        WHERE ar.id_archivos = :id_archivo
          AND pa.id_papelera IS NULL
          AND (ca.id_usuario = :id_usuario OR ac.id_compartido IS NOT NULL)
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindValue(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->bindValue(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query->bindValue(':id_usuario_compartido', $id_usuario_sesion, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    http_response_code(403);
    exit('Sin permiso');
}

$es_dueno = (int) $archivo['id_dueno'] == (int) $id_usuario_sesion;
$permiso_compartido = (string) ($archivo['permiso_compartido'] ?? '');

if ($descargar && !$es_dueno && $permiso_compartido != 'descargar') {
    http_response_code(403);
    exit('Sin permiso de descarga');
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
