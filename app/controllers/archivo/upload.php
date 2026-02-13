<?php
include('../../config.php');

// Obtener ID de carpeta
$id_carpeta = $_POST['id_carpeta'];
$fechaHora = date("Y-m-d H:i:s");

// Verificar si el archivo fue subido correctamente
if (!empty($_FILES['archivo']['name'])) {
    $nombre_original = $_FILES['archivo']['name'];

    // Renombrar archivo para evitar duplicados
    $nombre_unico = date("YmdHis") . "__" . $nombre_original;
    $ruta_destino = "../../../public/archivos_subidos/" . $nombre_unico;

    // Mover archivo al servidor
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {

        // Insertar en base de datos
        $sentencia = $pdo->prepare("INSERT INTO tb_archivos (nombre, id_carpeta, created_at) 
                                    VALUES (:nombre, :id_carpeta, :created_at)");

        $sentencia->bindParam(':nombre', $nombre_unico);
        $sentencia->bindParam(':id_carpeta', $id_carpeta);
        $sentencia->bindParam(':created_at', $fechaHora);

        if ($sentencia->execute()) {
            http_response_code(200);
            echo "Archivo subido correctamente.";
        } else {
            http_response_code(500);
            echo "Error al registrar en la base de datos.";
        }

    } else {
        http_response_code(500);
        echo "Error al mover el archivo.";
    }

} else {
    http_response_code(400);
    echo "No se recibió ningún archivo.";
}
?>