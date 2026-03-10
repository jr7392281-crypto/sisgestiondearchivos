<?php
include('../app/config.php');
include('../layout/sesion.php');
proteger_admin();
include('../layout/parte1.php');
include('../app/controllers/roles/listado_de_roles.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Registro de un nuevo usuario</h1>
        </div><!-- /.col -->

      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">LLene los datos con cuidado</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display:block;">
              <div class="row">
                <div class="col-md-12">
                  <form action="../app/controllers/usuarios/create.php" method="post">
                    <div class="form-group">
                      <label for="">Nombres</label>
                      <input type="text" name="nombre" class="form-control" placeholder="Escriba aqui el nombre del nuevo usuario" required>
                    </div>
                    <div class="form-group">
                      <label for="">Email</label>
                      <input type="email" name="email" class="form-control" placeholder="Escriba aqui el correo del nuevo usuario" required>
                    </div>
                    <div class="form-group">
                      <label for="">Rol del usuario</label>
                      <select name="rol" id="" class="form-control">
                        <?php
                        foreach ($roles_datos as $roles_dato) { ?>
                          <option value="<?php echo $roles_dato['id_rol'];?>"><?php echo $roles_dato['rol']; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Contrasena</label>
                      <div class="input-group">
                        <input type="password" id="password_user" name="password_user" class="form-control" minlength="6" required>
                        <div class="input-group-append">
                          <span class="input-group-text text-muted" style="cursor:pointer;background:#fff;" onclick="togglePassword('password_user', this)">
                            <i class="fas fa-eye"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="">Repita la contrasena</label>
                      <div class="input-group">
                        <input type="password" id="password_repeat" name="password_repeat" class="form-control" minlength="6" required>
                        <div class="input-group-append">
                          <span class="input-group-text text-muted" style="cursor:pointer;background:#fff;" onclick="togglePassword('password_repeat', this)">
                            <i class="fas fa-eye"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="form-group">
                      <a href="index.php" class="btn btn-secondary">Cancelar</a>
                      <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
function togglePassword(id, btn) {
  var input = document.getElementById(id);
  var icon = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}
</script>
<?php
include('../layout/parte2.php');
?>
