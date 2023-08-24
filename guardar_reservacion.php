<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar si el usuario ha iniciado sesión
require_once 'verificar_sesion.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';

//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reserva"]) && $_POST["reserva"] === "reserva") {
// Obtener los datos del formulario
$fecha = $_POST["fecha"];

//Veridicar si los campos están completos
if (empty($fecha)) {
    $error = '<p class="error-message">Debe completar todos los campos para efectuar la reservación</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['RESERVA'] = $error;
    header("Location: reservar.php");
    exit;
}


session_start();
//Seleccionar el libro de la bd
$ISBN = $_SESSION['ISBN_LIBRO'];
$sql = "SELECT * FROM libros WHERE `ISBN` = $ISBN";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
$Nombre_libro = (isset($row["Título"]) ? $row["Título"] : "Título no disponible");
$Portada = (isset($row["Portada"]) ? $row["Portada"] : "not_found.jpg");
}}

//Extraer datos del usuario
$Nombre_alumno = $datos_usuario['nombre_completo'];
$Dni = $datos_usuario['dni'];
$Nivel = $datos_usuario['nivel'];
$Curso = $datos_usuario['curso'];
$División = $datos_usuario['division'];

// Almacena los datos del usuario en una sesión con claves asociativas
session_start();
$_SESSION['reserva_nueva'] = array(
'Nombre_alumno' => $Nombre_alumno,
'Dni' => $Dni,
'Nivel' => $Nivel,
'Curso' => $Curso,
'División' => $División,
'Nombre_libro' => $Nombre_libro,
'Portada' => $Portada,
'ISBN' => $ISBN,
'fecha' => $fecha
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
        window.location.href = "catalogo.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "guardar_reservacion.php",
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
   
    session_start();
$reserva_nueva = $_SESSION['reserva_nueva'];

$Nombre_alumno = $reserva_nueva['Nombre_alumno'];
$Dni = $reserva_nueva['Dni'];
$Nivel = $reserva_nueva['Nivel'];
$Curso = $reserva_nueva['Curso'];
$División = $reserva_nueva['División'];
$ISBN = $reserva_nueva['ISBN'];
$Nombre_libro = $reserva_nueva['Nombre_libro'];
$Portada = $reserva_nueva['Portada'];
$fecha = $reserva_nueva['fecha'];

unset($_SESSION['reserva_nueva']);

// Restar 1 al número de ejemplares disponibles del libro en la base de datos
$query_update = "UPDATE libros SET `Número de ejemplares disponibles` = `Número de ejemplares disponibles` - 1 WHERE ISBN = '$ISBN'";
$resultado_update = mysqli_query($conn, $query_update);

// Verificar si la consulta de actualización fue exitosa
if (!$resultado_update) {
    die("Error al actualizar los ejemplares disponibles: " . mysqli_error($conn));
}

$estado="nuevo";

// Insertar los datos en la tabla de reservaciones
$query_insert = "INSERT INTO reservaciones (Dni, Nombre_alumno, Nivel, Curso, División, ISBN, Nombre_libro, Portada, Fecha, Estado) VALUES ('$Dni', '$Nombre_alumno', '$Nivel', '$Curso', '$División', '$ISBN', '$Nombre_libro', '$Portada', '$fecha', '$estado')";
$resultado_insert = mysqli_query($conn, $query_insert);

// Verificar si la consulta de inserción fue exitosa
if (!$resultado_insert) {
    die("Error al guardar los datos: " . mysqli_error($conn));
}

// Cerrar la conexión a la base de datos
require_once 'cerrar_conexion_bd.php';
unset($_SESSION['ISBN_LIBRO']);
sleep(1);
}
}
else {
    header("Location: home.php");
}

?>
