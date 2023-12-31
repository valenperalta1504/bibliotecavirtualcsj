<?php
define('ACCESO_PERMITIDO', true);
// Conectar con la bd
require_once 'conexion_bd_libros.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["insertar"]) && $_POST["insertar"] === "insertar") {
    $Dni = $_POST["selectedUsers"];
    $ISBN = $_POST["isbn"];
$Fecha = $_POST["Fecha"];

//Verificar campos vacíos
if ( empty($Dni) || empty($ISBN)|| empty($Fecha)) {
    $error = '<p class="error-message">Debe completar todos los campos para efectuar la acción</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: insertar_reserva.php");
    exit;
}

$sql = "SELECT dni, nombre_completo, nivel, curso, division FROM registro WHERE Dni = '$Dni'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Obtener la primera fila de resultados
    
    // Guardar los valores en variables
    $Nombre_alumno = $row['nombre_completo'];
    $Nivel = $row['nivel'];
    $Curso = $row['curso'];
    $División = $row['division'];
}
   
    $sql = "SELECT * FROM reservaciones WHERE ISBN = '$ISBN' AND Dni = '$Dni'";

    $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    $error = '<p class="error-message">Ese usuario ya tiene registrado una reserva de ese mismo libro</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: insertar_reserva.php");
    exit;
    }

// Consulta para obtener la cantidad de libros reservados por el usuario
$query_cantidad = "SELECT COUNT(*) AS cantidad FROM reservaciones WHERE Dni = '$Dni'";
$resultado_cantidad = mysqli_query($conn, $query_cantidad);

// Verificar si la consulta fue exitosa
if ($resultado_cantidad) {
  $row_cantidad = mysqli_fetch_assoc($resultado_cantidad);
  $cantidad_libros_reservados = $row_cantidad['cantidad'];
} else {
  $cantidad_libros_reservados = 0;
}

// Consulta para obtener la cantidad de libros reservados por el usuario
$query_cantidad = "SELECT COUNT(*) AS cantidad FROM prestamos WHERE Dni = '$Dni'";
$resultado_cantidad = mysqli_query($conn, $query_cantidad);

// Verificar si la consulta fue exitosa
if ($resultado_cantidad) {
  $row_cantidad = mysqli_fetch_assoc($resultado_cantidad);
  $cantidad_libros_prestados = $row_cantidad['cantidad'];
} else {
  $cantidad_libros_prestados = 0;
}

$cantidad_de_libros=$cantidad_libros_reservados+$cantidad_libros_prestados;

// Establecer límite de reservaciones y mostrar un msj si lo supera
if ($cantidad_de_libros>=3){
    $error = '<p class="error-message">Este usuario ya ha superado su límite de prestamos/reservas</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: insertar_reserva.php");
    exit;
}

$sql = "SELECT * FROM libros WHERE ISBN = '$ISBN'";

  $result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $Nombre_libro = $row['Título'];
  $Portada = $row['Portada'];
}
else{
    $error = '<p class="error-message">El ISBN proporcionado no se encuentra en la base de datos</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: insertar_reserva.php");
    exit;
}

// Almacena los datos del usuario en una sesión con claves asociativas
session_start();
$_SESSION['insertar_reserva'] = array(
'Nombre_alumno' => $Nombre_alumno,
'Dni' => $Dni,
'Nivel' => $Nivel,
'Curso' => $Curso,
'División' => $División,
'ISBN' => $ISBN,
'Nombre_libro' => $Nombre_libro,
'Portada' => $Portada,
'Fecha' => $Fecha
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
            url: "nueva_reserva.php",
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
header("Location: insertar_reserva.php");
exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
    
    
    session_start();
    $insertar_reserva = $_SESSION['insertar_reserva'];
    $Nombre_alumno = $insertar_reserva["Nombre_alumno"];
    $Dni = $insertar_reserva["Dni"];
    $Nivel = $insertar_reserva["Nivel"];
    $Curso = $insertar_reserva["Curso"];
    $División = $insertar_reserva["División"];
    $ISBN = $insertar_reserva["ISBN"];
    $Nombre_libro = $insertar_reserva["Nombre_libro"];
    $Portada = $insertar_reserva["Portada"];
    $Fecha = $insertar_reserva["Fecha"];
    
    // Using prepared statement to avoid SQL injection
    $query = "INSERT reservaciones SET Nombre_alumno=?, Dni=?, Nivel=?, `Curso`=?, División=?, ISBN=?, Nombre_libro=?, `Portada`=?, Fecha=?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
    die("Error in preparing the query: " . $conn->error);
    } 

    // Binding the parameters and executing the query
    $stmt->bind_param("sisssssss", $Nombre_alumno, $Dni, $Nivel, $Curso, $División, $ISBN, $Nombre_libro, $Portada, $Fecha);

    if ($stmt->execute()) {
        echo "bien";
    }

    //Sumar 1 a los ejemplares disponibles
    $query_update = "UPDATE libros SET `Número de ejemplares disponibles` = `Número de ejemplares disponibles` - 1 WHERE ISBN = '$ISBN'";
    $resultado_update = mysqli_query($conn, $query_update);

    sleep(1);
}

}
else {
    header("Location: home.php");
}   
?>