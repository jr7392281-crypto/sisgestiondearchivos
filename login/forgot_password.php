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
            $respuesta = $_SESSION['mensaje']; ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: ' <?php echo $respuesta ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
            <?php
        }
        ?>
        <br>
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <b>Recuperar contraseña</b>
            </div>

            <div class="card-body">
                <p class="login-box-msg">
                    Ingresa tu email y te enviaremos un enlace
                </p>

                <form action="../app/controllers/login/send_reset.php" method="post">
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Enviar enlace
                    </button>
                </form>

                <hr>
                <p class="mb-0 text-center">
                    <a href="index.php">Volver al login</a>
                </p>
            </div>
        </div>
    </div>

    <script src="../../plugins/jquery/jquery.js"></script>

    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="../../dist/js/adminlte.min.js?v=3.2.0"></script>
</body>

</html>