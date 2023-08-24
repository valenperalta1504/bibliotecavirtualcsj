<?php

if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
    exit;
} else {
    if (!isset($_SESSION['MODIFICAR']) || empty($_SESSION['MODIFICAR'])) {
        header("Location: home.php");
        exit;
    }
    else {
        
    unset($_SESSION['MODIFICAR']);
}
}

?>
