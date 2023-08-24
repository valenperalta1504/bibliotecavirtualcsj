<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener respuestas del formulario
    $titulo = $_POST['Título'];
    $portada = $_POST['Portada'];
    $autor = $_POST['Autor'];
    $editorial = $_POST['Editorial'];
    $isbn = $_POST['ISBN'];
    $año_publicacion = $_POST['Año_de_publicación'];
    $descripcion = $_POST['descripción']; // Cambiado a 'descripción' en minúsculas
    $categorias = $_POST['Categorías'];
    $num_ejemplares = $_POST['Número_de_ejemplares'];
    //Verificar campos vacíos
    if (empty($titulo) || empty($autor) || empty($editorial) || empty($isbn) || empty($año_publicacion) || empty($descripcion) || empty($categorias) || empty($num_ejemplares)|| empty($portada)) {
        $error = '<p class="error-message">Debe completar todos los campos para efectuar la carga</p>';
        session_start();
        $_SESSION['error'] = $error;
        header("Location: cargar_libro_manual.php");
        exit;
    }

    // Verificar si ya existe un libro con el mismo isbn
    $query = "SELECT * FROM libros WHERE ISBN = '$isbn'";
    $resultado = mysqli_query($conn, $query);
    if (mysqli_num_rows($resultado) > 0) {
    $error = '<p class="error-message">Ya existe un registro de libro con es ISBN. Si desea modificar el stock dirijase a "Modificar libros".</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: cargar_libro_manual.php");
    exit;
    }

    // Crear un arreglo asociativo con los datos del libro
    $libro = array(
        "Título" => $titulo,
        "Portada" => $portada,
        "Autor" => $autor,
        "Editorial" => $editorial,
        "ISBN" => $isbn,
        "Año de publicación" => $año_publicacion,
        "Descripción" => $descripcion,
        "Categorías" => $categorias,
        "Número de ejemplares" => $num_ejemplares
    );

    // Guardar los datos del libro en la variable de sesión
    $_SESSION['libro_cargado'] = $libro;
    $_SESSION['carga_manual'] = true;

    $libro_cargado = $_SESSION['libro_cargado'];
    $datos_libro = json_encode($libro_cargado);
    $datos_libro = json_decode($datos_libro, true);
    $datos = '<div id="portada-container">';
    $datos .= '<img src="' . $datos_libro['Portada'] . '" alt="Portada del libro">';
    $datos .= '</div>';
    $datos.= "<p>Título:";
    $datos.= " " . $datos_libro['Título'] . "</p>";

    $datos.= "<p>Autor:";
    $datos.= " " . $datos_libro['Autor'] . "</p>";

    $datos.= "<p>Editorial:";
    $datos.= " " . $datos_libro['Editorial'] . "</p>";

    $datos.= "<p>ISBN:";
    $datos.= " " . $datos_libro['ISBN'] . "</p>";

    $datos.= "<p>Año de publicación:";
    $datos.= " " . $datos_libro['Año de publicación'] . "</p>";

    $descripcion = $datos_libro['Descripción'];

    $descripcion_cortada = wordwrap($descripcion, 80, "<p>", true);
    
    $datos .= "<p>Descripción:</p>";
    $datos .= "<p>" . $descripcion_cortada . "</p>";

    $datos.= "<p>Categorías:";
    $datos.= " " . $datos_libro['Categorías'] . "</p>";

    $datos.= "<p>Número de ejemplares:";
    $datos.= " " . $datos_libro['Número de ejemplares'] . "</p>";

    //Mostrar un msj de confirmación
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
        window.location.href = "cargar_libro_manual.php";
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
        header("Location: cargar_libro_manual.php");
    }
}
else {
    header("Location: home.php");
}
