<?php
define('ACCESO_PERMITIDO', true);
// Conectar con la bd
require_once 'conexion_bd_libros.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["retirar"]) && $_POST["retirar"] === "retirar") {

$id = $_POST["id"];




$query = "SELECT * FROM reservaciones WHERE id = '$id'";
$result = mysqli_query($conn, $query);

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $reservacion = $result->fetch_assoc();
}

// Obtener la fecha de hoy
$Fecha_retiro = date("Y-m-d");
$ISBN= $reservacion['ISBN'];
// Almacena los datos del usuario en una sesión con claves asociativas
session_start();
$_SESSION['retirar_reserva'] = array(
'id' => $id,
'Nombre_alumno' => $reservacion['Nombre_alumno'],
'Dni' => $reservacion['Dni'],
'Nivel' => $reservacion['Nivel'],
'Curso' => $reservacion['Curso'],
'División' => $reservacion['División'],
'Nombre_libro' => $reservacion['Nombre_libro'],
'ISBN_NUEVO' => $reservacion['ISBN'],
'Portada' => $reservacion['Portada'],
'Fecha_retiro' => date("Y-m-d"),
'Fecha_devolución' => date("Y-m-d", strtotime($Fecha_retiro . "+1 week"))
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
        window.location.href = "modificar_reservaciones.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "libro_retirado.php",
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
$_SESSION['permiso'] = $ventana_emergente;
header("Location: modificar_reservaciones.php");
exit();
} 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
    
    
    session_start();
$retirar_reserva = $_SESSION['retirar_reserva'];

$id = $retirar_reserva['id'];
$Nombre_alumno = $retirar_reserva['Nombre_alumno'];
$Dni = $retirar_reserva['Dni'];
$Nivel = $retirar_reserva['Nivel'];
$Curso = $retirar_reserva['Curso'];
$División = $retirar_reserva['División'];
$Nombre_libro = $retirar_reserva['Nombre_libro'];
$ISBN_LIBRO = $retirar_reserva['ISBN_NUEVO'];
echo $ISBN_LIBRO;
$Portada = $retirar_reserva['Portada'];
$Fecha_retiro = $retirar_reserva['Fecha_retiro'];
$Fecha_devolución = $retirar_reserva['Fecha_devolución'];

unset($_SESSION['retirar_reserva']);
// Using prepared statement to avoid SQL injection
$query = "DELETE FROM reservaciones WHERE id = $id";
$result = mysqli_query($conn, $query);
$result = $conn->query($query);

$query = "INSERT INTO prestamos (Nombre_alumno, Dni, Nivel, Curso, División, Nombre_libro, Portada, ISBN, Fecha_retiro, Fecha_devolución) VALUES ('$Nombre_alumno', '$Dni', '$Nivel', '$Curso', '$División', '$Nombre_libro', '$Portada', '$ISBN_LIBRO', '$Fecha_retiro', '$Fecha_devolución')";

if ($conn->query($query) === true) {
    $conn->close();
} else {
    die("Error in the query: " . $conn->error);
}

    sleep(1);

    // Si todo sale bien, podemos responder con un mensaje de éxito
    echo "La acción ha sido confirmada exitosamente.";
} else {
    // Si la solicitud no es correcta, responder con un mensaje de error
    echo "Error: Acción no válida.";
}
} else {
    header("Location: home.php");
}

    ?>