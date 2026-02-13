<?php

$id_usuario_get = $_GET['id'];

// Armamos la consulta SQL para obtener los datos del usuario y su rol
$sql_usuarios = "SELECT us.id_usuario as id_usuario, us.nombre as nombre, us.email as email, rol.rol as rol 
    FROM  tb_users as us INNER JOIN tb_roles as rol 
    ON us.id_rol = rol.id_rol
    WHERE id_usuario = '$id_usuario_get'";
    
$query_usuarios = $pdo->prepare($sql_usuarios);
$query_usuarios->execute();

// Obtenemos todos los resultados como array asociativo
$usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);
// Recorremos los datos obtenidos
foreach ($usuarios_datos as $usuarios_dato) {
    $nombres = $usuarios_dato['nombre'];
    $email = $usuarios_dato['email'];
    $rol = $usuarios_dato['rol'];
}

?>