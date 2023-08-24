<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// Verificar la sesión del usuario
require_once 'prestamo_no_devuelto.php';
// Verificar la sesión del usuario
require_once 'ventana_emergente.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';
// Verificar la sesión del usuario
require_once 'conexion_bd_libros.php';


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/png" href="/logo6.png"/>
    <title>Biblioteca Virtual</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style18.css">
  
</head>
<body>
    <div class="container"> 
        <nav>
            <ul>
                <?php
                $resultado=mostrarChat();
                mostrar_menu_home($_SESSION['es_admin'], $resultado);
                ?>
            </ul>
        </nav>         
        <header>
		<div class="container-menu">
        <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José" class="logo">
    </div>
        </header>
    </div>    
    <main>
        <div class="container7">
            <?php
            require_once 'galeria.php';
            ?>
        </div>
    </main>
    <footer>
        <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
    </footer>

    <?php
    if (isset($ventana_emergente)) {
        echo $ventana_emergente;
    }

    if (!isset($_SESSION['es_admin']) || empty($_SESSION['es_admin'])) {

    }
    else {
        $query_seleccionar = "SELECT * FROM eliminados";
    $resultado_seleccionar = mysqli_query($conn, $query_seleccionar);
    
    // Verificar si hay resultados
    if (mysqli_num_rows($resultado_seleccionar) > 0) {
        // Recorrer los resultados uno por uno
        if (mysqli_num_rows($resultado_seleccionar) > 0) {
    
            if (mysqli_num_rows($resultado_seleccionar) > 0) {
                // Recorrer los resultados uno por uno
                echo '<div id="miVentana" class="modal">';
                echo '<div class="modal-content">';
                echo '<p id="mensajeContenido"></p>';
                echo '<div class="boton-centro">';
                echo '<button onclick="mostrarSiguienteMensaje()">Siguiente</button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            
                echo '<script>';
                echo 'var mensajes = ' . json_encode(mysqli_fetch_all($resultado_seleccionar, MYSQLI_ASSOC)) . ';';
                echo 'var indiceMensajeActual = 0;';
            
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo 'mostrarVentana();';
                echo '});';
            
                echo 'function mostrarVentana() {';
                echo 'document.getElementById("miVentana").style.display = "flex";';
                echo 'mostrarSiguienteMensaje();'; // Mostrar el primer mensaje al cargar la ventana
                echo '}';
            
                echo 'function cerrarVentana() {';
                echo 'document.getElementById("miVentana").style.display = "none";';
                echo '}';
            
                echo 'function mostrarSiguienteMensaje() {';
                echo 'if (indiceMensajeActual < mensajes.length) {';
                echo 'var mensaje = mensajes[indiceMensajeActual].mensaje;';
                echo 'document.getElementById("mensajeContenido").textContent = mensaje;';
                echo 'indiceMensajeActual++;';
                echo '} else {';
                echo 'cerrarVentana();';
                echo '}';
                echo '}';
                echo '</script>';
            }
        }
    
    
            // Consulta para eliminar la entrada específica por su ID
            $query_eliminar = "DELETE FROM eliminados";
            $resultado_eliminar = mysqli_query($conn, $query_eliminar);
    
        
        
    } 
    }
    ?>

</body>
</html>
