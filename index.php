<?php
include('app/config.php');
include('layout/sesion.php');
include('layout/parte1.php');
include('app/controllers/carpeta/listado_carpetas.php');
include('app/controllers/usuarios/listado_de_usuarios.php');
//include('app/controllers/roles/listado_de_roles.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Bienvenido al SISTEMA DE GESTION - <?php echo $rol_sesion; ?></h1>
        </div><!-- /.col -->

      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- /.content -->
  <div class="content">
    <div class="container-fluid">
      CONTENIDO DEL SISTEMA
      <br><br>

      <div class="row">
        <!-- Carpetas -->
        <div class="col-lg-3 col-6">
          <!-- small card -->
          <div class="small-box bg-warning">
            <div class="inner">
              <?php
              $contador_de_usuarios = 0;
              foreach ($usuarios_datos as $usuarios_dato) {
                $contador_de_usuarios = $contador_de_usuarios + 1;
              }
              ?>
              <H3><?php echo $contador_de_usuarios ?></H3>
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
        <!-- Fin de carpetas -->

        <!-- Carpetas -->
        <div class="col-lg-3 col-6">
          <!-- small card -->
          <div class="small-box bg-info">
            <div class="inner">
              <?php
              $contador_de_carpetas = 0;
              foreach ($carpeta_datos as $carpeta_dato) {
                $contador_de_carpetas = $contador_de_carpetas + 1;
              }
              ?>
              <H3><?php echo $contador_de_carpetas ?></H3>
              <p>Carpetas en el sistema</p>
            </div>
            <a href="<?php echo $URL; ?>/unidad">
              <div class="icon">
                <i class="fas fa-list"></i>
              </div>
            </a>
            <a href="<?php echo $URL; ?>/unidad" class="small-box-footer">
              Mas detalles <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- Fin de carpetas -->
      </div>
      <!-- /.content-wrapper -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<?php
include('layout/parte2.php');
?>