<?php

// Conectar con la bd
require_once 'conexion_bd_libros.php';
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
    exit;
} 
else {
$dni=$datos_usuario['dni'];



$hoy = date("Y-m-d");

$query = "SELECT * FROM prestamos WHERE Dni = '$dni' AND Fecha_devolución <= '$hoy'";
$result = mysqli_query($conn, $query);

if ($result->num_rows > 0) {
    // Si el registro es exitoso, mostrar el msj de confirmación
$ventana_emergente = '<div id="miVentana" class="modal">
<div class="modal-content">
    <p>Usted tiene una devolución pendiente, recuerde devolverla en la biblioteca lo antes posible.</p> 
    <div class="boton-centro">
    <button onclick="cerrarVentana()">Aceptar</button>
           
            </div>
    
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    mostrarVentana();
});

function mostrarVentana() {
    document.getElementById("miVentana").style.display = "flex";
}

function cerrarVentana() {
    document.getElementById("miVentana").style.display = "none";
}
</script>';
$_SESSION['ventana_emergente'] = $ventana_emergente;
}
}
    ?>