<?php
define('ACCESO_PERMITIDO', true);
require_once 'conexion_bd_libros.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar"]) && $_POST["editar"] === "editar") {
       
        global $conn;
    
    // Verificar la conexión
    if ($conn->connect_error) { 
        die("Error de conexión: " . $conn->connect_error);
    }
    $id = $_POST["id"];
      // Realizar la consulta para obtener los datos de los libros desde la base de datos
    $sql = "SELECT * FROM libros WHERE `id` = $id";
    $result = $conn->query($sql);
    //Código de muestra para cada libro
    if ($result->num_rows > 0) {
        // Almacena los datos del usuario en una sesión con claves asociativas
        $libros = mysqli_fetch_assoc($result);
        session_start();
        $_SESSION['libros'] = array(
        'id' => $libros['id'],
        'Título' => $libros['Título'],
        'Portada' => $libros['Portada'],
        'Autor' => $libros['Autor'],
        'Editorial' => $libros['Editorial'],
        'Año de publicación' => $libros['Año de publicación'],
        'ISBN' => $libros['ISBN'],
        'Descripción' => $libros['Descripción'],
        'Categorías' => $libros['Categorías'],
        'Número de ejemplares disponibles' => $libros['Número de ejemplares disponibles'],
        'Stock' => $libros['Stock']
    ); 
    $_SESSION['permiso'] = $libros;
    header("Location: actualizar_libros.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["actualizar"]) && $_POST["actualizar"] === "actualizar") {
$id = $_POST["id"];
$titulo = $_POST["titulo"];
$autor = $_POST["autor"];
$editorial = $_POST["editorial"];
$añodepublicación = $_POST["añodepublicación"];
$isbn = $_POST["isbn"];
$descripción = $_POST["descripción"];
$categorías = $_POST["categorías"];
$númerodeejemplaresdisponibles = $_POST["númerodeejemplaresdisponibles"];
$stock = $_POST["stock"];
$portada = $_POST["portada"];

// Almacena los datos del usuario en una sesión con claves asociativas
session_start();
$_SESSION['libro_actualizar'] = array(
'id' => $id,
'Título' => $titulo,
'Autor' => $autor,
'Editorial' => $editorial,
'Año_de_publicación' => $añodepublicación,
'ISBN' => $isbn,
'Descripción' => $descripción,
'Categorías' => $categorías,
'Número_de_ejemplares_disponibles' => $númerodeejemplaresdisponibles,
'Stock' => $stock,
'Portada' => $portada
); 

//Verificar campos vacíos
if (empty($id) || empty($titulo) || empty($autor) || empty($editorial) || empty($añodepublicación) || empty($isbn) || empty($descripción) || empty($categorías) || empty($númerodeejemplaresdisponibles)|| empty($stock)|| empty($portada)) {
    $error = '<p class="error-message">Debe completar todos los campos para efectuar el cambio</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: actualizar_libros.php");
    exit;
}

global $conn;
$sql = "SELECT ISBN FROM libros WHERE id = '$id'";

  $result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $isbn_viejo = $row['ISBN'];
}

$sql = "SELECT * FROM libros WHERE ISBN = '$isbn'";
$result = $conn->query($sql);

//Verificar que no se repita el nombre de usuario
if ($isbn != $isbn_viejo && $result && $result->num_rows > 0) {
    $error = '<p class="error-message">Ese ISBN ya está registrado. Prueba elegir otro.</p>';
    session_start();
    $_SESSION['error'] = $error;
    $_SESSION['permiso'] = $error;
    header("Location: actualizar_libros.php");
    exit;
    //Verificar que no se repita el dni
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
        window.location.href = "modificar_libros.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "actualizar_libros2.php",
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
header("Location: actualizar_libros.php");
exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
    global $conn;
    
    session_start();
    $libro_actualizar = $_SESSION['libro_actualizar'];
    $id = $libro_actualizar["id"];
    $titulo = $libro_actualizar["Título"];
    $autor = $libro_actualizar["Autor"];
    $editorial = $libro_actualizar["Editorial"];
    $añodepublicación = $libro_actualizar["Año_de_publicación"];
    $isbn = $libro_actualizar["ISBN"];
    $descripción = $libro_actualizar["Descripción"];
    $categorías = $libro_actualizar["Categorías"];
    $númerodeejemplaresdisponibles = $libro_actualizar["Número_de_ejemplares_disponibles"];
    $stock = $libro_actualizar["Stock"];
    $portada = $libro_actualizar["Portada"];
    
    // Using prepared statement to avoid SQL injection
    $query = "UPDATE libros SET Título=?, Autor=?, Editorial=?, `Año de publicación`=?, ISBN=?, Descripción=?, Categorías=?, `Número de ejemplares disponibles`=?, Stock=?, Portada=? WHERE id=?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
    die("Error in preparing the query: " . $conn->error);
    } 

    // Binding the parameters and executing the query
    $stmt->bind_param("sssssssiisi", $titulo, $autor, $editorial, $añodepublicación, $isbn, $descripción, $categorías, $númerodeejemplaresdisponibles, $stock, $portada, $id);

    if ($stmt->execute()) {
    }
    sleep(1);
}

}
else {
    header("Location: home.php");
}   
?>