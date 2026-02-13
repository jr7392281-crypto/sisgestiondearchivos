<?php
include('../../config.php');
$email = $_POST['email'];
$password_user = $_POST['password_user'];
$contador=0;

$sql="SELECT * FROM tb_users WHERE email='$email'";
$query=$pdo->prepare($sql);
$query->execute();
$usuarios=$query->fetchAll(PDO::FETCH_ASSOC);

foreach($usuarios as $usuario){
    $contador=$contador +1;
   $email =$usuario['email'];
   $nombres =$usuario['nombre'];
   $password_user_tb =$usuario['password_user'];
   $id_rol = $usuario['id_rol'];
}
if(($contador>0)&& password_verify($password_user,$password_user_tb)){
    //echo"Datos correctos";
    session_start();
    $_SESSION['sesion_email']=$email;
    // Guardar el rol del usuario en la sesión (1=Admin, 2=Usuario)
    $_SESSION['id_rol'] = $id_rol;

    // Guardar permisos del usuario en la sesión
    $sentencia = $pdo->prepare("SELECT nombre_permiso FROM tb_permision WHERE id_rol = :id_rol");
    $sentencia->bindParam(':id_rol', $id_rol);
    $sentencia->execute();
    $permisos = $sentencia->fetchAll(PDO::FETCH_COLUMN);
    $_SESSION['permisos'] = $permisos;

    header('location:'.$URL.'/index.php');
}else{
    echo "Datos Incorrectos, vuelve a intentarlo";
    session_start();
    $_SESSION['mensaje'] = "Error Datos Incorrectos";
    header('location:'.$URL.'/login');
}