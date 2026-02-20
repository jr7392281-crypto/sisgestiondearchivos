<?php
include('../../config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . $URL . '/login/index.php');
    exit();
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$password = isset($_POST['password_user']) ? $_POST['password_user'] : '';
$password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

if ($token === '' || $password === '' || $password_confirm === '') {
    $_SESSION['mensaje'] = "Completa todos los campos.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/reset_password.php?token=' . urlencode($token));
    exit();
}

if ($password !== $password_confirm) {
    $_SESSION['mensaje'] = "Las contrasenas no coinciden.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/reset_password.php?token=' . urlencode($token));
    exit();
}

if (strlen($password) < 6) {
    $_SESSION['mensaje'] = "La contrasena debe tener al menos 6 caracteres.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/reset_password.php?token=' . urlencode($token));
    exit();
}

$sql_reset = "SELECT id_usuario, created_at FROM tb_password_reset WHERE token = :token LIMIT 1";
$query_reset = $pdo->prepare($sql_reset);
$query_reset->bindParam(':token', $token, PDO::PARAM_STR);
$query_reset->execute();
$reset = $query_reset->fetch(PDO::FETCH_ASSOC);

if (!$reset) {
    $_SESSION['mensaje'] = "Enlace invalido o ya utilizado.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

$created_at = strtotime($reset['created_at']);
if ($created_at === false || (time() - $created_at) > 1800) {
    $delete_token = $pdo->prepare("DELETE FROM tb_password_reset WHERE token = :token");
    $delete_token->bindParam(':token', $token, PDO::PARAM_STR);
    $delete_token->execute();

    $_SESSION['mensaje'] = "El enlace expiro. Solicita uno nuevo.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

$id_usuario = (int) $reset['id_usuario'];
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$pdo->beginTransaction();
try {
    $update = $pdo->prepare("UPDATE tb_users SET password_user = :password_user WHERE id_usuario = :id_usuario");
    $update->bindParam(':password_user', $password_hash, PDO::PARAM_STR);
    $update->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $update->execute();

    $delete_all_tokens = $pdo->prepare("DELETE FROM tb_password_reset WHERE id_usuario = :id_usuario");
    $delete_all_tokens->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $delete_all_tokens->execute();

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['mensaje'] = "No se pudo actualizar la contrasena.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

$_SESSION['mensaje'] = "Contrasena actualizada. Ya puedes iniciar sesion.";
$_SESSION['icono'] = "success";
header('Location:' . $URL . '/login/index.php');
exit();
?>
