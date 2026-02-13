<?php
include('../../config.php');   // conexión, variables globales

$id_carpeta = $_POST['id_carpeta'];
$nuevo_nombre = $_POST['nuevo_nombre'];
$carpeta_padre_id = $_POST['carpeta_padre_id'];

$sentencia = $pdo->prepare("UPDATE tb_carpetas 
    SET nombre = :nuevo_nombre, 
        updated_at = :updated_at 
    WHERE id_carpeta = :id_carpeta");

$sentencia->bindParam(':nuevo_nombre', $nuevo_nombre);
$sentencia->bindParam(':updated_at', $fechaHora);
$sentencia->bindParam(':id_carpeta', $id_carpeta);

session_start();

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "La subcarpeta fue renombrada correctamente.";
    $_SESSION['icono']   = "success";
    
     ?>
    <script>
        location.href = "<?php echo $URL; ?>/unidad/show.php?id=<?php echo $carpeta_padre_id; ?>";
    </script>
    <?php

} else {
    $_SESSION['mensaje'] = "Error al intentar renombrar la subcarpeta.";
    $_SESSION['icono']   = "error";

     ?>
    <script>
     location.href = "<?php echo $URL; ?>/unidad/show.php?id=<?php echo $carpeta_padre_id; ?>";
    </script>
    <?php
}

?>