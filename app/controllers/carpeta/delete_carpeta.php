<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_carpeta = $_POST['id_carpeta'] ?? 0;

$buscar = $pdo->prepare("SELECT id_carpeta FROM tb_carpetas WHERE id_carpeta = :id_carpeta AND id_usuario = :id_usuario LIMIT 1");
$buscar->bindParam(':id_carpeta', $id_carpeta, PDO::PARAM_INT);
$buscar->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$buscar->execute();
$carpeta = $buscar->fetch(PDO::FETCH_ASSOC);

if (!$carpeta) {
    $_SESSION['mensaje'] = "No tienes permiso para eliminar esta carpeta.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$carpetas_ids = [(int) $id_carpeta];
$pendientes = [(int) $id_carpeta];

while (!empty($pendientes)) {
    $placeholders = implode(',', array_fill(0, count($pendientes), '?'));
    $query_hijas = $pdo->prepare("SELECT id_carpeta FROM tb_carpetas WHERE carpeta_padre_id IN ($placeholders) AND id_usuario = ?");

    $i = 1;
    foreach ($pendientes as $id_pendiente) {
        $query_hijas->bindValue($i, $id_pendiente, PDO::PARAM_INT);
        $i++;
    }
    $query_hijas->bindValue($i, $id_usuario_sesion, PDO::PARAM_INT);
    $query_hijas->execute();

    $hijas = $query_hijas->fetchAll(PDO::FETCH_COLUMN);
    $pendientes = [];

    foreach ($hijas as $id_hija) {
        $id_hija = (int) $id_hija;
        if (!in_array($id_hija, $carpetas_ids, true)) {
            $carpetas_ids[] = $id_hija;
            $pendientes[] = $id_hija;
        }
    }
}

if (!empty($carpetas_ids)) {
    $placeholders = implode(',', array_fill(0, count($carpetas_ids), '?'));
    $query_archivos = $pdo->prepare("SELECT ar.ruta
                                     FROM tb_archivos ar
                                     INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
                                     WHERE ar.id_carpeta IN ($placeholders) AND ca.id_usuario = ?");

    $i = 1;
    foreach ($carpetas_ids as $id_carpeta_item) {
        $query_archivos->bindValue($i, $id_carpeta_item, PDO::PARAM_INT);
        $i++;
    }
    $query_archivos->bindValue($i, $id_usuario_sesion, PDO::PARAM_INT);
    $query_archivos->execute();
    $rutas = $query_archivos->fetchAll(PDO::FETCH_COLUMN);

    foreach ($rutas as $ruta) {
        if (!$ruta) {
            continue;
        }

        if (strpos($ruta, 'private/') === 0) {
            $ruta_fisica = rtrim($PRIVATE_STORAGE, "/\\") . '/' . substr($ruta, 8);
        } else {
            $ruta_fisica = '../../../' . ltrim($ruta, '/');
        }

        if (is_file($ruta_fisica)) {
            unlink($ruta_fisica);
        }
    }
}

$delete = $pdo->prepare("DELETE FROM tb_carpetas WHERE id_carpeta = :id_carpeta AND id_usuario = :id_usuario");
$delete->bindParam(':id_carpeta', $id_carpeta, PDO::PARAM_INT);
$delete->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);

if ($delete->execute()) {
    $_SESSION['mensaje'] = "Carpeta eliminada correctamente.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "No se pudo eliminar la carpeta.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad');
exit();
?>
