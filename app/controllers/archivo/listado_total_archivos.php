<?php
$sql_archivos = "SELECT COUNT(*) as total FROM tb_archivos";
$query_archivos = $pdo->prepare($sql_archivos);
$query_archivos->execute();
$archivos_datos = $query_archivos->fetch(PDO::FETCH_ASSOC);
?>
