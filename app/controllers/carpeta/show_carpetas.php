<?php
$id_carpeta = $_GET['id'];

$sql_carpetas = "SELECT * FROM tb_carpetas 
                 WHERE id_carpeta = :id_carpeta 
                 AND id_usuario = :id_usuario";

$query_carpetas = $pdo->prepare($sql_carpetas);
$query_carpetas->bindParam(':id_carpeta', $id_carpeta, PDO::PARAM_INT);
$query_carpetas->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query_carpetas->execute();

$carpeta_datos = $query_carpetas->fetch(PDO::FETCH_ASSOC);

// Si no es su carpeta, redirige
if (!$carpeta_datos) {
    $_SESSION['mensaje'] = "No tienes permiso para ver esta carpeta";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/unidad');
    exit();
}
?>
