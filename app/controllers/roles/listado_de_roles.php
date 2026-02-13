<?php
// Consulta SQL para obtener todos los registros de la tabla 'roles'
$sql_roles = "SELECT * FROM tb_roles";

// Se prepara la consulta usando PDO
$query_roles = $pdo->prepare($sql_roles);

// Se ejecuta la consulta
$query_roles->execute();

// Se obtienen todos los resultados como un arreglo asociativo
$roles_datos = $query_roles->fetchAll(PDO::FETCH_ASSOC);
?>
