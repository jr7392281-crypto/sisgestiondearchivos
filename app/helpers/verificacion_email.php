<?php
require_once(__DIR__ . '/email.php');

function generar_token_verificacion_email($pdo, $id_usuario, $fechaHora)
{
    $token = bin2hex(random_bytes(32));

    $pdo->beginTransaction();
    try {
        $delete = $pdo->prepare("DELETE FROM tb_email_verification WHERE id_usuario = :id_usuario");
        $delete->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $delete->execute();

        $insert = $pdo->prepare("INSERT INTO tb_email_verification (id_usuario, token, created_at) VALUES (:id_usuario, :token, :created_at)");
        $insert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $insert->bindParam(':token', $token, PDO::PARAM_STR);
        $insert->bindParam(':created_at', $fechaHora, PDO::PARAM_STR);
        $insert->execute();

        $pdo->commit();
        return $token;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function enviar_verificacion_email($pdo, $id_usuario, $email, $URL, $fechaHora, $directorio_debug, &$error = '', &$ruta_debug = '')
{
    $error = '';
    $ruta_debug = '';

    $token = generar_token_verificacion_email($pdo, $id_usuario, $fechaHora);
    if ($token === false) {
        $error = 'No se pudo generar el token de verificacion.';
        return false;
    }

    $link = $URL . '/app/controllers/login/verificar_email.php?token=' . urlencode($token);
    $link_seguro = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
    $asunto = 'Verifica tu correo';
    $cuerpo = '<p>Hola.</p>'
        . '<p>Antes de ingresar al sistema, necesitas verificar tu correo.</p>'
        . '<p><a href="' . $link_seguro . '">Haz clic aqui para verificar tu correo</a></p>'
        . '<p>Si no solicitaste esta cuenta, puedes ignorar este correo.</p>'
        . '<p>Este enlace vence en 24 horas.</p>';

    if (!enviar_correo_o_guardar_debug($email, $asunto, $cuerpo, $directorio_debug, $error, $ruta_debug)) {
        if ($error === '') {
            $error = 'No se pudo enviar el correo de verificacion.';
        }
        return false;
    }

    return true;
}
?>
