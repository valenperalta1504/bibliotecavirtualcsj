<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar si el usuario ha iniciado sesión
require_once 'verificar_sesion.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Eliminar session y volver al catalogo
    unset($_SESSION['ISBN_LIBRO']);
    header("Location: catalogo.php");
exit;
}
else {
    header("Location: home.php");
}

?>
