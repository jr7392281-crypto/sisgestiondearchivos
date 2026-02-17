<?php
$carpeta_padre_id = $_GET['id'];

$sql_subcarpetas = "SELECT * FROM tb_carpetas 
                    WHERE carpeta_padre_id = :carpeta_padre_id
                    AND id_usuario = :id_usuario";

$query_subcarpetas = $pdo->prepare($sql_subcarpetas);
$query_subcarpetas->bindParam(':carpeta_padre_id', $carpeta_padre_id, PDO::PARAM_INT);
$query_subcarpetas->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query_subcarpetas->execute();

$subcarpetas_datos = $query_subcarpetas->fetchAll(PDO::FETCH_ASSOC);
?>
