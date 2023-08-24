<?php
session_start();
//Condición para no poder abrir desde url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
    exit;
} else {
    //Verificar si existe la variable de session con los datos del usuario, creada en confirmar_iniciodesesion.php
    if (!isset($_SESSION['datos_usuario']) || empty($_SESSION['datos_usuario'])) {
        header("Location: iniciar_sesion.php");
        exit;
    }

    // Acceder a los datos del usuario almacenados en la sesión
    $datos_usuario = $_SESSION['datos_usuario'];
    
}

?>
