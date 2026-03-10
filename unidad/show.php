<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/carpeta/show_carpetas.php');
include('../app/controllers/carpeta/show_subcarpetas.php');
include('../app/controllers/archivo/listado_archivos.php');

?>
<div class="content-wrapper px-5">
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo $carpeta_datos['nombre']; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <!-- Button para regresar-->
                    <a href="<?php echo $URL; ?>/unidad" class="btn btn-default"><i
                            class="bi bi-arrow-bar-left"></i>Volver</a>
                    <!-- Button para subir archivos -->
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#Modal-upload">
                        <i class="bi bi-cloud-upload-fill"></i> Subir archivo
                    </button>
                    <!-- Modal para subir un archivo-->
                    <!-- Modal Dropzone -->
                    <div class="modal fade" id="Modal-upload" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Subir archivo</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="../app/controllers/archivo/upload.php" method="post" class="dropzone"
                                        id="myDropzone" enctype="multipart/form-data">
                                        <input type="text" name="id_carpeta" value="<?php echo $_GET['id']; ?>" hidden>
                                        <div class="fallback">
                                            <input type="file" name="archivo" multiple />
                                        </div>
                                </div>
                                <!-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Crear</button>
                                      </div> -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                        Dropzone.options.myDropzone = {
                            paramName: "archivo",
                            acceptedFiles: ".jpg,.jpeg,.png,.webp,.pdf,.docx,.xlsx,.pptx,.mp4,.mp3", // ext permitidas
                            autoProcessQueue: true,
                            dictDefaultMessage: "Arrastra tus archivos aquí o haz clic para subir",
                            init: function () {
                                this.on("queuecomplete", function () {
                                    setTimeout(function () { location.reload(); }, 700);
                                });
                            }
                        };
                    </script>
                    <!-- Fin del modal -->

                    <!-- Button trigger modal de creacion de subcarpetas -->
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#Modal-carpetas">
                        <i class="fa fa-folder"></i> Nueva carpeta
                    </button>

                </ol>
            </div>
        </div>
    </div>
    <!-- Modal para crear subcarpetas -->
    <div class="modal fade" id="Modal-carpetas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nombra la carpeta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../app/controllers/carpeta/create_subcarpeta.php" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="nombre" required>
                                    <input type="text" name="carpeta_padre_id" value="<?php echo $_GET['id']; ?>"
                                        hidden>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Subcarpetas -->
    <hr>
    <h5>Carpetas y archivos</h5>
    <div class="row">
        <?php foreach ($subcarpetas_datos as $subcarpetas_dato) {
            $id = $subcarpetas_dato['id_carpeta'];
            $nombre = $subcarpetas_dato['nombre'];

            ?>
            <div class="col-md-3">
                <div style="background: white;border: 1px solid #c0c0c0;border-radius: 10px;  margin-top: 15px"
                    data-toggle="tooltip" data-placement="bottom" title="<?php echo $nombre; ?>">
                    <div class="row" style="padding: 10px">
                        <div class="col-2" style="text-align: center">
                            <i class="bi bi-folder-fill"
                                style="font-size: 20pt; color:<?php echo $subcarpetas_dato['color']; ?>;"></i>
                        </div>
                        <div class="col-8" style="margin-top: 5px">
                            <a href="show.php?id=<?php echo $id; ?>" style="color: black;">
                                <h3 class="text-truncate mb-0" style="max-width: 150px;">
                                    <?php echo $nombre; ?>
                                </h3>
                            </a>
                        </div>
                        <!--- Opciones en carpeta -->
                        <div class="col-2" style="margin-top: 5px; text-align: right">
                            <div>
                                <button class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <!-- Botón que abre el modal -->
                                    <button class="dropdown-item" data-toggle="modal"
                                        data-target="#Modal-cambio<?php echo $id; ?>">
                                        <i class="bi bi-pencil"></i> Cambiar de Nombre
                                    </button>
                                    <!--  más opciones aquí -->
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-gear"></i> Cambiar color de carpeta
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <form action="../app/controllers/carpeta/update_color_subcarpeta.php"
                                                method="post">
                                                <input type="text" value="blue" name="color" hidden>
                                                <input type="text" value="<?php echo $id ?>" name="id_carpeta" hidden>
                                                <input type="text" value="<?php echo $_GET['id']; ?>"
                                                    name="carpeta_padre_id" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: blue"></i>
                                                </button>
                                            </form>
                                            <form action="../app/controllers/carpeta/update_color_subcarpeta.php"
                                                method="post">
                                                <input type="text" value="green" name="color" hidden>
                                                <input type="text" value="<?php echo $id ?>" name="id_carpeta" hidden>
                                                <input type="text" value="<?php echo $_GET['id']; ?>"
                                                    name="carpeta_padre_id" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: green"></i>
                                                </button>
                                            </form>
                                            <form action="../app/controllers/carpeta/update_color_subcarpeta.php"
                                                method="post">
                                                <input type="text" value="red" name="color" hidden>
                                                <input type="text" value="<?php echo $id ?>" name="id_carpeta" hidden>
                                                <input type="text" value="<?php echo $_GET['id']; ?>"
                                                    name="carpeta_padre_id" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: red"></i>
                                                </button>
                                            </form>
                                            <form action="../app/controllers/carpeta/update_color_subcarpeta.php"
                                                method="post">
                                                <input type="text" value="yellow" name="color" hidden>
                                                <input type="text" value="<?php echo $id ?>" name="id_carpeta" hidden>
                                                <input type="text" value="<?php echo $_GET['id']; ?>"
                                                    name="carpeta_padre_id" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: yellow"></i>
                                                </button>
                                            </form>
                                            <form action="../app/controllers/carpeta/update_color_subcarpeta.php"
                                                method="post">
                                                <input type="text" value="black" name="color" hidden>
                                                <input type="text" value="<?php echo $id ?>" name="id_carpeta" hidden>
                                                <input type="text" value="<?php echo $_GET['id']; ?>"
                                                    name="carpeta_padre_id" hidden>
                                                <button type="submit" style="background-color: white;border: 0px">
                                                    <i class="bi bi-circle-fill" style="color: black"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </a>
                                    <form action="../app/controllers/carpeta/delete_subcarpeta.php" method="post">
                                        <input type="text" name="id_carpeta" value="<?php echo $id; ?>" hidden>
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-trash"></i>Borrar
                                        </button>
                                    </form>
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
                        <form action="../app/controllers/carpeta/rename_subcarpeta.php" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Cambiar nombre de carpeta</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_carpeta" value="<?php echo $id; ?>">
                                <input type="hidden" name="carpeta_padre_id" value="<?php echo $_GET['id']; ?>">
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

    <hr>
    <div class="table-responsive">
        <table id="example1" class="table table-hover mb-0">
         <thead>
            <tr>
                <th>
                    <center>Nro</center>
                </th>
                <th>
                    <center>Nombre</center>
                </th>
                <th>
                    <center>Fecha</center>
                </th>
                <th>
                    <center>Acciones</center>
                </th>
            </tr>
         </thead>
         <tbody>
            <?php
            $contador = 0;
            foreach ($archivos_datos as $archivos_dato) {
                $id_archivo = $archivos_dato['id_archivos'];
                $nombre_archivo = $archivos_dato['nombre'];
                $estado_archivo = $archivos_dato['estado_archivo'];
                $url_compartir = $URL . '/app/controllers/archivo/ver_publico.php?id=' . $id_archivo;
                $url_compartir_descarga = $url_compartir . '&descargar=1';

                ?>
                <tr>
                    <td>
                        <center><?php echo $contador = $contador + 1; ?> </center>
                    </td>
                    <td>
                        <?php
                        $nombre_archivo = $archivos_dato['nombre'];
                        // Obtener extensión
                        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
                        $url_archivo_privado = $URL . '/app/controllers/archivo/ver_archivo.php?id=' . $id_archivo;
                        $url_archivo = ($estado_archivo == 'publico') ? $url_compartir : $url_archivo_privado;
                        $url_descarga = $url_archivo . '&descargar=1';
                        // Detectar tipo
                        if ($extension == 'jpg') { ?><img src="../public/images/iconos/icono_jpg.png" width="25"
                                alt=""><?php }
                        if ($extension == 'jpeg') { ?><img src="../public/images/iconos/icono_jpeg.png" width="25"
                                alt=""><?php }
                        if ($extension == 'png') { ?><img src="../public/images/iconos/icono_png.png" width="25"
                                alt=""><?php }
                        if ($extension == 'webp') { ?><img src="../public/images/iconos/icono_webp.png" width="25"
                                alt=""><?php }
                        if ($extension == 'pdf') { ?><img src="../public/images/iconos/icono_pdf.png" width="25"
                                alt=""><?php }
                        if ($extension == 'docx') { ?><img src="../public/images/iconos/icono_docx.png" width="25"
                                alt=""><?php }
                        if ($extension == 'xlsx') { ?><img src="../public/images/iconos/icono_excel.png" width="25"
                                alt=""><?php }
                        if ($extension == 'pptx') { ?><img
                                src="../public/images/iconos/icono_power.png" width="25" alt=""><?php }
                        if ($extension == 'mp4') { ?><img src="../public/images/iconos/icono_video.png" width="25"
                                alt=""><?php }
                        if ($extension == 'mp3') { ?><img src="../public/images/iconos/icono_mp3.png" width="25"
                                alt=""><?php }
                        ?>

                        <a href="#" data-toggle="modal" data-target="#modal_visor<?php echo $id_archivo; ?>"
                            style="color: black">
                            <?php echo $archivos_dato['nombre']; ?>
                        </a>

                        <?php if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'webp' || $extension == 'png') { ?>
                            <!-- Modal JPG-->
                            <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-truncate pr-4" id="exampleModalLabel"
                                                style="max-width: 90%;">
                                                <?php echo $nombre_archivo; ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="text-align: center;">
                                            <div style="height:55vh; display:flex; align-items:center; justify-content:center;">
                                                <img src="<?php echo $url_archivo; ?>" style="max-width:100%; max-height:100%; object-fit:contain;" alt="">
                                            </div>
                                            <br><br>
                                            <a href="<?php echo $url_descarga; ?>" class="btn btn-success">
                                                descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del Modal Visor -->
                        <?php } ?>



                        <?php if ($extension == 'docx') { ?>
                            <!-- Modal Word -->
                            <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-truncate pr-4" id="exampleModalLabel"
                                                style="max-width: 90%;">
                                                <?php echo $nombre_archivo; ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="text-align: center">
                                            <img src="<?php echo $URL . "/public/images/iconos/icono_docx.png" ?>" width="50%"
                                                alt=""><br>
                                            <a href="<?php echo $url_descarga; ?>" class="btn btn-success">
                                                descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del Modal Visor -->
                        <?php } ?>

                        <?php if ($extension == 'pptx') { ?>
                            <!-- Modal PowerPoint -->
                            <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-truncate pr-4" id="exampleModalLabel"
                                                style="max-width: 90%;">
                                                <?php echo $nombre_archivo; ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="text-align: center">
                                            <img src="<?php echo $URL . "/public/images/iconos/icono_power.png" ?>" width="50%"
                                                alt=""><br>
                                            <a href="<?php echo $url_descarga; ?>" class="btn btn-success">
                                                descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del Modal Visor -->
                        <?php } ?>

                        <?php if ($extension == 'xlsx') { ?>
                            <!-- Modal Excel -->
                            <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-truncate pr-4" id="exampleModalLabel"
                                                style="max-width: 90%;">
                                                <?php echo $nombre_archivo; ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="text-align: center">
                                            <img src="<?php echo $URL . "/public/images/iconos/icono_excel.png" ?>" width="50%"
                                                alt=""><br>
                                            <a href="<?php echo $url_descarga; ?>" class="btn btn-success">
                                                descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del Modal Visor -->
                        <?php } ?>

                        <?php if ($extension == 'pdf') { ?>
                            <!-- Modal PDF -->
                            <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-truncate pr-4" id="exampleModalLabel"
                                                style="max-width: 90%;">
                                                <?php echo $nombre_archivo; ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="text-align: center">
                                            <iframe src="<?php echo $url_archivo; ?>" width="100%" height="500px"
                                                alt=""></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del Modal Visor -->
                        <?php } ?>

                        <?php if ($extension == 'mp4') { ?>
                            <!-- Modal Video -->
                            <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-truncate pr-4" id="exampleModalLabel"
                                                style="max-width: 90%;">
                                                <?php echo $nombre_archivo; ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="text-align: center">
                                            <video id="my-video<?php echo $id_archivo; ?>" style="width: 100%;" height="500px"
                                                class="video-js" controls preload="auto" data-setup="{}">
                                                <source src="<?php echo $url_archivo; ?>"
                                                    type="video/<?php echo $extension; ?>">
                                            </video>
                                            <br><br>
                                            <a href="<?php echo $url_descarga; ?>" class="btn btn-success">
                                                descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del Modal Visor -->
                        <?php } ?>

                        <?php if ($extension == 'mp3') { ?>
                            <!-- Modal Audio -->
                            <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-truncate pr-4" id="exampleModalLabel"
                                                style="max-width: 90%;">
                                                <?php echo $nombre_archivo; ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="text-align: center">
                                            <audio id="my-audio<?php echo $id_archivo; ?>" style="width: 100%;" height="500px"
                                                controls>
                                                <source src="<?php echo $url_archivo; ?>" type="audio/mpeg">
                                            </audio>
                                            <br><br>
                                            <a href="<?php echo $url_descarga; ?>" class="btn btn-success">
                                                descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del Modal Visor -->
                        <?php } ?>
                    </td>
                    <td> <?php echo $archivos_dato['created_at']; ?></td>
                    <!-- Boton para eliminar archivos -->
                    <td>

                        <div class="btn-group" role="group" aria-label="Basic example">


                            <form action="../app/controllers/archivo/delete_archivos.php" method="post"
                                class="form-delete-archivo">
                                <input type="text" value="<?php echo $id_archivo; ?>" name="id" hidden>
                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            <!-- Fin del boton -->

                            <!-- Boton para compartir archivos -->
                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                data-target="#modal_compartir<?php echo $id_archivo; ?>"><i
                                    class="bi bi-share-fill"></i></button>
                            <!-- Modal -->
                            <div class="modal fade" id="modal_compartir<?php echo $id_archivo; ?>" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Compartir archivo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <p style="overflow-wrap:anywhere;word-break:break-word;">
                                                <?php echo $archivos_dato['nombre']; ?>
                                            </p>
                                            <?php if ($estado_archivo == 'privado') { ?>
                                                <b> Este archivo es privado</b> <br>
                                                <form action="../app/controllers/archivo/cambiar_estado.php" method="post">
                                                    <input type="text" name="id" value="<?php echo $id_archivo; ?>" hidden>
                                                    <input type="text" name="estado" value="publico" hidden>
                                                    <button type="submit" class="btn btn-success">Cambiar a publico</button>
                                                </form>
                                            <?php } else { ?>
                                                <b> Este archivo es publico</b> <br>
                                                <form action="../app/controllers/archivo/cambiar_estado.php" method="post">
                                                    <input type="text" name="id" value="<?php echo $id_archivo; ?>" hidden>
                                                    <input type="text" name="estado" value="privado" hidden>
                                                    <button type="submit" class="btn btn-primary">Cambiar a privado</button>
                                                </form>
                                                <hr>
                                                <input type="text" class="form-control" id="link_<?php echo $id_archivo; ?>"
                                                    value="<?php echo $url_compartir_descarga; ?>" readonly>
                                                <br>
                                                <button type="button" class="btn btn-outline-primary"
                                                    onclick="copiarEnlace('link_<?php echo $id_archivo; ?>')">Copiar
                                                    enlace</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del boton -->
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('submit', function (e) {
        var form = e.target.closest('.form-delete-archivo');
        if (!form) return;

        e.preventDefault();
        Swal.fire({
            icon: 'question',
            title: 'Eliminar archivo',
            text: 'Desea eliminar este archivo?',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            iconColor: '#d33'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<script>
    function copiarEnlace(idInput) {
        var input = document.getElementById(idInput);
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        Swal.fire({
            icon: 'success',
            title: 'Enlace copiado',
            text: 'Ya puedes compartirlo',
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>

<script>
    $(document).on('hidden.bs.modal', '.modal', function () {
        var media = $(this).find('video, audio');
        media.each(function () {
            this.pause();
            this.currentTime = 0;
        });
    });
</script>

<script>
    $(function () {
        $('#example1').DataTable({
            "pageLength": 10,
            "searching": false,
            "lengthChange": false,
            "info": false,
            "ordering": false,
            "responsive": true,
            "autoWidth": false,
            "dom": 'tp',
            language: {
                emptyTable: "No hay informacion",
                paginate: {
                    previous: "Anterior",
                    next: "Siguiente"
                }
            }
        });
    });
</script>
<?php
include('../layout/mensajes.php');
include('../layout/parte2.php');
?>
