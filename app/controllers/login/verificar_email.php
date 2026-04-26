<?php
include('../../config.php');
session_start();

$token = isset($_GET['token']) ? trim((string) $_GET['token']) : '';

if ($token === '') {
    $_SESSION['mensaje'] = "Enlace de verificacion invalido.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/index.php');
    exit();
}

$query = $pdo->prepare("SELECT id_usuario, created_at FROM tb_email_verification WHERE token = :token LIMIT 1");
$query->bindParam(':token', $token, PDO::PARAM_STR);
$query->execute();
$verificacion = $query->fetch(PDO::FETCH_ASSOC);

if (!$verificacion) {
    $_SESSION['mensaje'] = "El enlace de verificacion no es valido o ya fue utilizado.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/index.php');
    exit();
}

$created_at = strtotime((string) $verificacion['created_at']);
if ($created_at === false || (time() - $created_at) > 86400) {
    $delete_expirado = $pdo->prepare("DELETE FROM tb_email_verification WHERE token = :token");
    $delete_expirado->bindParam(':token', $token, PDO::PARAM_STR);
    $delete_expirado->execute();

    $_SESSION['mensaje'] = "El enlace de verificacion expiro. Intenta ingresar nuevamente para recibir otro.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/index.php');
    exit();
}

$id_usuario = (int) $verificacion['id_usuario'];

$pdo->beginTransaction();
try {
    $update = $pdo->prepare("UPDATE tb_users
                             SET email_verificado = 1,
                                 email_verificado_at = :email_verificado_at,
                                 updated_at = :updated_at
                             WHERE id_usuario = :id_usuario");
    $update->bindParam(':email_verificado_at', $fechaHora, PDO::PARAM_STR);
    $update->bindParam(':updated_at', $fechaHora, PDO::PARAM_STR);
    $update->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $update->execute();

    $delete = $pdo->prepare("DELETE FROM tb_email_verification WHERE id_usuario = :id_usuario");
    $delete->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $delete->execute();

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['mensaje'] = "No se pudo verificar el correo. Intenta nuevamente.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/index.php');
    exit();
}

$_SESSION['mensaje'] = "Correo verificado correctamente. Ya puedes iniciar sesion.";
$_SESSION['icono'] = "success";
header('Location:' . $URL . '/login/index.php');
exit();
?>
