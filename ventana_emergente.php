<?php
//Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
}
else {

// Verificar si existe uns ventana emergente
if (isset($_SESSION['ventana_emergente'])) {
    $ventana_emergente = $_SESSION['ventana_emergente'];
    // Eliminar el contenido para poder reutilizarla
    unset($_SESSION['ventana_emergente']);
}

}
?>