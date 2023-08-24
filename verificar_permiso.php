<?php
//Condición para no poder abrir desde url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
    exit;
} else {
    //Verificar si existe la variable de session con los datos del usuario, creada en confirmar_iniciodesesion.php
    if (!isset($_SESSION['permiso']) || empty($_SESSION['permiso'])) {
        header("Location: home.php");
        exit;
    }

    // Acceder a los datos del usuario almacenados en la sesión
    unset($_SESSION['permiso']);
    
}

?>
