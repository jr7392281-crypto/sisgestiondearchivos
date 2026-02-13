<?php
// Obtenemos el ID de la carpeta padre desde la URL (por ejemplo: unidad/show.php?id=5)
$carpeta_padre_id = $_GET['id'];

// Preparamos la consulta SQL para obtener todas las subcarpetas que pertenecen a esa carpeta padre
$sql_subcarpetas = "SELECT * FROM tb_carpetas WHERE carpeta_padre_id = :carpeta_padre_id";

// Preparamos la sentencia con PDO
$query_subcarpetas = $pdo->prepare($sql_subcarpetas);

// Asociamos el valor del ID de la carpeta padre al parámetro de la consulta
$query_subcarpetas->bindParam(':carpeta_padre_id', $carpeta_padre_id);

// Ejecutamos la consulta
$query_subcarpetas->execute();

// Obtenemos todas las subcarpetas encontradas y las almacenamos en un array asociativo
$subcarpetas_datos = $query_subcarpetas->fetchAll(mode: PDO::FETCH_ASSOC);
?>
