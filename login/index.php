<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Gestion</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet"
        href="../public/templates/templates/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css?v=3.2.0">
    <!-- Librería SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <?php
        session_start();
        if (isset($_SESSION['mensaje'])) {
            $respuesta = $_SESSION['mensaje'];
            $icono = isset($_SESSION['icono']) ? $_SESSION['icono'] : 'error'; ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "<?php echo $icono; ?>",
                    title: ' <?php echo $respuesta ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['icono']);
        }
        ?>
        <center>
            <img src="../public/images/logo.png" alt="" width="200px">
        </center>
        <br>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Ingrese sus Datos</p>

            <form action="../app/controllers/login/ingreso.php" method="post">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_user" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="forgot_password.php">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>

    </div>

    <script src="../../plugins/jquery/jquery.js"></script>

    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="../../dist/js/adminlte.min.js?v=3.2.0"></script>
</body>

</html>
