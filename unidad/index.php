<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/carpeta/listado_carpetas.php');
?>
<div class="content-wrapper px-5">
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mi unidad</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                        data-target="#Modal-carpetas-padre">
                        <i class="fa fa-folder"></i> Nueva carpeta
                    </button>

                    <!-- Modal Crear carpetas -->
                    <div class="modal fade" id="Modal-carpetas-padre" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Nombre de la carpeta</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="../app/controllers/carpeta/create_carpeta.php" method="post">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="nombre" required>
                                                    <input type="text" name="id_usuario" value="<?php echo $id_usuario_sesion; ?>" hidden>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Crear</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </ol>
            </div>
        </div>
    </div>
    <!-- Carpetas -->
    <hr>
    <h5>Carpetas</h5>
    <div class="row">
        <?php foreach ($carpeta_datos as $carpeta_dato) {
            $id = $carpeta_dato['id_carpeta'];
            $nombre = $carpeta_dato['nombre'];
            $color = $carpeta_dato['color'];
            ?>
            <div class="col-md-3">
                <div style="background: white;border: 1px solid #c0c0c0;border-radius: 10px; margin-top: 15px"
                    data-toggle="tooltip" data-placement="bottom" title="<?php echo $nombre; ?>">
                    <div class="row" style="padding: 10px">
                        <div class="col-2" style="text-align: center">
                            <i class="bi bi-folder-fill"
                                style="font-size: 20pt; color:<?php echo $carpeta_dato['color']; ?>;"></i>
                        </div>
                        <div class="col-8" style="margin-top: 5px">
                            <a href="show.php?id=<?php echo $id; ?>" style="color: black;">
                                <h3><?php echo $nombre; ?></h3>
                            </a>
                        </div>
                        <!--- Opciones en carpeta -->
                        <div class="col-2" style="margin-top: 5px; text-align: right">
                            <div class="dropdown">
                                <button class="dropdown-toggle btn btn-light btn-sm" data-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu">
                                    <!-- Botón que abre el modal -->
                                    <button class="dropdown-item" data-toggle="modal"
                                        data-target="#Modal-cambio<?php echo $id; ?>">
                                        <i class="bi bi-pencil"></i>Cambiar de Nombre
                                    </button>
                                    <!--  más opciones aquí -->
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-gear"></i> Color de la carpeta
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <form action="../app/controllers/carpeta/update_color_carpeta.php"
                                                method="post">
                                                <input type="text" value="blue" name="color" hidden>
                                                <input type="text" value="<?php echo $id; ?>" name="id_carpeta" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: blue"></i>
                                                </button>
                                            </form>
                                            <form action="../app/controllers/carpeta/update_color_carpeta.php"
                                                method="post">
                                                <input type="text" value="green" name="color" hidden>
                                                <input type="text" value="<?php echo $id; ?>" name="id_carpeta" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: green"></i>
                                                </button>
                                            </form>
                                            <form action="../app/controllers/carpeta/update_color_carpeta.php"
                                                method="post">
                                                <input type="text" value="red" name="color" hidden>
                                                <input type="text" value="<?php echo $id; ?>" name="id_carpeta" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: red"></i>
                                                </button>
                                            </form>
                                            <form action="../app/controllers/carpeta/update_color_carpeta.php"
                                                method="post">
                                                <input type="text" value="yellow" name="color" hidden>
                                                <input type="text" value="<?php echo $id; ?>" name="id_carpeta" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: yellow"></i>
                                                </button>
                                            </form>
                                            <form action="../app/controllers/carpeta/update_color_carpeta.php"
                                                method="post">
                                                <input type="text" value="black" name="color" hidden>
                                                <input type="text" value="<?php echo $id; ?>" name="id_carpeta" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: black"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-trash"></i>Borrar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal por carpeta para cambiar de nombre-->
            <div class="modal fade" id="Modal-cambio<?php echo $id; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="../app/controllers/carpeta/rename_carpeta.php" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Cambiar nombre de carpeta</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_carpeta" value="<?php echo $id; ?>">
                                <input type="text" class="form-control" name="nuevo_nombre" value="<?php echo $nombre; ?>"
                                    required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Renombrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
include('../layout/mensajes.php');
include('../layout/parte2.php');
?>