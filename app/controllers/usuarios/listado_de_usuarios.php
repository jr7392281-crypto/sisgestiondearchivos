<?php
// Consulta SQL que selecciona usuarios y su respectivo rol desde la base de datos.
// Se hace un INNER JOIN para unir las tablas 'users' y 'roles'.
$sql_usuarios = "SELECT 
    us.id_usuario as id_usuario,  -- ID del usuario
    us.nombre as nombre,          -- Nombre del usuario
    us.email as email,            -- Email del usuario
    rol.rol as rol                -- Nombre del rol (ej: Administrador, Usuario, etc.)
    FROM tb_users as us 
    INNER JOIN tb_roles as rol ON us.id_rol = rol.id_rol;"; // Unión con tabla de roles

// Preparamos la consulta con PDO
$query_usuarios = $pdo->prepare($sql_usuarios);

// Ejecutamos la consulta
$query_usuarios->execute();

// Obtenemos todos los resultados como un array asociativo
$usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);
?>