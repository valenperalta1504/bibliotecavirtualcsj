<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar si el usuario ha iniciado sesión
require_once 'verificar_sesion.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';

//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cambiar"]) && $_POST["cambiar"] === "cambiar") { 
    $dni = $_SESSION['datos_usuario']['dni']; // Obtener el DNI del usuario de la sesión

    // Consultar la contraseña actual en la base de datos
    $query = "SELECT contrasena FROM registro WHERE dni = '$dni'";
    $resultado = mysqli_query($conn, $query);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $contrasena_actual_bd = $fila['contrasena'];

        
        $contrasena_actual_ingresada = $_POST['contrasena'];
        $contrasena_nueva = $_POST['contrasena_nueva'];
        $contrasena_nueva2 = $_POST['contrasena_nueva2'];
        //Verificar si hay campos vacíos
        if (empty($contrasena_actual_ingresada) || empty($contrasena_nueva) || empty($contrasena_nueva2)) {
            $error = '<p class="error-message">Debe completar todos los campos para efectuar el cambio</p>';
            $_SESSION['error'] = $error;
            header("Location: cambiar_contraseña.php");
            exit;
        }
        // Verificar si la contraseña actual coincide
        if (!password_verify($contrasena_actual_ingresada, $contrasena_actual_bd)) {
            $error = '<p class="error-message">La contraseña actual debe ser correcta para efectuar el cambio</p>';
            $_SESSION['error'] = $error;
            header("Location: cambiar_contraseña.php");
            exit;
        }
    } 
    
    // Verificar si las contraseñas nuevas coinciden
    if ($contrasena_nueva !== $contrasena_nueva2) {
        $error = '<p class="error-message">Las contraseñas nuevas deben coincidir para efectuar el cambio</p>';
        $_SESSION['error'] = $error;
        header("Location: cambiar_contraseña.php");
        exit;
    } else {
        // Encriptar la contraseña nueva
        $contrasena_nueva_encriptada = password_hash($contrasena_nueva, PASSWORD_DEFAULT);
    } 

    session_start();
    $_SESSION['contrasena_nueva'] = array(
    'contrasena_nueva_encriptada' => $contrasena_nueva_encriptada,
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
            <button onclick="Aceptar()">Aceptar</button>
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
        window.location.href = "mi_cuenta.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "actualizar_contraseña.php",
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
header("Location: cambiar_contraseña.php");
exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
    global $conn;
    session_start();
$contrasena_nueva = $_SESSION['contrasena_nueva'];

$contrasena_nueva_encriptada = $contrasena_nueva['contrasena_nueva_encriptada'];
$dni = $contrasena_nueva['dni'];

unset($_SESSION['contrasena_nueva']);

// Actualizar la contraseña en la base de datos
 $query_actualizar = "UPDATE registro SET contrasena = '$contrasena_nueva_encriptada' WHERE dni = '$dni'";
mysqli_query($conn, $query_actualizar);

sleep(1);
exit();
}
} else {
    header("Location: home.php");
}

// Cerrar la conexión a la base de datos
require_once 'cerrar_conexion_bd.php';
?>