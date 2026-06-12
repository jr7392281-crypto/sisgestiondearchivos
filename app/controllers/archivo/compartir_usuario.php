<?php
include('../../config.php');
include('../../../layout/sesion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . $URL . '/unidad');
    exit();
}

if (isset($_POST['id'])) {
    $id_archivo = (int) $_POST['id'];
} else {
    $id_archivo = 0;
}

if (isset($_POST['id_usuario_destino'])) {
    $id_usuario_destino = (int) $_POST['id_usuario_destino'];
} else {
    $id_usuario_destino = 0;
}

if (isset($_POST['permiso'])) {
    $permiso = strtolower(trim($_POST['permiso']));
} else {
    $permiso = 'ver';
}

if ($permiso != 'ver' && $permiso != 'descargar') {
    $permiso = 'ver';
}

if ($id_archivo <= 0 || $id_usuario_destino <= 0 || $id_usuario_destino == $id_usuario_sesion) {
    $_SESSION['mensaje'] = "Datos invalidos para compartir.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$sql = "SELECT ar.id_archivos, ar.id_carpeta, ar.tipo
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
    $_SESSION['mensaje'] = "No tienes permiso para compartir este archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$tipo_archivo = strtolower($archivo['tipo']);
if ($tipo_archivo == 'docx' || $tipo_archivo == 'xlsx' || $tipo_archivo == 'pptx') {
    $permiso = 'descargar';
}

$buscar_usuario = $pdo->prepare("SELECT id_usuario FROM tb_users WHERE id_usuario = :id_usuario LIMIT 1");
$buscar_usuario->bindParam(':id_usuario', $id_usuario_destino, PDO::PARAM_INT);
$buscar_usuario->execute();
$usuario_destino = $buscar_usuario->fetch(PDO::FETCH_ASSOC);

if (!$usuario_destino) {
    $_SESSION['mensaje'] = "El usuario destino no existe.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/show.php?id=' . $archivo['id_carpeta']);
    exit();
}

$buscar_compartido = $pdo->prepare("SELECT id_compartido
                                    FROM tb_archivos_compartidos
                                    WHERE id_archivo = :id_archivo
                                    AND id_usuario_destino = :id_usuario_destino
                                    LIMIT 1");
$buscar_compartido->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$buscar_compartido->bindParam(':id_usuario_destino', $id_usuario_destino, PDO::PARAM_INT);
$buscar_compartido->execute();
$compartido = $buscar_compartido->fetch(PDO::FETCH_ASSOC);

if ($compartido) {
    $id_compartido = $compartido['id_compartido'];
    $sentencia = $pdo->prepare("UPDATE tb_archivos_compartidos
                                SET permiso = :permiso,
                                    updated_at = :updated_at
                                WHERE id_compartido = :id_compartido");
    $sentencia->bindParam(':permiso', $permiso);
    $sentencia->bindParam(':updated_at', $fechaHora);
    $sentencia->bindParam(':id_compartido', $id_compartido, PDO::PARAM_INT);
} else {
    $sentencia = $pdo->prepare("INSERT INTO tb_archivos_compartidos
        (id_archivo, id_usuario_origen, id_usuario_destino, permiso, created_at, updated_at)
        VALUES (:id_archivo, :id_usuario_origen, :id_usuario_destino, :permiso, :created_at, :updated_at)");
    $sentencia->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
    $sentencia->bindParam(':id_usuario_origen', $id_usuario_sesion, PDO::PARAM_INT);
    $sentencia->bindParam(':id_usuario_destino', $id_usuario_destino, PDO::PARAM_INT);
    $sentencia->bindParam(':permiso', $permiso);
    $sentencia->bindParam(':created_at', $fechaHora);
    $sentencia->bindParam(':updated_at', $fechaHora);
}

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Archivo compartido correctamente.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "No se pudo compartir el archivo.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $archivo['id_carpeta']);
exit();
?>
