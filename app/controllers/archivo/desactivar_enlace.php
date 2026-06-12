<?php
include('../../config.php');
include('../../../layout/sesion.php');

$es_ajax = isset($_POST['ajax']) && $_POST['ajax'] == '1';

function responder_desactivar_ajax($ok, $mensaje)
{
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => $ok,
        'mensaje' => $mensaje
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
        responder_desactivar_ajax(false, 'Archivo invalido.');
    }
    $_SESSION['mensaje'] = "Archivo invalido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$sql = "SELECT ar.id_carpeta
        FROM tb_archivos as ar
        INNER JOIN tb_carpetas as ca ON ca.id_carpeta = ar.id_carpeta
        WHERE ar.id_archivos = :id_archivo
        AND ca.id_usuario = :id_usuario
        LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$query->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query->execute();
$archivo = $query->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    if ($es_ajax) {
        responder_desactivar_ajax(false, 'No tienes permiso para cambiar este enlace.');
    }
    $_SESSION['mensaje'] = "No tienes permiso para cambiar este enlace.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$sentencia = $pdo->prepare("UPDATE tb_enlaces_compartidos
                            SET activo = 0,
                                updated_at = :updated_at
                            WHERE id_archivo = :id_archivo");
$sentencia->bindParam(':updated_at', $fechaHora);
$sentencia->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);

if ($sentencia->execute()) {
    if ($es_ajax) {
        responder_desactivar_ajax(true, 'Enlace desactivado.');
    }
    $_SESSION['mensaje'] = "Enlace desactivado.";
    $_SESSION['icono'] = "success";
} else {
    if ($es_ajax) {
        responder_desactivar_ajax(false, 'No se pudo desactivar el enlace.');
    }
    $_SESSION['mensaje'] = "No se pudo desactivar el enlace.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $archivo['id_carpeta'] . '&compartir=' . $id_archivo);
exit();
?>
