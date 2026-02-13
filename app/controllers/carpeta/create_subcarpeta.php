<?php
// Incluimos la configuración general (conexión a la base de datos, constantes, etc.)
include('../../config.php');

// Obtenemos los datos enviados desde el formulario (nombre de la subcarpeta y el ID de la carpeta padre)
$nombre = $_POST['nombre'];
$carpeta_padre_id = $_POST['carpeta_padre_id'];

// Preparamos la consulta SQL para insertar una nueva subcarpeta.
// Usamos parámetros para mayor seguridad y evitar inyecciones SQL.
$sentencia = $pdo->prepare("INSERT INTO tb_carpetas (nombre, carpeta_padre_id) VALUES (:nombre, :carpeta_padre_id)");
$sentencia->bindParam(':nombre', $nombre);
$sentencia->bindParam(':carpeta_padre_id', $carpeta_padre_id);

// Ejecutamos la consulta y verificamos si se insertó correctamente
if ($sentencia->execute()) {
    // Iniciamos la sesión (es obligatorio para poder usar $_SESSION)
    session_start();
    // Guardamos un mensaje de éxito que se puede mostrar con alertas en la vista
    $_SESSION['mensaje'] = "Se registró la subcarpeta de forma correcta";
    $_SESSION['icono'] = "success";
    ?>
    <script>
        location.href = "<?php echo $URL; ?>/unidad/show.php?id=<?php echo $carpeta_padre_id; ?>";
    </script>
    <?php
} else {
    // Si hubo un error al insertar, también iniciamos la sesión para mostrar un mensaje de error
    session_start();
    $_SESSION['mensaje'] = "Error al registrar";
    $_SESSION['icono'] = "error";
    ?>
    <script>
        location.href = "<?php echo $URL; ?>/unidad";
    </script>
    <?php
}
?>
