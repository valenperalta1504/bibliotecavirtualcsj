<?php
session_start(); 
//Condición para no poder abrir desde la url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
}
else {
    //Condición para saber si ya tiene sesión iniciada
if (isset($_SESSION['datos_usuario']) && !empty($_SESSION['datos_usuario'])) {
    header("Location: home.php");
}
}
?>