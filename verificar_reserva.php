<?php
//Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
    exit;
} else {
    //Verifica que exista la session que permite la entrada a la pag de reservar.php
    if (!isset($_SESSION['RESERVA']) || empty($_SESSION['RESERVA'])) {
        header("Location: home.php");
        exit;
    }
    else {
    //Elimina la session para impedir que entren por la url
    unset($_SESSION['RESERVA']);
}
}

?>
