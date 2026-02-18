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
                            acceptedFiles: ".jpg,.png,.pdf,.docx", // ext permitidas
                            autoProcessQueue: true,
                            dictDefaultMessage: "Arrastra tus archivos aquí o haz clic para subir"
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
                                <h3><?php echo $nombre; ?></h3>
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

    <table id="example1" class="table table-bordered table-striped able-responsive table-hover">
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
                        // Detectar tipo
                        if ($extension == 'jpg') { ?><img src="../public/images/iconos/icono_jpg.png" width="25"
                                alt=""><?php }
                        if ($extension == 'png') { ?><img src="../public/images/iconos/icono_png.png" width="25"
                                alt=""><?php }
                        if ($extension == 'pdf') { ?><img src="../public/images/iconos/icono_pdf.png" width="25"
                                alt=""><?php }
                        if ($extension == 'docx') { ?><img src="../public/images/iconos/icono_docx.png" width="25"
                                alt=""><?php }
                        ?>


                        <a href="" data-toggle="modal" data-target="#modal_visor<?php echo $id_archivo; ?>" style="color: black">
                            <?php echo $archivos_dato['nombre']; ?>
                        </a>

                            <?php if ($extension == 'jpg') { ?>
                             <!-- Modal -->
                              <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                               aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $archivos_dato['nombre']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="text-align: center">
                                    <img src="<?php echo $URL . '/storage/' . $carpeta_datos['id_carpeta'] . '/' . $archivos_dato['nombre']; ?>" 
                                     width="100%" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin del Modal Visor -->                                     
                        <?php }?>

                           <?php if ($extension == 'png') { ?>
                             <!-- Modal -->
                              <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                               aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $archivos_dato['nombre']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="text-align: center">
                                    <img src="<?php echo $URL . '/storage/' . $carpeta_datos['id_carpeta'] . '/' . $archivos_dato['nombre']; ?>" 
                                     width="100%" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin del Modal Visor -->                                     
                        <?php }?>

                        
                           <?php if ($extension == 'pdf') { ?>
                             <!-- Modal PDF -->
                              <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                               aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $archivos_dato['nombre']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="text-align: center">
                                    <iframe src="<?php echo $URL . '/storage/' . $carpeta_datos['id_carpeta'] . '/' . $archivos_dato['nombre']; ?>" 
                                     width="100%" height="500px" alt=""></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin del Modal Visor -->                                     
                        <?php }?>

                          <?php if ($extension == 'docx') { ?>
                             <!-- Modal PDF -->
                              <div class="modal fade" id="modal_visor<?php echo $id_archivo; ?>" tabindex="-1" role="dialog"
                               aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $archivos_dato['nombre']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="text-align: center">
                                    <img src="<?php echo $URL . "../public/images/iconos/icono_docx.png" ?>" 
                                     width="50%" alt=""><br>
                                     <a href="<?php echo $URL . '/storage/' . $carpeta_datos['id_carpeta'] . '/' . $archivos_dato['nombre']; ?>" class="btn btn-success">
                                     descargar
                                     </a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin del Modal Visor -->                                     
                        <?php }?>

                    </td>
                    <td> <?php echo $archivos_dato['created_at']; ?></td>
                    <td>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
include('../layout/mensajes.php');
include('../layout/parte2.php');
?>