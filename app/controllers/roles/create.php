<?php
include('../../config.php'); // Conexión a la base de datos y variables globales
include('../../../layout/sesion.php');
proteger_admin();

$rol = $_POST['rol']; // Se obtiene el rol enviado desde el formulario

// Se prepara la consulta para insertar el nuevo rol con la fecha actual
$sentencia = $pdo->prepare("INSERT INTO tb_roles 
        (rol, created_at)
VALUES (:rol, :created_at)");

$sentencia->bindParam('rol', $rol);
$sentencia->bindParam('created_at', $fechaHora);

// Si se ejecuta correctamente, muestra mensaje de éxito y redirige
if ($sentencia->execute()){
    session_start();
    $_SESSION['mensaje'] = "Se registró el rol de forma correcta";
    $_SESSION['icono'] = "success";
    header('Location:' . $URL . '/roles/');
}else{
    // Si ocurre un error, muestra mensaje de error y regresa al formulario
    session_start();
    $_SESSION['mensaje'] = "Error, no se pudo registrar en la base de datos";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/roles/create.php');
}
?>
