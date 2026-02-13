<?php
include('../../config.php');   // conexión, variables globales

$id_carpeta = $_POST['id_carpeta'];
$color = $_POST['color'];
$carpeta_padre_id = $_POST['carpeta_padre_id'];

$sentencia = $pdo->prepare("UPDATE tb_carpetas 
    SET color = :color
    WHERE id_carpeta = :id_carpeta");

$sentencia->bindParam(':color', $color);
$sentencia->bindParam(':id_carpeta', $id_carpeta);
$sentencia->execute()
    ?>
<script>
    location.href = "<?php echo $URL; ?>/unidad/show.php?id=<?php echo $carpeta_padre_id; ?>";
</script>
<?php

?>