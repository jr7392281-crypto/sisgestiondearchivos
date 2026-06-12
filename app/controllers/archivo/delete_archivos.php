<?php
include('../../config.php');
include('../../../layout/sesion.php');

if (isset($_POST['id'])) {
    $id_archivo = (int) $_POST['id'];
} else {
    $id_archivo = 0;
}

// Verifica que el archivo pertenezca al usuario.
$sql = "SELECT ar.id_archivos, ar.id_carpeta
        FROM tb_archivos ar
        INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
        LEFT JOIN tb_papelera_archivos pa ON pa.id_archivo = ar.id_archivos
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
    $_SESSION['mensaje'] = "No tienes permiso para eliminar este archivo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$id_carpeta = $archivo['id_carpeta'];

$buscar_papelera = $pdo->prepare("SELECT id_papelera FROM tb_papelera_archivos WHERE id_archivo = :id_archivo LIMIT 1");
$buscar_papelera->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$buscar_papelera->execute();
$archivo_papelera = $buscar_papelera->fetch(PDO::FETCH_ASSOC);

if ($archivo_papelera) {
    $papelera = $pdo->prepare("UPDATE tb_papelera_archivos
                               SET id_usuario_elimino = :id_usuario_elimino,
                                   fecha_eliminacion = :fecha_eliminacion,
                                   created_at = :created_at
                               WHERE id_archivo = :id_archivo");
} else {
    $papelera = $pdo->prepare("INSERT INTO tb_papelera_archivos
        (id_archivo, id_usuario_elimino, fecha_eliminacion, fecha_expiracion, created_at)
        VALUES (:id_archivo, :id_usuario_elimino, :fecha_eliminacion, NULL, :created_at)");
}

$papelera->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
$papelera->bindParam(':id_usuario_elimino', $id_usuario_sesion, PDO::PARAM_INT);
$papelera->bindParam(':fecha_eliminacion', $fechaHora);
$papelera->bindParam(':created_at', $fechaHora);

if ($papelera->execute()) {
    $_SESSION['mensaje'] = "Archivo enviado a papelera.";
    $_SESSION['icono'] = "success";
} else {
    $_SESSION['mensaje'] = "No se pudo enviar el archivo a papelera.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
exit();
?>
