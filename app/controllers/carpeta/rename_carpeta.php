<?php
// Incluimos el archivo de configuración, donde está la conexión a la base de datos y otras variables globales
include('../../config.php');
include('../../../layout/sesion.php');
// Obtenemos el ID de la carpeta que queremos renombrar, y el nuevo nombre, desde el formulario (método POST)
$id_carpeta = $_POST['id_carpeta'];
$nuevo_nombre = $_POST['nuevo_nombre'];

// Preparamos la consulta SQL para actualizar el nombre de la carpeta
// También actualizamos el campo 'updated_at' con la fecha y hora actual
$sentencia = $pdo->prepare("UPDATE tb_carpetas 
    SET nombre = :nuevo_nombre, 
        updated_at = :updated_at 
    WHERE id_carpeta = :id_carpeta
      AND id_usuario = :id_usuario");
// Enlazamos los valores a los parámetros de la consulta preparada
$sentencia->bindParam(':nuevo_nombre', $nuevo_nombre);
$sentencia->bindParam(':updated_at', $fechaHora);
$sentencia->bindParam(':id_carpeta', $id_carpeta);
$sentencia->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
// Ejecutamos la consulta y verificamos si todo salió bien
if ($sentencia->execute()) {
    // Guardamos un mensaje de éxito que luego puede mostrarse con alguna librería como SweetAlert
    $_SESSION['mensaje'] = "La carpeta fue renombrada correctamente.";
    $_SESSION['icono'] = "success";

     ?>
    <script>
        location.href = "<?php echo $URL; ?>/unidad";
    </script>
    <?php
} else {
    // Si la consulta falla, también mostramos un mensaje pero de error
    $_SESSION['mensaje'] = "Error al intentar renombrar la carpeta.";
    $_SESSION['icono'] = "error";
    ?>
    <script>
        location.href = "<?php echo $URL; ?>/unidad";
    </script>
    <?php
}
?>
