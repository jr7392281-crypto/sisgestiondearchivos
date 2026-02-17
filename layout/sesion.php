<?php
session_start();
if (isset($_SESSION['sesion_email'])) {
    $email_sesion = $_SESSION['sesion_email'];

    $sql = "SELECT us.id_usuario as id_usuario, us.nombre as nombre, us.email as email, us.id_rol as id_rol, rol.rol as rol
    FROM tb_users as us INNER JOIN tb_roles as rol ON us.id_rol = rol.id_rol WHERE email = :email";
    $query = $pdo->prepare($sql);
    $query->bindParam(':email', $email_sesion);
    $query->execute();
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as $usuario) {
        $id_usuario_sesion = $usuario['id_usuario'];
        $nombres_sesion = $usuario['nombre'];
        $rol_sesion = $usuario['rol'];
        $id_rol_sesion = $usuario['id_rol'];
        $_SESSION['id_rol'] = $id_rol_sesion;
    }

    function proteger_admin()
    {
        global $URL;
        if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
            $_SESSION['mensaje'] = "Acceso denegado: se requieren permisos de administrador";
            header('location:' . $URL . '/index.php');
            exit();
        }
    }
} else {
    echo "No existe sesión";
    header('location:' . $URL . '/login');
}
?>
