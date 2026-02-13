<?php
$sql_archivos = "SELECT * FROM tb_archivos WHERE id_carpeta = :id_carpeta";
$query_archivos = $pdo->prepare($sql_archivos);
$query_archivos->bindParam(':id_carpeta', $carpeta_padre_id);
$query_archivos->execute();

$archivos_datos = $query_archivos->fetchAll(PDO::FETCH_ASSOC);