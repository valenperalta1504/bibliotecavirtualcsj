<?php
define('ACCESO_PERMITIDO', true);
// Conectar con la bd
require_once 'conexion_bd_libros.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["modificar"]) && $_POST["modificar"] === "modificar") {
  

    $id = $_POST["id"];
      // Realizar la consulta para obtener los datos de los libros desde la base de datos
    $sql = "SELECT * FROM prestamos WHERE `id` = $id";
    $result = $conn->query($sql);
    //Código de muestra para cada libro
    if ($result->num_rows > 0) {
        // Almacena los datos del usuario en una sesión con claves asociativas
        $prestamo_modificar = mysqli_fetch_assoc($result);
        session_start();
        $_SESSION['prestamo_modificar'] = array(
        'id' => $prestamo_modificar['id'],
        'Nombre_alumno' => $prestamo_modificar['Nombre_alumno'],
        'Dni' => $prestamo_modificar['Dni'],
        'Nivel' => $prestamo_modificar['Nivel'],
        'Curso' => $prestamo_modificar['Curso'],
        'División' => $prestamo_modificar['División'],
        'ISBN' => $prestamo_modificar['ISBN'],
        'Fecha_retiro' => $prestamo_modificar['Fecha_retiro'],
        'Fecha_devolución' => $prestamo_modificar['Fecha_devolución']
    ); 
    $_SESSION['permiso'] = $prestamo_modificar;
    header("Location: modificar_prestamo.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["actualizar"]) && $_POST["actualizar"] === "actualizar") {
$Nombre_alumno = $_POST["Nombre_alumno"];
$Dni = $_POST["Dni"];
$Nivel = $_POST["Nivel"];
$Curso = $_POST["Curso"];
$División = $_POST["División"];
$ISBN = $_POST["ISBN"];
$Fecha_retiro = $_POST["Fecha_retiro"];
$Fecha_devolución = $_POST["Fecha_devolución"];
session_start();
$prestamo_modificar=$_SESSION['prestamo_modificar'];
$id = $prestamo_modificar["id"];
$ISBN_viejo = $prestamo_modificar["ISBN"];

// Almacena los datos del usuario en una sesión con claves asociativas

$_SESSION['prestamo_actualizar'] = array(
'id' => $id,
'Nombre_alumno' => $Nombre_alumno,
'Dni' => $Dni,
'Nivel' => $Nivel,
'Curso' => $Curso,
'División' => $División,
'ISBN' => $ISBN,
'Fecha_retiro' => $Fecha_retiro,
'Fecha_devolución' => $Fecha_devolución,
'ISBN_viejo' => $ISBN_viejo
); 

if ($Nivel=="Secundario"||$Nivel=="Primario"||$Nivel=="Inicial"){
    if (empty($División) || empty($Curso) ){
        $error = '<p class="error-message">Debe completar todos los campos para efectuar el registro</p>';
        session_start();
        $_SESSION['error'] = $error;
        $_SESSION['permiso'] = $error;
        header("Location: modificar_prestamo.php");
        exit;
}
}

//Verificar campos vacíos
if (empty($Nombre_alumno) || empty($Dni) || empty($Nivel) || empty($ISBN) || empty($Fecha_retiro)|| empty($Fecha_devolución)) {
    $error = '<p class="error-message">Debe completar todos los campos para efectuar el cambio</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_prestamo.php");
    exit;
}

 
//Buscar usuario en el registro
$sql = "SELECT * FROM libros WHERE ISBN = '$ISBN'";
$result = $conn->query($sql);
if ($result && $result->num_rows === 0) {
    $error = '<p class="error-message">El ISBN proporcionado no se encuentra en la base de datos.</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_prestamo.php");
    exit;
}

//Buscar usuario en el registro
$sql = "SELECT * FROM prestamos WHERE ISBN = '$ISBN' AND Dni = '$Dni'";
$result = $conn->query($sql);
if ($ISBN != $ISBN_viejo && $result && $result->num_rows > 0) {
    $error = '<p class="error-message">El usuario ya tiene prestado ese libro.</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_prestamo.php");
    exit;
}

//Buscar usuario en el registro
$sql = "SELECT * FROM reservaciones WHERE ISBN = '$ISBN' AND Dni = '$Dni'";
$result = $conn->query($sql);
if ($ISBN != $ISBN_viejo && $result && $result->num_rows > 0) {
    $error = '<p class="error-message">El usuario ya tiene reservado ese libro. Realice el prestamo desde la tabla de reservaciones.</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_prestamo.php");
    exit;
}

// Convertir las fechas a objetos DateTime para compararlas
$fecha_retiro_obj = new DateTime($Fecha_retiro);
$fecha_devolucion_obj = new DateTime($Fecha_devolución);

// Verificar si la fecha de retiro es posterior a la fecha de devolución
if ($fecha_retiro_obj > $fecha_devolucion_obj) {
    $error = '<p class="error-message">La fecha de retiro debe ser anterior a la fecha de devolución.</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: modificar_prestamo.php");
    exit;
}

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
            url: "modificar_prestamo2.php",
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
header("Location: modificar_prestamo.php");
exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
   
    
    session_start();
    $prestamo_actualizar = $_SESSION['prestamo_actualizar'];
    $id = $prestamo_actualizar["id"];
    $Nombre_alumno = $prestamo_actualizar["Nombre_alumno"];
    $Dni = $prestamo_actualizar["Dni"];
    $Nivel = $prestamo_actualizar["Nivel"];
    $Curso = $prestamo_actualizar["Curso"];
    $División = $prestamo_actualizar["División"];
    $ISBN = $prestamo_actualizar["ISBN"];
    $Fecha_retiro = $prestamo_actualizar["Fecha_retiro"];
    $Fecha_devolución = $prestamo_actualizar["Fecha_devolución"];
    $ISBN_viejo = $prestamo_actualizar["ISBN_viejo"];

    //Buscar usuario en el registro
    $sql = "SELECT * FROM libros WHERE ISBN = '$ISBN'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Nombre_libro = $row['Título'];
    $Portada = $row['Portada'];
}

    if($ISBN!=$ISBN_viejo){
    //Sumar 1 a los ejemplares disponibles
    $query_update = "UPDATE libros SET `Número de ejemplares disponibles` = `Número de ejemplares disponibles` - 1 WHERE ISBN = '$ISBN'";
    $resultado_update = mysqli_query($conn, $query_update);
    //Sumar 1 a los ejemplares disponibles
    $query_update = "UPDATE libros SET `Número de ejemplares disponibles` = `Número de ejemplares disponibles` + 1 WHERE ISBN = '$ISBN_viejo'";
    $resultado_update = mysqli_query($conn, $query_update);
    }

    // Using prepared statement to avoid SQL injection
    $query = "UPDATE prestamos SET Nombre_alumno=?, Dni=?, Nivel=?, `Curso`=?, División=?, ISBN=?, Nombre_libro=?, Portada=?, Fecha_retiro=?, Fecha_devolución=? WHERE id=?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
    die("Error in preparing the query: " . $conn->error);
    } 

    // Binding the parameters and executing the query
    $stmt->bind_param("sissssssssi", $Nombre_alumno, $Dni, $Nivel, $Curso, $División, $ISBN, $Nombre_libro, $Portada, $Fecha_retiro, $Fecha_devolución, $id);

    if ($stmt->execute()) {
    }
    sleep(1);
}

} 
else {
    header("Location: home.php");
}   
?>