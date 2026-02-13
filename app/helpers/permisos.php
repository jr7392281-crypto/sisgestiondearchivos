<?php
function tiene_permiso($permiso) {
    return (isset($_SESSION['permisos']) && in_array($permiso, $_SESSION['permisos']));
}
?>
