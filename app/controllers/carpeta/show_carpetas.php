<?php
// Obtenemos el ID de la carpeta desde la URL (por ejemplo: edit.php?id=3)
$id_carpeta = $_GET['id'];

// Preparamos la consulta para obtener los datos de una carpeta específica según su ID
$sql_carpetas = "SELECT * FROM tb_carpetas WHERE id_carpeta = :id_carpeta";

// Preparamos la sentencia con PDO
$query_carpetas = $pdo->prepare($sql_carpetas);

// Enlazamos el valor del ID a la consulta preparada
$query_carpetas->bindParam(':id_carpeta', $id_carpeta);

// Ejecutamos la consulta
$query_carpetas->execute();

// Obtenemos el resultado como un array asociativo con los datos de la carpeta (nombre, fecha, etc.)
$carpeta_datos = $query_carpetas->fetch(PDO::FETCH_ASSOC);
?>
