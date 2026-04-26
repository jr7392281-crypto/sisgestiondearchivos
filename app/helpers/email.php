<?php
function correo_smtp_configurado()
{
    return SMTP_HOST !== '' && SMTP_PORT > 0 && SMTP_USER !== '' && SMTP_PASS !== '' && SMTP_FROM_EMAIL !== '';
}

function smtp_leer_respuesta($conexion)
{
    $respuesta = '';

    while (!feof($conexion)) {
        $linea = fgets($conexion, 515);
        if ($linea === false) {
            break;
        }

        $respuesta .= $linea;

        if (isset($linea[3]) && $linea[3] === ' ') {
            break;
        }
    }

    return $respuesta;
}

function smtp_enviar_comando($conexion, $comando, $codigos_esperados, &$error)
{
    if ($comando !== null) {
        fwrite($conexion, $comando . "\r\n");
    }

    $respuesta = smtp_leer_respuesta($conexion);
    $codigo = (int) substr(trim($respuesta), 0, 3);

    if (!in_array($codigo, (array) $codigos_esperados, true)) {
        $error = trim($respuesta) !== '' ? trim($respuesta) : 'Sin respuesta del servidor SMTP.';
        return false;
    }

    return $respuesta;
}

function smtp_codificar_cabecera($texto)
{
    return '=?UTF-8?B?' . base64_encode($texto) . '?=';
}

function enviar_correo_smtp($para_email, $asunto, $html, &$error = '')
{
    if (!correo_smtp_configurado()) {
        $error = 'SMTP no configurado.';
        return false;
    }

    $host = SMTP_HOST;
    $puerto = (int) SMTP_PORT;
    $timeout = 20;
    $transporte = $puerto === 465 ? 'ssl://' : '';

    $conexion = @stream_socket_client(
        $transporte . $host . ':' . $puerto,
        $errno,
        $errstr,
        $timeout
    );

    if (!$conexion) {
        $error = 'No se pudo conectar al servidor SMTP: ' . $errstr . ' (' . $errno . ')';
        return false;
    }

    stream_set_timeout($conexion, $timeout);

    if (!smtp_enviar_comando($conexion, null, [220], $error)) {
        fclose($conexion);
        return false;
    }

    if (!smtp_enviar_comando($conexion, 'EHLO localhost', [250], $error)) {
        fclose($conexion);
        return false;
    }

    if ($puerto === 587) {
        if (!smtp_enviar_comando($conexion, 'STARTTLS', [220], $error)) {
            fclose($conexion);
            return false;
        }

        $tls = @stream_socket_enable_crypto($conexion, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        if ($tls !== true) {
            fclose($conexion);
            $error = 'No se pudo iniciar TLS con el servidor SMTP.';
            return false;
        }

        if (!smtp_enviar_comando($conexion, 'EHLO localhost', [250], $error)) {
            fclose($conexion);
            return false;
        }
    }

    if (!smtp_enviar_comando($conexion, 'AUTH LOGIN', [334], $error)) {
        fclose($conexion);
        return false;
    }

    if (!smtp_enviar_comando($conexion, base64_encode(SMTP_USER), [334], $error)) {
        fclose($conexion);
        return false;
    }

    if (!smtp_enviar_comando($conexion, base64_encode(SMTP_PASS), [235], $error)) {
        fclose($conexion);
        return false;
    }

    if (!smtp_enviar_comando($conexion, 'MAIL FROM: <' . SMTP_FROM_EMAIL . '>', [250], $error)) {
        fclose($conexion);
        return false;
    }

    if (!smtp_enviar_comando($conexion, 'RCPT TO: <' . $para_email . '>', [250, 251], $error)) {
        fclose($conexion);
        return false;
    }

    if (!smtp_enviar_comando($conexion, 'DATA', [354], $error)) {
        fclose($conexion);
        return false;
    }

    $headers = [];
    $headers[] = 'Date: ' . date(DATE_RFC2822);
    $headers[] = 'From: ' . smtp_codificar_cabecera(SMTP_FROM_NAME) . ' <' . SMTP_FROM_EMAIL . '>';
    $headers[] = 'Reply-To: ' . SMTP_FROM_EMAIL;
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'Content-Transfer-Encoding: 8bit';
    $headers[] = 'Subject: ' . smtp_codificar_cabecera($asunto);
    $headers[] = 'To: <' . $para_email . '>';

    $cuerpo = str_replace(["\r\n", "\r", "\n"], "\r\n", $html);
    $cuerpo = preg_replace('/^\./m', '..', $cuerpo);

    $mensaje = implode("\r\n", $headers) . "\r\n\r\n" . $cuerpo . "\r\n.";
    fwrite($conexion, $mensaje . "\r\n");

    if (!smtp_enviar_comando($conexion, null, [250], $error)) {
        fclose($conexion);
        return false;
    }

    smtp_enviar_comando($conexion, 'QUIT', [221], $error);
    fclose($conexion);

    return true;
}

function guardar_correo_debug($para_email, $asunto, $html, $directorio, &$ruta_guardada = '')
{
    $directorio = rtrim($directorio, "/\\");
    if ($directorio === '') {
        return false;
    }

    if (!is_dir($directorio) && !mkdir($directorio, 0777, true)) {
        return false;
    }

    $archivo = date('YmdHis') . '__' . preg_replace('/[^a-zA-Z0-9._@-]/', '_', $para_email) . '.html';
    $ruta_guardada = $directorio . '/' . $archivo;

    $contenido = "<h2>Correo de prueba</h2>\n";
    $contenido .= "<p><strong>Para:</strong> " . htmlspecialchars($para_email, ENT_QUOTES, 'UTF-8') . "</p>\n";
    $contenido .= "<p><strong>Asunto:</strong> " . htmlspecialchars($asunto, ENT_QUOTES, 'UTF-8') . "</p>\n";
    $contenido .= "<hr>\n";
    $contenido .= $html;

    return file_put_contents($ruta_guardada, $contenido) !== false;
}

function enviar_correo_o_guardar_debug($para_email, $asunto, $html, $directorio_debug, &$error = '', &$ruta_debug = '')
{
    $error = '';
    $ruta_debug = '';

    if (enviar_correo_smtp($para_email, $asunto, $html, $error)) {
        return true;
    }

    if (guardar_correo_debug($para_email, $asunto, $html, $directorio_debug, $ruta_debug)) {
        return true;
    }

    if ($error === '') {
        $error = 'No se pudo enviar ni guardar el correo.';
    }

    return false;
}
?>
