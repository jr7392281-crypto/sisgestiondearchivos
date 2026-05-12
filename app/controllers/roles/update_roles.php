<?php
$id_rol_get = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_rol_get <= 0) {
    $_SESSION['mensaje'] = "Rol invalido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/roles/');
    exit();
}

$sql_roles = "SELECT id_rol, rol FROM tb_roles WHERE id_rol = :id_rol LIMIT 1";
$query_roles = $pdo->prepare($sql_roles);
$query_roles->bindParam(':id_rol', $id_rol_get, PDO::PARAM_INT);
$query_roles->execute();
$roles_dato = $query_roles->fetch(PDO::FETCH_ASSOC);

if (!$roles_dato) {
    $_SESSION['mensaje'] = "El rol no existe.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/roles/');
    exit();
}

$rol = $roles_dato['rol'];
?>
