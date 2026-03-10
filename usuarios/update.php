<?php
include('../app/config.php');
include('../layout/sesion.php');
proteger_admin();
include('../layout/parte1.php');
include('../app/controllers/usuarios/update_usuario.php');
include('../app/controllers/roles/listado_de_roles.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Actualizar usuario</h1>
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
                    <div class="card card-success">
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

                                    <form action="../app/controllers/usuarios/update.php" method="post">
                                        <input type="text" name="id_usuario" value="<?php echo $id_usuario_get; ?>" hidden>
                                        <div class="form-group">
                                            <label for="">Nombres</label>
                                            <input type="text" name="nombre" class="form-control" value="<?php echo $nombres; ?>" placeholder="Escriba aqui el nombre del nuevo usuario" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Escriba aqui el correo del nuevo usuario" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Rol del usuario</label>
                                            <select name="rol" id="" class="form-control">
                                                <?php
                                                foreach ($roles_datos as $roles_dato) {
                                                    $rol_tabla = $roles_dato['rol'];
                                                    $id_rol = $roles_dato['id_rol']; ?>
                                                    <option value="<?php echo $id_rol; ?>" <?php if ($rol_tabla == $rol) { ?> selected="selected" <?php } ?>>
                                                        <?php echo $rol_tabla; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Contrasena</label>
                                            <div class="input-group">
                                                <input type="password" id="password_user" name="password_user" class="form-control" minlength="6" placeholder="Dejar vacio para no cambiar">
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
                                                <input type="password" id="password_repeat" name="password_repeat" class="form-control" minlength="6" placeholder="Repite solo si cambias">
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
                                            <button type="submit" class="btn btn-success">Actualizar</button>
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
include('../layout/mensajes.php');
include('../layout/parte2.php');
?>
