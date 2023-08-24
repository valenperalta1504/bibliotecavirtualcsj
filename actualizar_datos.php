<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar si el usuario ha iniciado sesión
require_once 'verificar_sesion.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';

//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["modificar"]) && $_POST["modificar"] === "modificar") {
// Obtener los datos del formulario
$nombre_completo = $_POST['nombre_completo'];
$email = $_POST['email'];
$nivel = $_POST['nivel']; 
$curso = $_POST['curso'];
$division = $_POST['division'];
$dni = $_POST['dni'];
$usuario = $_POST['usuario'];

if ($nivel=="Secundario"||$nivel=="Primario"||$nivel=="Inicial"){
    if (empty($division) || empty($curso) ){
        $error = '<p class="error-message">Debe completar todos los campos para efectuar el registro</p>';
        session_start();
        $_SESSION['error'] = $error;
        header("Location: modificar_datos.php");
        exit;
}
}

// Verificar si el correo electrónico tiene un formato válido
if (!empty($email)&&!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = '<p class="error-message">El correo electrónico no tiene un formato válido</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: modificar_datos.php");
    exit;
}

if (empty($nombre_completo) || empty($nivel) || empty($dni) || empty($usuario)) {
    $error = '<p class="error-message">Debe completar todos los campos para efectuar el registro</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: modificar_datos.php");
    exit;
}

//Obetener datos actuales 
$usuario_actual = $datos_usuario['usuario'];
$dni_actual = $datos_usuario['dni'];
$email_actual = $datos_usuario['email'];

//Buscar usuario en el registro
$sql = "SELECT * FROM registro WHERE usuario = '$usuario'";
$result = $conn->query($sql);

$sql2 = "SELECT * FROM registro WHERE dni = '$dni'";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM registro WHERE email = '$email'";
$result3 = $conn->query($sql3);
//Verificar que no se repita el nombre de usuario
if ($usuario != $usuario_actual && $result && $result->num_rows > 0) {
    $error = '<p class="error-message">Ese nombre de usuario ya está registrado. Prueba elegir otro.</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: modificar_datos.php");
    exit;
    //Verificar que no se repita el dni
} elseif ($dni != $dni_actual && $result2 && $result2->num_rows > 0) {
    $error = '<p class="error-message">Ese DNI ya está registrado. Prueba elegir otro.</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: modificar_datos.php");
    exit;
}
elseif (!empty($email)&&$email != $email_actual && $result3 && $result3->num_rows > 0) {
    $error = '<p class="error-message">Ese email ya está registrado. Prueba elegir otro.</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: modificar_datos.php");
    exit;
}
else {
//Obtener dni anterior
$dni_viejo = $datos_usuario['dni']; 
$query = "SELECT id FROM registro WHERE dni = '$dni_viejo'";
$resultado = mysqli_query($conn, $query);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $id = $row['id'];
}


session_start();
$_SESSION['datos_nuevos'] = array(
'nombre_completo' => $nombre_completo,
'email' => $email,
'nivel' => $nivel,
'curso' => $curso,
'division' => $division,
'dni' => $dni,
'usuario' => $usuario,
'id' => $id,
'dni_viejo' => $dni_viejo
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
        window.location.href = "mi_cuenta.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "actualizar_datos.php",
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
header("Location: modificar_datos.php");
exit();
}
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {

global $conn;
    session_start();
$datos_nuevos = $_SESSION['datos_nuevos'];

$nombre_completo = $datos_nuevos['nombre_completo'];
$email = $datos_nuevos['email'];
$nivel = $datos_nuevos['nivel'];
$curso = $datos_nuevos['curso'];
$division = $datos_nuevos['division'];
$dni = $datos_nuevos['dni'];
$usuario = $datos_nuevos['usuario'];
$id = $datos_nuevos['id'];
$dni_viejo = $datos_nuevos['dni_viejo'];

unset($_SESSION['datos_nuevos']);

// Actualizar los datos del usuario en la base de datos
$sql = "UPDATE reseñas SET Nombre_alumno='$nombre_completo', Dni='$dni' WHERE Dni='$dni_viejo'";
echo "hola";
if ($conn->query($sql) === TRUE) {
    // Actualizar los datos del usuario en la base de datos
    $sql = "UPDATE registro SET nombre_completo='$nombre_completo', email='$email', nivel='$nivel', curso='$curso', division='$division', dni='$dni', usuario='$usuario' WHERE id=$id";
    echo "hola";
    if ($conn->query($sql) === TRUE) {

        $sql = "UPDATE reservaciones SET Nombre_alumno = ?, Nivel = ?, Curso = ?, División = ?, Dni = ? WHERE Dni = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
          $stmt->bind_param("ssssss", $nombre_completo, $nivel, $curso, $division, $dni, $dni_viejo);
          $stmt->execute();
        
          if ($stmt->affected_rows > 0) {
            echo "Actualización exitosa";
          } else {
            echo "No se encontraron registros para actualizar";
          }
          $stmt->close();
        } else {
          echo "Error en la consulta: " . $conn->error;
        }
        $sql = "UPDATE prestamos SET Nombre_alumno = ?, Nivel = ?, Curso = ?, División = ?, Dni = ? WHERE Dni = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
          $stmt->bind_param("ssssss", $nombre_completo, $nivel, $curso, $division, $dni, $dni_viejo);
          $stmt->execute();
        
          if ($stmt->affected_rows > 0) {
            echo "Actualización exitosa";
          } else {
            echo "No se encontraron registros para actualizar";
          }
          $stmt->close();
        } else {
          echo "Error en la consulta: " . $conn->error;
        }
    }
    
echo "hola";
       
require_once 'cerrar_conexion_bd.php';

// Actualizar los datos en la session
$_SESSION['datos_usuario'] = array(
    'nombre_completo' => $nombre_completo,
    'email' => $email,
    'nivel' => $nivel,
    'curso' => $curso,
    'division' => $division,
    'dni' => $dni,
    'id' => $id,
    'usuario' => $usuario
);


}
sleep(1);
exit();
}
}
else {
    header("Location: home.php");
}

?>
