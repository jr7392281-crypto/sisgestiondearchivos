<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Gestion</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css?v=3.2.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <?php
        session_start();
        $token = isset($_GET['token']) ? trim($_GET['token']) : '';

        if ($token === '') {
            $_SESSION['mensaje'] = "Enlace invalido.";
            header('Location: index.php');
            exit();
        }

        if (isset($_SESSION['mensaje'])) {
            $respuesta = $_SESSION['mensaje'];
            $icono = isset($_SESSION['icono']) ? $_SESSION['icono'] : 'error'; ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "<?php echo $icono; ?>",
                    title: ' <?php echo $respuesta ?>',
                    showConfirmButton: false,
                    timer: 1800
                });
            </script>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['icono']);
        }
        ?>
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <b>Restablecer contrasena</b>
            </div>

            <div class="card-body">
                <p class="login-box-msg">Ingresa tu nueva contrasena</p>

                <form action="../app/controllers/login/update_password.php" method="post">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="input-group mb-3">
                        <input type="password" name="password_user" class="form-control" placeholder="Nueva contrasena"
                            required minlength="6">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password_confirm" class="form-control"
                            placeholder="Confirmar contrasena" required minlength="6">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Actualizar contrasena</button>
                </form>

                <hr>
                <p class="mb-0 text-center">
                    <a href="index.php">Volver al login</a>
                </p>
            </div>
        </div>
    </div>

    <script src="../public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <script src="../public/templates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../public/templates/AdminLTE-3.2.0/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>

</html>
