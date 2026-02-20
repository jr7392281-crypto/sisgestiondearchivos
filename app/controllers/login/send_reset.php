<?php
include('../../config.php');
session_start();

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if ($email === '') {
    $_SESSION['mensaje'] = "Debes ingresar un email.";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

$sql_usuario = "SELECT id_usuario FROM tb_users WHERE email = :email LIMIT 1";
$query_usuario = $pdo->prepare($sql_usuario);
$query_usuario->bindParam(':email', $email, PDO::PARAM_STR);
$query_usuario->execute();
$usuario = $query_usuario->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['mensaje'] = "Si el email existe, recibira un enlace de recuperacion.";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

$id_usuario = (int) $usuario['id_usuario'];
$token = bin2hex(random_bytes(32));

$pdo->beginTransaction();
try {
    $delete = $pdo->prepare("DELETE FROM tb_password_reset WHERE id_usuario = :id_usuario");
    $delete->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $delete->execute();

    $insert = $pdo->prepare("INSERT INTO tb_password_reset (id_usuario, token, created_at) VALUES (:id_usuario, :token, :created_at)");
    $insert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $insert->bindParam(':token', $token, PDO::PARAM_STR);
    $insert->bindParam(':created_at', $fechaHora, PDO::PARAM_STR);
    $insert->execute();

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['mensaje'] = "No se pudo generar el enlace. Intenta nuevamente.";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

header('Location:' . $URL . '/login/reset_password.php?token=' . urlencode($token));
exit();
?>
