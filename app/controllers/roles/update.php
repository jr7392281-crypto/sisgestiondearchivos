<?php
include('../../config.php'); // Incluye la conexión a la base de datos y variables globales
include('../../../layout/sesion.php');
proteger_admin();

$id_rol = $_POST['id_rol']; // ID del rol a actualizar
$rol = $_POST['rol'];       // Nuevo nombre del rol

// Se prepara la consulta para actualizar el nombre del rol y la fecha de modificación
$sentencia = $pdo->prepare("UPDATE tb_roles 
        SET rol = :rol,
            updated_at = :updated_at
        WHERE id_rol = :id_rol");

// Se enlazan los valores a los parámetros
$sentencia->bindParam('rol', $rol);
$sentencia->bindParam('updated_at', $fechaHora);
$sentencia->bindParam('id_rol', $id_rol);

// Se ejecuta la actualización y se verifica el resultado
if ($sentencia->execute()) {
    session_start();
    $_SESSION['mensaje'] = "Se actualizó el rol de la manera correcta";
    $_SESSION['icono'] = "success";
    header('Location:' . $URL . '/roles/');
} else {
    // Si ocurre un error, se muestra un mensaje y se redirige al formulario de edición
    session_start();
    $_SESSION['mensaje'] = "Error, no se pudo actualizar la base de datos";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/roles/update.php?id=' . $id_rol);
}
