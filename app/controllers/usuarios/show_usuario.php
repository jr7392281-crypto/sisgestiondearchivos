<?php

// Obtenemos el ID del usuario desde la URL (método GET), por ejemplo: edit.php?id=5
$id_usuario_get = $_GET['id'];

// Consulta SQL para obtener el usuario y su rol, haciendo un INNER JOIN entre users y roles
$sql_usuarios = "SELECT 
    us.id_usuario as id_usuario, 
    us.nombre as nombre, 
    us.email as email, 
    rol.rol as rol 
    FROM tb_users as us 
    INNER JOIN tb_roles as rol ON us.id_rol = rol.id_rol 
    WHERE us.id_usuario = :id";

// Preparamos la sentencia SQL
$query_usuarios = $pdo->prepare($sql_usuarios);

// Asociamos el valor del ID al parámetro `:id` de la consulta
$query_usuarios->bindParam(':id', $id_usuario_get);

// Ejecutamos la consulta
$query_usuarios->execute();

// Obtenemos los datos del usuario como array asociativo
$usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);

// Recorremos (aunque será solo uno) y guardamos en variables para usarlos en el HTML
foreach($usuarios_datos as $usuarios_dato){
    $nombres = $usuarios_dato['nombre'];  // Nombre del usuario
    $email = $usuarios_dato['email'];    // Correo
    $rol = $usuarios_dato['rol'];        // Nombre del rol (ej: Administrador)
}
?>
