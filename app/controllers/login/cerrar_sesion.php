<?php
include('../../config.php');
session_start();

// Vaciar TODAS las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir al login
header('Location: ' . $URL . '/login');
exit;
