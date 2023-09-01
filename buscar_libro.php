<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);

// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin2.0.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ISBN enviado por el formulario
    $isbn = $_POST['isbn'];
    $num_ejemplares = $_POST['num_ejemplares'];
    //Verificar campos vacíos
    if (empty($isbn) || empty($num_ejemplares)) {
        $error = '<p class="error-message">Debe completar todos los campos para efectuar la carga</p>';
        session_start();
        $_SESSION['error'] = $error;
        header("Location: subir_libros.php");
        exit;
    }

    // Verificar si ya existe un libro con el mismo isbn
    $query = "SELECT * FROM libros WHERE ISBN = '$isbn'";
    $resultado = mysqli_query($conn, $query);
    if (mysqli_num_rows($resultado) > 0) {
    $error = '<p class="error-message">Ya existe un registro de libro con es ISBN. Si desea modificar el stock dirijase a "Modificar libros".</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: subir_libros.php");
    exit;
    }

    // Realizar la solicitud a la API de Google Books
    $api_key = "AIzaSyBjOOlQqGA8vgmNTB927iEJkziHyg1_5V4"; // Reemplaza "TU_CLAVE_DE_API" con tu clave de API de Google Books
    $api_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn&key=$api_key";
    $response = file_get_contents($api_url);

    $data = json_decode($response, true);

    // Obtener los datos del libro
    $items = isset($data['items']) ? $data['items'] : array();

    if (empty($items)) {
        $error = '<p class="error-message">No se encontraron resultados con el isbn proporcionado</p>';
        session_start();
        $_SESSION['error'] = $error;
        header("Location: subir_libros.php");
        exit;
        exit;
    }

    $volumeInfo = $items[0]['volumeInfo'];

    $nombre = isset($volumeInfo['title']) ? $volumeInfo['title'] : "Desconocido";
    $autores = isset($volumeInfo['authors']) ? $volumeInfo['authors'] : array();
    $autor = !empty($autores) ? implode(", ", $autores) : "Desconocido";
    $editorial = isset($volumeInfo['publisher']) ? $volumeInfo['publisher'] : "Desconocido";
    $ano_publicacion = isset($volumeInfo['publishedDate']) ? $volumeInfo['publishedDate'] : "Desconocido";
    $descripcion_obra = isset($volumeInfo['description']) ? $volumeInfo['description'] : "Desconocido";
    $categorias = isset($volumeInfo['categories']) ? $volumeInfo['categories'] : array();
    $categoria = !empty($categorias) ? implode(", ", $categorias) : "Desconocido";
    $imageUrl = isset($volumeInfo['imageLinks']['thumbnail']) ? $volumeInfo['imageLinks']['thumbnail'] : "no_hay_portada.jpg";


    // Crear un arreglo asociativo con los datos del libro
 $libro = array(
    "Título" => $nombre,
    "Autor" => $autor,
    "Editorial" => $editorial,
    "ISBN" => $isbn,
    "Año de publicación" => $ano_publicacion,
    "Descripción" => $descripcion_obra,
    "Categorías" => $categoria,
    "Número de ejemplares" => $num_ejemplares,
    "Portada" => $imageUrl
);

// Guardar los datos del libro en la variable de sesión
$_SESSION['libro_cargado'] = $libro;
$_SESSION['carga_isbn'] = true;

$libro_cargado = $_SESSION['libro_cargado'];
$datos_libro = json_encode($libro_cargado);
$datos_libro = json_decode($datos_libro, true);
$datos = '<div id="portada-container">';
$datos .= '<img src="' . $datos_libro['Portada'] . '" alt="Portada del libro">';
$datos .= '</div>';
$datos .= "<p>Título: " . $datos_libro['Título'] . "</p>";
$datos .= "<p>Autor: " . $datos_libro['Autor'] . "</p>";
$datos .= "<p>Editorial: " . $datos_libro['Editorial'] . "</p>";
$datos .= "<p>ISBN: " . $datos_libro['ISBN'] . "</p>";

$descripcion = $datos_libro['Descripción'];
$descripcion_cortada = wordwrap($descripcion, 80, "<p>", true);
$datos .= "<p>Descripción:</p>";
$datos .= "<p>" . $descripcion_cortada . "</p>";

$datos .= "<p>Categorías: " . $datos_libro['Categorías'] . "</p>";
$datos .= "<p>Número de ejemplares: " . $datos_libro['Número de ejemplares'] . "</p>";
 
//Mostrar una ventana emergente
    if (isset($_SESSION['libro_cargado']) && !empty($_SESSION['libro_cargado'])) {
        $ventana_emergente = '<body 
onload="mostrarVentana()"> <!-- Invocamos la función mostrarVentana() al cargar la página -->
    <!-- Contenido de la página -->


    <!-- Ventana emergente -->
    <div id="miVentana" class="modal">
        <div class="modal-content">
        <p class="titulo3">LIBRO INGRESADO:</p>
                <p><p>' 
                . $datos . '
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
        window.location.href = "subir_libros.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "carga.php",
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
        header("Location: subir_libros.php");
    }
} else {
    header("Location: home.php");
}

// Cerrar la conexión a la base de datos
require_once 'cerrar_conexion_bd.php';

?>
