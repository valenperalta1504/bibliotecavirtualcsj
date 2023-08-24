<?php
//Condición para no poder abrir desde url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
}
else {
// Verificar si existe un mensaje de error
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    // Eliminar el mensaje de error de la variable de sesión
    unset($_SESSION['error']);
}
}
?>