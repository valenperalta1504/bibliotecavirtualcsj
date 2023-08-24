<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Conectar con la bd
require_once 'conexion_bd_libros.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registrarse"]) && $_POST["registrarse"] === "registrarse") {
    // Obtener los datos del formulario
    $nombre_completo = $_POST["nombre"];
    $contrasena = $_POST["contrasena"];
    $contrasena2 = $_POST["confirmar-contrasena"];
    $email = $_POST["email"];
    $nivel = $_POST["nivel"];
    $dni = $_POST["dni"];
    $curso = $_POST["curso"];
    $division = $_POST["division"];
    $usuario = $_POST["usu"];

if ($nivel=="Secundario"||$nivel=="Primario"||$nivel=="Inicial"){
    if (empty($division) || empty($curso) ){
        $error = '<p class="error-message">Debe completar todos los campos para efectuar el registro</p>';
        session_start();
        $_SESSION['error'] = $error;
        header("Location: registrarse.php");
        exit;
}
}
//Verificar campos vacíos
if (empty($nombre_completo) || empty($contrasena) || empty($contrasena2) || empty($nivel) || empty($dni) || empty($usuario)) {
    $error = '<p class="error-message">Debe completar todos los campos para efectuar el registro</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: registrarse.php");
    exit;
}

// Verificar si ya existe un usuario con el mismo nombre de usuario
$query = "SELECT * FROM registro WHERE usuario = '$usuario'";
$resultado = mysqli_query($conn, $query);
if (mysqli_num_rows($resultado) > 0) {
    $error = '<p class="error-message">El nombre de usuario ya está en uso. Por favor, elija otro.</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: registrarse.php");
    exit;
}

// Verificar si ya existe un usuario con el mismo DNI
$query = "SELECT * FROM registro WHERE dni = '$dni'";
$resultado = mysqli_query($conn, $query);
if (mysqli_num_rows($resultado) > 0) {
    $error = '<p class="error-message">Ese DNI ya está registrado.</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: registrarse.php");
    exit;
}

// Verificar si ya existe un usuario con el mismo email
$query = "SELECT * FROM registro WHERE email = '$email'";
$resultado = mysqli_query($conn, $query);
if (!empty($email)&&mysqli_num_rows($resultado) > 0) {
    $error = '<p class="error-message">Ese email ya está registrado.</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: registrarse.php");
    exit;
}

if ($contrasena != $contrasena2) {
	$error = '<p class="error-message">Las contraseñas deben coincidir para poder efectuar el registro</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: registrarse.php");
    exit;
}

// Verificar si el correo electrónico tiene un formato válido
if (!empty($email)&&!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = '<p class="error-message">El correo electrónico no tiene un formato válido</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: registrarse.php");
    exit;
}

// Encriptar la contraseña
$contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

// Almacena los datos del usuario en una sesión con claves asociativas
session_start();
$_SESSION['registro'] = array(
'nombre_completo' => $nombre_completo,
'contrasenaEncriptada' => $contrasenaEncriptada,
'email' => $email,
'nivel' => $nivel,
'curso' => $curso,
'division' => $division,
'dni' => $dni,
'usuario' => $usuario
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
        window.location.href = "modificar_registros.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "guardar_registro2.php",
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
header("Location: alta_registros.php");
exit();

}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
  
    session_start();
$registro = $_SESSION['registro'];

$nombre_completo = $registro['nombre_completo'];
$contrasenaEncriptada = $registro['contrasenaEncriptada'];
$email = $registro['email'];
$nivel = $registro['nivel'];
$curso = $registro['curso'];
$division = $registro['division'];
$usuario = $registro['usuario'];
$dni = $registro['dni'];

unset($_SESSION['registro']);

    // Insertar los datos en la tabla de registro
$query = "INSERT INTO registro (nombre_completo, contrasena, email, nivel, dni, curso, division, usuario) VALUES ('$nombre_completo', '$contrasenaEncriptada', '$email', '$nivel', '$dni','$curso', '$division', '$usuario')";
$resultado = mysqli_query($conn, $query);


// Verificar si la consulta fue exitosa
if (!$resultado) {
	die("Error al guardar los datos: " . mysqli_error($conn));
}
sleep(1);
exit;
}
}
else {
    header("Location: home.php");
}

?>