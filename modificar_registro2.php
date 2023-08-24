<?php
define('ACCESO_PERMITIDO', true);
// Conectar con la bd
require_once 'conexion_bd_libros.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar"]) && $_POST["editar"] === "editar") {
   
    $id = $_POST["id"];
      // Realizar la consulta para obtener los datos de los libros desde la base de datos
    $sql = "SELECT * FROM registro WHERE `id` = $id";
    $result = $conn->query($sql);
    //Código de muestra para cada libro
    if ($result->num_rows > 0) {
        // Almacena los datos del usuario en una sesión con claves asociativas
        $usuario = mysqli_fetch_assoc($result);
        session_start();
        $_SESSION['usuario'] = array(
        'id' => $usuario['id'],
        'nombre_completo' => $usuario['nombre_completo'],
        'dni' => $usuario['dni'],
        'email' => $usuario['email'],
        'nivel' => $usuario['nivel'],
        'curso' => $usuario['curso'],
        'division' => $usuario['division'],
        'usuario' => $usuario['usuario']
    ); 
    $_SESSION['permiso'] = $usuario;
    header("Location: modificar_registro3.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["modificar"]) && $_POST["modificar"] === "modificar") {
$nombre_completo = $_POST["nombre_completo"];
$dni = $_POST["dni"];
$email = $_POST["email"];
$nivel = $_POST["nivel"];
$curso = $_POST["curso"];
$division = $_POST["division"];
$usuario = $_POST["usuario"];
session_start();
$usuario_viejo=$_SESSION['usuario'];
$id = $usuario_viejo["id"];
$usuario_actual = $usuario_viejo["usuario"];
$dni_actual = $usuario_viejo["dni"];
$email_actual = $usuario_viejo["email"];

// Almacena los datos del usuario en una sesión con claves asociativas

$_SESSION['usuario_actualizar'] = array(
'id' => $id,
'nombre_completo' => $nombre_completo,
'dni' => $dni,
'dni_viejo' => $dni_actual,
'email' => $email,
'nivel' => $nivel,
'curso' => $curso,
'division' => $division,
'usuario' => $usuario
); 

if ($nivel=="Secundario"||$nivel=="Primario"||$nivel=="Inicial"){
    if (empty($division) || empty($curso) ){
        $error = '<p class="error-message">Debe completar todos los campos para efectuar el registro</p>';
        session_start();
        $_SESSION['error'] = $error;
        $_SESSION['permiso'] = $error;
        header("Location: modificar_registro3.php");
        exit;
}
}


if (!empty($email)&&!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = '<p class="error-message">El correo electrónico no tiene un formato válido</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_registro3.php");
    exit;
}



//Verificar campos vacíos
if (empty($nombre_completo) || empty($dni) || empty($nivel) || empty($usuario)) {
    $error = '<p class="error-message">Debe completar todos los campos para efectuar el cambio</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_registro3.php");
    exit;
}

   
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
    $_SESSION['permiso'] = $error;
    header("Location: modificar_registro3.php");
    exit;
    //Verificar que no se repita el dni
} elseif ($dni != $dni_actual && $result2 && $result2->num_rows > 0) {
    $error = '<p class="error-message">Ese DNI ya está registrado. Prueba elegir otro.</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_registro3.php");
    exit;
}
elseif ($email != $email_actual && $result3 && $result3->num_rows > 0) {
    $error = '<p class="error-message">Ese email ya está registrado. Prueba elegir otro.</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_registro3.php");
    exit;
}
else {
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
            url: "modificar_registro2.php",
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
header("Location: modificar_registro3.php");
exit();
}
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
  
    session_start();
    $usuario_actualizar = $_SESSION['usuario_actualizar'];
    $id = $usuario_actualizar["id"];
    $nombre_completo = $usuario_actualizar["nombre_completo"];
    $dni = $usuario_actualizar["dni"];
    $dni_viejo = $usuario_actualizar["dni_viejo"];
    $email = $usuario_actualizar["email"];
    $nivel = $usuario_actualizar["nivel"];
    $curso = $usuario_actualizar["curso"];
    $division = $usuario_actualizar["division"];
    $usuario = $usuario_actualizar["usuario"];
    
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
    sleep(1);
}

} 
}
else {
    header("Location: home.php");
}   
?>