<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_carpeta = isset($_POST['id_carpeta']) ? $_POST['id_carpeta'] : '';
$estado_archivo = 'privado';
$max_archivos_usuario = 2000;

if ($id_carpeta === '' || !ctype_digit($id_carpeta) || $id_carpeta == '0') {
    $_SESSION['mensaje'] = "Carpeta invalida.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$consulta = $pdo->prepare("SELECT id_carpeta
                           FROM tb_carpetas
                           WHERE id_carpeta = :id_carpeta
                           AND id_usuario = :id_usuario
                           LIMIT 1");
$consulta->execute([
    ':id_carpeta' => $id_carpeta,
    ':id_usuario' => $id_usuario_sesion
]);

if (!$consulta->fetch(PDO::FETCH_ASSOC)) {
    $_SESSION['mensaje'] = "No tienes permiso para subir archivos en esta carpeta.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$sql_total_usuario = "SELECT COUNT(*)
                      FROM tb_archivos ar
                      INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
                      WHERE ca.id_usuario = :id_usuario";
$query_total_usuario = $pdo->prepare($sql_total_usuario);
$query_total_usuario->execute([':id_usuario' => $id_usuario_sesion]);
$total_usuario = $query_total_usuario->fetchColumn();

if ($total_usuario >= $max_archivos_usuario) {
    $_SESSION['mensaje'] = "Llegaste al limite de archivos permitidos para tu usuario.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
    exit();
}

if (!isset($_FILES['archivo']) || $_FILES['archivo']['name'] === '') {
    $_SESSION['mensaje'] = "No se recibio ningun archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
    exit();
}

$permitidos = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'docx', 'xlsx', 'pptx', 'mp4', 'mp3'];
$max_bytes = 50 * 1024 * 1024;

$nombre_original = basename($_FILES['archivo']['name']);
$tamano_archivo = $_FILES['archivo']['size'];
$tmp_archivo = $_FILES['archivo']['tmp_name'];
$error_archivo = $_FILES['archivo']['error'];
$extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

if ($error_archivo != UPLOAD_ERR_OK) {
    $_SESSION['mensaje'] = "Error al subir el archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
    exit();
}

if (!in_array($extension, $permitidos, true)) {
    $_SESSION['mensaje'] = "Tipo de archivo no permitido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
    exit();
}

if ($tamano_archivo <= 0 || $tamano_archivo > $max_bytes) {
    $_SESSION['mensaje'] = "El archivo supera el tamano maximo permitido (50 MB).";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
    exit();
}

$nombre_archivo = date('YmdHis') . '__' . $nombre_original;
$ruta_db = "private/" . $id_usuario_sesion . "/" . $id_carpeta . "/" . $nombre_archivo;
$directorio_destino = rtrim($PRIVATE_STORAGE, "/\\") . "/" . $id_usuario_sesion . "/" . $id_carpeta . "/";

if (!is_dir($directorio_destino)) {
    mkdir($directorio_destino, 0777, true);
}

$ruta_destino = $directorio_destino . $nombre_archivo;
if (!move_uploaded_file($tmp_archivo, $ruta_destino)) {
    $_SESSION['mensaje'] = "Error al mover el archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
    exit();
}

$sentencia = $pdo->prepare("INSERT INTO tb_archivos VALUES (NULL, :nombre, :estado_archivo, :id_carpeta, :tipo, :tamano, :ruta, :created_at, :updated_at)");
$ok = $sentencia->execute([
    ':nombre' => $nombre_archivo,
    ':estado_archivo' => $estado_archivo,
    ':id_carpeta' => $id_carpeta,
    ':tipo' => $extension,
    ':tamano' => $tamano_archivo,
    ':ruta' => $ruta_db,
    ':created_at' => $fechaHora,
    ':updated_at' => $fechaHora
]);

if ($ok) {
    $_SESSION['mensaje'] = "Archivo subido correctamente.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "Error al registrar en la base de datos.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
exit();
?>
