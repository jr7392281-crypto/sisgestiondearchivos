<?php
$sql_carpetas = "SELECT * FROM tb_carpetas
                 WHERE carpeta_padre_id IS NULL
                 AND id_usuario = :id_usuario";

$query_carpetas = $pdo->prepare($sql_carpetas);
$query_carpetas->bindParam(':id_usuario', $id_usuario_sesion);
$query_carpetas->execute();

$carpeta_datos = $query_carpetas->fetchAll(PDO::FETCH_ASSOC);
?>