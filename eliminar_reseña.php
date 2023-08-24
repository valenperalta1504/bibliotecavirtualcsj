<?php
define('ACCESO_PERMITIDO', true);
// Conexión con la base de datos
require_once 'conexion_bd_libros.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar"]) && $_POST["eliminar"] === "eliminar") {
  $ISBN = $_POST['ISBN'];
  $dni = $_POST['dni'];


    session_start();
    $_SESSION['reseña_eliminar'] = array(
    'ISBN' => $ISBN,
    'dni' => $dni
);


    
    // Si el registro es exitoso, mostrar el mensaje de confirmación
$ventana_emergente = '<body 
onload="mostrarVentana()"> <!-- Invocamos la función mostrarVentana() al cargar la página -->
    <!-- Contenido de la página -->


    <!-- Ventana emergente -->
    <div id="miVentana" class="modal">
        <div class="modal-content">
            <p>¿Está seguro de confirmar?</p>
            <button onclick="confirmarAccion()">Confirmar</button>
            <button onclick="cerrarVentana()">Cancelar</button>
        </div>
    </div>

    <!-- Ventana emergente de éxito -->
    <div id="exitoVentana" class="modal">
        <div class="modal-content">
            <p>La acción se realizó con éxito.</p>
            <div class="boton-centro">
            <button onclick="Aceptar()">Aceptar</button>
            </div>
        </div>
    </div>

    <!-- Ventana emergente de carga -->
    <div id="cargandoVentana" class="modal">
        <div class="modal-content">
            <p>Procesando la acción...</p>
        </div>
    </div>

    <!-- jQuery (asegúrate de incluirlo antes del script) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    function mostrarVentana() {
        document.getElementById("miVentana").style.display = "flex";
    }

    function cerrarVentana() {
        document.getElementById("miVentana").style.display = "none";
    }

    function Aceptar() {
        window.location.href = "reservar.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "eliminar_reseña.php",
            type: "POST",
            data: { accion: "confirmar" },
            success: function(response) {
                console.log(response); // Ver la respuesta del servidor en la consola
                cerrarVentana(); // Cerrar la ventana emergente actual
                ocultarVentanaCargando(); // Ocultar la ventana de carga después de recibir la respuesta exitosa
                mostrarExitoVentana(); // Mostrar la ventana de éxito
            },
            error: function() {
                console.error("Ha ocurrido un error al confirmar la acción.");
                cerrarVentana();
                ocultarVentanaCargando(); // Ocultar la ventana de carga en caso de error también
            }
        });
    }

    function mostrarExitoVentana() {
        document.getElementById("exitoVentana").style.display = "flex";
    }

    function cerrarExitoVentana() {
        document.getElementById("exitoVentana").style.display = "none";
    }

    function mostrarVentanaCargando() {
        document.getElementById("cargandoVentana").style.display = "flex";
    }

    function ocultarVentanaCargando() {
        document.getElementById("cargandoVentana").style.display = "none";
    }
    </script>
</body>';
session_start();
$_SESSION['ventana_emergente'] = $ventana_emergente;
$_SESSION['RESERVA'] = $ventana_emergente;
header("Location: reservar.php");
exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
    global $conn;
    session_start();
    $reseña_eliminar = $_SESSION['reseña_eliminar'];
    
    $ISBN = $reseña_eliminar['ISBN'];
    $dni = $reseña_eliminar['dni'];
    
    $query = "DELETE FROM reseñas WHERE ISBN = '$ISBN' AND Dni = '$dni'";
    
    if ($conn->query($query) === TRUE) {
    echo "bien";
    } else {
    echo "Error en la consulta: " . $conn->error;
    }
sleep(1);

}
}
?>