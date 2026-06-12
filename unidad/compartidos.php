<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');

$sql = "SELECT ar.id_archivos, ar.nombre, ar.tipo, ar.created_at,
               us.nombre AS usuario_origen,
               ac.permiso
        FROM tb_archivos_compartidos ac
        INNER JOIN tb_archivos ar ON ar.id_archivos = ac.id_archivo
        INNER JOIN tb_users us ON us.id_usuario = ac.id_usuario_origen
        LEFT JOIN tb_papelera_archivos pa ON pa.id_archivo = ar.id_archivos
        WHERE ac.id_usuario_destino = :id_usuario
          AND pa.id_papelera IS NULL
        ORDER BY ac.created_at DESC";
$query = $pdo->prepare($sql);
$query->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
$query->execute();
$archivos_compartidos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper px-5">
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Compartidos conmigo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <a href="<?php echo $URL; ?>/unidad" class="btn btn-default">
                        <i class="bi bi-arrow-bar-left"></i> Volver
                    </a>
                </ol>
            </div>
        </div>
    </div>

    <hr>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Archivo</th>
                    <th>Compartido por</th>
                    <th>Fecha</th>
                    <th>Permiso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($archivos_compartidos)) { ?>
                    <tr>
                        <td colspan="6" class="text-center">No tienes archivos compartidos.</td>
                    </tr>
                <?php } ?>
                <?php
                $contador = 0;
                foreach ($archivos_compartidos as $archivo) {
                    $contador++;
                    $id_archivo = (int) $archivo['id_archivos'];
                    $tipo_archivo = strtolower($archivo['tipo']);
                    $url_ver = $URL . '/app/controllers/archivo/ver_archivo.php?id=' . $id_archivo;
                    $url_descargar = $url_ver . '&descargar=1';

                    $se_puede_ver = false;
                    if ($tipo_archivo == 'jpg' || $tipo_archivo == 'jpeg' || $tipo_archivo == 'png' || $tipo_archivo == 'webp') {
                        $se_puede_ver = true;
                    }
                    if ($tipo_archivo == 'pdf' || $tipo_archivo == 'mp4' || $tipo_archivo == 'mp3') {
                        $se_puede_ver = true;
                    }
                    ?>
                    <tr>
                        <td><?php echo $contador; ?></td>
                        <td><?php echo e($archivo['nombre']); ?></td>
                        <td><?php echo e($archivo['usuario_origen']); ?></td>
                        <td><?php echo e($archivo['created_at']); ?></td>
                        <td>
                            <?php
                            if ($archivo['permiso'] == 'descargar') {
                                echo 'Ver y descargar';
                            } else {
                                echo 'Solo ver';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($se_puede_ver) { ?>
                                <a href="<?php echo e($url_ver); ?>" class="btn btn-primary btn-sm" target="_blank">
                                    Ver
                                </a>
                                <?php if ($archivo['permiso'] == 'descargar') { ?>
                                    <a href="<?php echo e($url_descargar); ?>" class="btn btn-success btn-sm">
                                        Descargar
                                    </a>
                                <?php } ?>
                            <?php } else { ?>
                                <a href="<?php echo e($url_descargar); ?>" class="btn btn-warning btn-sm">
                                    Descargar para abrir
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include('../layout/mensajes.php');
include('../layout/parte2.php');
?>
