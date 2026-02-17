<?php
include('../../config.php');

$id_carpeta = $_POST['id_carpeta'];
$fechaHora = date("Y-m-d H:i:s");

if (!empty($_FILES['archivo']['name'])) {

    $nombre_archivo = $_FILES['archivo']['name'];

    // Ruta donde ya existe la carpeta con el ID
    $ruta_destino = "../../../storage/" . $id_carpeta . "/" . $nombre_archivo;

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {

        $sentencia = $pdo->prepare("INSERT INTO tb_archivos 
                                    (nombre, id_carpeta, created_at) 
                                    VALUES (:nombre, :id_carpeta, :created_at)");

        $sentencia->bindParam(':nombre', $nombre_archivo);
        $sentencia->bindParam(':id_carpeta', $id_carpeta);
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
