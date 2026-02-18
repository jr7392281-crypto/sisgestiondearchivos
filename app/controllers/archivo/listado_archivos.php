<?php
$sql_archivos = "SELECT ar.*
                 FROM tb_archivos ar
                 INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
                 WHERE ar.id_carpeta = :id_carpeta
                 AND ca.id_usuario = :id_usuario";
$query_archivos = $pdo->prepare($sql_archivos);
$query_archivos->bindParam(':id_carpeta', $carpeta_padre_id, PDO::PARAM_INT);
$query_archivos->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query_archivos->execute();

$archivos_datos = $query_archivos->fetchAll(PDO::FETCH_ASSOC);
