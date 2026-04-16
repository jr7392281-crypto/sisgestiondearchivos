<?php
include('../../config.php');
session_start();

// Este controlador solo debe recibir datos por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

// Recibimos el email del formulario
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validamos que no llegue vacio
if ($email === '') {
    $_SESSION['mensaje'] = "Debes ingresar un email.";
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

// Buscamos al usuario por email
$sql_usuario = "SELECT id_usuario FROM tb_users WHERE email = :email LIMIT 1";
$query_usuario = $pdo->prepare($sql_usuario);
$query_usuario->bindParam(':email', $email, PDO::PARAM_STR);
$query_usuario->execute();
$usuario = $query_usuario->fetch(PDO::FETCH_ASSOC);

// Si no existe, mostramos mensaje general para no revelar datos del sistema
if (!$usuario) {
    $_SESSION['mensaje'] = "Si el email existe, recibira un enlace de recuperacion.";
    $_SESSION['icono'] = "success";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

// Si existe, creamos un token nuevo
$id_usuario = (int) $usuario['id_usuario'];
$token = bin2hex(random_bytes(32));

// Guardamos el token en BD (reemplazando tokens anteriores de ese usuario)
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
    $_SESSION['icono'] = "error";
    header('Location:' . $URL . '/login/forgot_password.php');
    exit();
}

// Redirigimos al formulario de nueva contrasena con el token
/*
Si quieres enviar el enlace por Gmail (opcional), puedes usar PHPMailer.
Dejo el bloque completo comentado para activarlo cuando lo decidas.

PASOS:
1) Instalar PHPMailer (composer require phpmailer/phpmailer)
2) Activar verificacion en dos pasos en Gmail y crear App Password
3) Completar usuario y password de la app

// require '../../vendor/autoload.php';
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
//
// $link = $URL . '/login/reset_password.php?token=' . urlencode($token);
//
// $mail = new PHPMailer(true);
// $mail->isSMTP();
// $mail->Host = 'smtp.gmail.com';
// $mail->SMTPAuth = true;
// $mail->Username = 'tu_correo@gmail.com';
// $mail->Password = 'TU_APP_PASSWORD';
// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
// $mail->Port = 587;
//
// $mail->setFrom('tu_correo@gmail.com', 'Sistema');
// $mail->addAddress($email);
//
// $mail->isHTML(true);
// $mail->Subject = 'Recuperar contrasena';
// $mail->Body = 'Tu enlace: <a href="' . $link . '">Recuperar</a>';
//
// $mail->send();
*/
header('Location:' . $URL . '/login/reset_password.php?token=' . urlencode($token));
exit();
?>
