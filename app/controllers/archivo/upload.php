<?php
include('../../config.php');
include('../../../layout/sesion.php');

$id_carpeta = isset($_POST['id_carpeta']) ? (int) $_POST['id_carpeta'] : 0;

if ($id_carpeta <= 0) {
    $_SESSION['mensaje'] = "Carpeta inválida.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

$consulta = $pdo->prepare("SELECT id_carpeta FROM tb_carpetas WHERE id_carpeta = :id_carpeta AND id_usuario = :id_usuario");
$consulta->bindParam(':id_carpeta', $id_carpeta, PDO::PARAM_INT);
$consulta->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$consulta->execute();
$carpeta_permitida = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$carpeta_permitida) {
    $_SESSION['mensaje'] = "No tienes permiso para subir archivos en esta carpeta.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}

if (!empty($_FILES['archivo']['name'])) {
    $nombre_archivo = basename($_FILES['archivo']['name']);
    $ruta_destino = "../../../storage/" . $id_carpeta . "/" . $nombre_archivo;

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {
        $sentencia = $pdo->prepare("INSERT INTO tb_archivos (nombre, id_carpeta, created_at) VALUES (:nombre, :id_carpeta, :created_at)");
        $sentencia->bindParam(':nombre', $nombre_archivo);
        $sentencia->bindParam(':id_carpeta', $id_carpeta, PDO::PARAM_INT);
        $sentencia->bindParam(':created_at', $fechaHora);

        if ($sentencia->execute()) {
            $_SESSION['mensaje'] = "Archivo subido correctamente.";
            $_SESSION['icono'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al registrar en la base de datos.";
            $_SESSION['icono'] = "error";
        }
    } else {
        $_SESSION['mensaje'] = "Error al mover el archivo.";
        $_SESSION['icono'] = "error";
    }
} else {
    $_SESSION['mensaje'] = "No se recibió ningún archivo.";
    $_SESSION['icono'] = "error";
}

header('Location:' . $URL . '/unidad/show.php?id=' . $id_carpeta);
exit();
?>
