<?php
//Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
}
else {
    //Verificar si inició sesión el admin
    if (isset($_SESSION['es_admin']) && !empty($_SESSION['es_admin'])) {
        header("Location: home.php");
        exit;
    }

    // Acceder a los datos del usuario almacenados en la sesión
    $datos_admin = $_SESSION['es_admin'];
}
?>