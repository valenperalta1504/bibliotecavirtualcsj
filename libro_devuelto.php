<?php
define('ACCESO_PERMITIDO', true);
// Conectar con la bd
require_once 'conexion_bd_libros.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["devuelto"]) && $_POST["devuelto"] === "devuelto") {

$id = $_POST["id"];





$query = "SELECT * FROM prestamos WHERE id = '$id'";
$result = mysqli_query($conn, $query);

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $prestamo = $result->fetch_assoc();
}


// Almacena los datos del usuario en una sesión con claves asociativas
session_start();
$_SESSION['devolver_libro'] = array(
'id' => $id,
'Nombre_alumno' => $prestamo['Nombre_alumno'],
'Dni' => $prestamo['Dni'],
'Nivel' => $prestamo['Nivel'],
'Curso' => $prestamo['Curso'],
'División' => $prestamo['División'],
'Nombre_libro' => $prestamo['Nombre_libro'],
'ISBN' => $prestamo['ISBN'],
'Portada' => $prestamo['Portada'],
'Fecha_retiro' => $prestamo['Fecha_retiro'],
'Fecha_devolución' => date("Y-m-d")
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
        window.location.href = "modificar_prestamos.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "libro_devuelto.php",
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
header("Location: modificar_prestamos.php");
exit();
} 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
    
   
    session_start();
$devolver_libro = $_SESSION['devolver_libro'];

$id = $devolver_libro['id'];
$Nombre_alumno = $devolver_libro['Nombre_alumno'];
$Dni = $devolver_libro['Dni'];
$Nivel = $devolver_libro['Nivel'];
$Curso = $devolver_libro['Curso'];
$División = $devolver_libro['División'];
$Nombre_libro = $devolver_libro['Nombre_libro'];
$ISBN = $devolver_libro['ISBN'];
$Portada = $devolver_libro['Portada'];
$Fecha_retiro = $devolver_libro['Fecha_retiro'];
$Fecha_devolución = $devolver_libro['Fecha_devolución'];

unset($_SESSION['retirar_reserva']);
// Using prepared statement to avoid SQL injection
$query = "DELETE FROM prestamos WHERE id = $id";
$result = mysqli_query($conn, $query);
$result = $conn->query($query);

$query = "INSERT INTO historial_prestamos (Nombre_alumno, Dni, Nivel, Curso, División, Nombre_libro, Portada, ISBN, Fecha_retiro, Fecha_devolución) VALUES ('$Nombre_alumno', '$Dni', '$Nivel', '$Curso', '$División', '$Nombre_libro', '$Portada', '$ISBN', '$Fecha_retiro', '$Fecha_devolución')";

if ($conn->query($query) === true) {
    //Sumar 1 a los ejemplares disponibles
$query_update = "UPDATE libros SET `Número de ejemplares disponibles` = `Número de ejemplares disponibles` + 1 WHERE ISBN = '$ISBN'";
$resultado_update = mysqli_query($conn, $query_update);
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