<?php
// Lista archivos solo de la carpeta del usuario y oculta los que estan en papelera.
$sql_archivos = "SELECT ar.*,
                        ec.token AS enlace_token
                 FROM tb_archivos ar
                 INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
                 LEFT JOIN tb_papelera_archivos pa ON pa.id_archivo = ar.id_archivos
                 LEFT JOIN tb_enlaces_compartidos ec ON ec.id_archivo = ar.id_archivos
                    AND ec.activo = 1
                    AND (ec.fecha_expiracion IS NULL OR ec.fecha_expiracion > NOW())
                 WHERE ar.id_carpeta = :id_carpeta
                 AND ca.id_usuario = :id_usuario
                 AND pa.id_papelera IS NULL";
$query_archivos = $pdo->prepare($sql_archivos);
$query_archivos->bindParam(':id_carpeta', $carpeta_padre_id);
$query_archivos->bindParam(':id_usuario', $id_usuario_sesion);
$query_archivos->execute();

$archivos_datos = $query_archivos->fetchAll(PDO::FETCH_ASSOC);
