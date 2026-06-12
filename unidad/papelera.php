<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');

$sql = "SELECT ar.id_archivos, ar.nombre, ar.tipo, pa.fecha_eliminacion
        FROM tb_papelera_archivos pa
        INNER JOIN tb_archivos ar ON ar.id_archivos = pa.id_archivo
        INNER JOIN tb_carpetas ca ON ca.id_carpeta = ar.id_carpeta
        WHERE ca.id_usuario = :id_usuario
        ORDER BY pa.fecha_eliminacion DESC";
$query = $pdo->prepare($sql);
$query->execute([':id_usuario' => $id_usuario_sesion]);
$archivos_papelera = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper px-5">
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Papelera</h1>
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
                    <th>Fecha de eliminacion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($archivos_papelera)) { ?>
                    <tr>
                        <td colspan="4" class="text-center">La papelera esta vacia.</td>
                    </tr>
                <?php } ?>
                <?php
                $contador = 0;
                foreach ($archivos_papelera as $archivo) {
                    $contador++;
                    $id_archivo = (int) $archivo['id_archivos'];
                    ?>
                    <tr>
                        <td><?php echo $contador; ?></td>
                        <td><?php echo e($archivo['nombre']); ?></td>
                        <td><?php echo e($archivo['fecha_eliminacion']); ?></td>
                        <td>
                            <form action="../app/controllers/archivo/restaurar_archivo.php" method="post" style="display:inline-block;">
                                <input type="text" name="id" value="<?php echo $id_archivo; ?>" hidden>
                                <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                            </form>
                            <form action="../app/controllers/archivo/eliminar_definitivo.php" method="post" class="form-delete-definitivo" style="display:inline-block;">
                                <input type="text" name="id" value="<?php echo $id_archivo; ?>" hidden>
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar definitivo</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('submit', function (e) {
        var form = e.target.closest('.form-delete-definitivo');
        if (!form) return;

        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Eliminar definitivamente',
            text: 'Esta accion no se puede deshacer.',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<?php
include('../layout/mensajes.php');
include('../layout/parte2.php');
?>
