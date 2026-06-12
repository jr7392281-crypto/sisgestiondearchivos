<?php
include('../../config.php');
include('../../../layout/sesion.php');

$es_ajax = isset($_POST['ajax']) && $_POST['ajax'] == '1';

function responder_enlace_ajax($ok, $mensaje, $url = '')
{
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => $ok,
        'mensaje' => $mensaje,
        'url' => $url
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . $URL . '/unidad');
    exit();
}

if (isset($_POST['id'])) {
    $id_archivo = (int) $_POST['id'];
} else {
    $id_archivo = 0;
}

if ($id_archivo <= 0) {
    if ($es_ajax) {
        responder_enlace_ajax(false, 'Archivo invalido.');
    }
    $_SESSION['mensaje'] = "Archivo invalido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$sql = "SELECT ar.id_archivos, ar.id_carpeta
        FROM tb_archivos as ar
        INNER JOIN tb_carpetas as ca ON ca.id_carpeta = ar.id_carpeta
        LEFT JOIN tb_papelera_archivos as pa ON pa.id_archivo = ar.id_archivos
        WHERE ar.id_archivos = :id_archivo
        AND ca.id_usuario = :id_usuario
        AND pa.id_papelera IS NULL
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    if ($es_ajax) {
        responder_enlace_ajax(false, 'No tienes permiso para compartir este archivo.');
    }
    $_SESSION['mensaje'] = "No tienes permiso para compartir este archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$token = bin2hex(random_bytes(32));

$desactivar = $pdo->prepare("UPDATE tb_enlaces_compartidos
                             SET activo = 0,
                                 updated_at = :updated_at
                             WHERE id_archivo = :id_archivo");
$desactivar->bindParam(':updated_at', $fechaHora);
$desactivar->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$desactivar->execute();

$sentencia = $pdo->prepare("INSERT INTO tb_enlaces_compartidos
    (id_archivo, id_usuario_creador, token, activo, fecha_expiracion, total_descargas, created_at, updated_at)
    VALUES (:id_archivo, :id_usuario_creador, :token, 1, NULL, 0, :created_at, :updated_at)");
$sentencia->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$sentencia->bindParam(':id_usuario_creador', $id_usuario_sesion, PDO::PARAM_INT);
$sentencia->bindParam(':token', $token);
$sentencia->bindParam(':created_at', $fechaHora);
$sentencia->bindParam(':updated_at', $fechaHora);

if ($sentencia->execute()) {
    $url_enlace = $URL . '/app/controllers/archivo/ver_publico.php?token=' . urlencode($token) . '&descargar=1';
    if ($es_ajax) {
        responder_enlace_ajax(true, 'Enlace generado correctamente.', $url_enlace);
    }
    $_SESSION['mensaje'] = "Enlace generado correctamente.";
    $_SESSION['icono'] = "success";
} else {
    if ($es_ajax) {
        responder_enlace_ajax(false, 'No se pudo generar el enlace.');
    }
    $_SESSION['mensaje'] = "No se pudo generar el enlace.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $archivo['id_carpeta'] . '&compartir=' . $id_archivo);
exit();
?>
