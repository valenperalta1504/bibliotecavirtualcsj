<?php
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); // Inicias la sesión
    
    // Destruye todas las session (incluyendo la del usuario)
    session_destroy();
    
    // Redirigir a la página de inicio de sesión 
    header("Location: iniciar_sesion.php");
    exit;
}
else {
    header("Location: home.php");
}
?>