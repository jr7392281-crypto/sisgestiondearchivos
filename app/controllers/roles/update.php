<?php
include('../../config.php');
include('../../../layout/sesion.php');
proteger_admin();

$id_rol = isset($_POST['id_rol']) ? (int) $_POST['id_rol'] : 0;
$rol = trim((string) ($_POST['rol'] ?? ''));

if ($id_rol <= 0 || $rol === '') {
    session_start();
    $_SESSION['mensaje'] = "Datos de rol invalidos.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/roles/');
    exit();
}

$sentencia = $pdo->prepare("UPDATE tb_roles
        SET rol = :rol,
            updated_at = :updated_at
        WHERE id_rol = :id_rol");

$sentencia->bindParam(':rol', $rol, PDO::PARAM_STR);
$sentencia->bindParam(':updated_at', $fechaHora, PDO::PARAM_STR);
$sentencia->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);

if ($sentencia->execute()) {
    session_start();
    $_SESSION['mensaje'] = "Se actualizo el rol correctamente.";
    $_SESSION['icono'] = "success";
    header('Location:' . $URL . '/roles/');
    exit();
}

session_start();
$_SESSION['mensaje'] = "Error, no se pudo actualizar la base de datos.";
$_SESSION['icono'] = "error";
header('Location:' . $URL . '/roles/update.php?id=' . $id_rol);
exit();
?>
