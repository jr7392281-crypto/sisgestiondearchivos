<?php
// Se obtiene el ID del rol desde la URL (por método GET)
$id_rol_get = $_GET['id'];

// Se prepara la consulta SQL para obtener los datos del rol con ese ID
$sql_roles = "SELECT * FROM tb_roles WHERE id_rol = '$id_rol_get'";
$query_roles = $pdo->prepare($sql_roles);

// Se ejecuta la consulta
$query_roles->execute();

// Se obtienen los resultados como array asociativo
$roles_datos = $query_roles->fetchAll(PDO::FETCH_ASSOC);

// Se recorre el resultado (aunque solo debería haber un registro)
foreach($roles_datos as $roles_dato){
    $rol = $roles_dato['rol']; // Se guarda el nombre del rol en una variable
}
?>