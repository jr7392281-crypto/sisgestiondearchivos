<?php
include('app/config.php');
include('layout/sesion.php');
include('layout/parte1.php');
include('app/controllers/carpeta/listado_carpetas.php');
include('app/controllers/usuarios/listado_de_usuarios.php');
include('app/controllers/archivo/listado_total_archivos.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Bienvenido al SISTEMA DE GESTION - <?php echo $rol_sesion; ?></h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      CONTENIDO DEL SISTEMA
      <br><br>

      <div class="row">
        <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
          <!-- Usuarios (solo admin) -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                $contador_de_usuarios = 0;
                foreach ($usuarios_datos as $usuarios_dato) {
                  $contador_de_usuarios++;
                }
                ?>
                <h3><?php echo $contador_de_usuarios; ?></h3>
                <p>Usuarios Registrados</p>
              </div>
              <a href="<?php echo $URL; ?>/usuarios/create.php">
                <div class="icon">
                  <i class="fas fa-user-plus"></i>
                </div>
              </a>
              <a href="<?php echo $URL; ?>/usuarios" class="small-box-footer">
                Mas detalles <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
          <!-- Carpetas -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <?php
                $contador_de_carpetas = 0;
                foreach ($carpeta_datos as $carpeta_dato) {
                  $contador_de_carpetas++;
                }
                ?>
                <h3><?php echo $contador_de_carpetas; ?></h3>
                <p>Carpetas en el sistema</p>
              </div>
              <a href="<?php echo $URL; ?>/unidad">
                <div class="icon">
                  <i class="fas fa-folder"></i>
                </div>
              </a>
              <a href="<?php echo $URL; ?>/unidad" class="small-box-footer">
               <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- Fin de carpetas -->
        <?php endif; ?>
                <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
          <!-- Archivos -->
           <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <?php
                $contador_de_archivos = $archivos_datos['total'];
                ?>
                <h3><?php echo $contador_de_archivos; ?></h3>
                <p>Archivos en el sistema</p>
              </div>
              <a href="<?php echo $URL; ?>/unidad">
                <div class="icon">
                  <i class="fas fa-file"></i>
                </div>
              </a>
              <a href="<?php echo $URL; ?>/unidad" class="small-box-footer">
               <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- Fin de archivos -->
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php
include('layout/parte2.php');
?>
