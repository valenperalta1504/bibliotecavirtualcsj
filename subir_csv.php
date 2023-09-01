<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin2.0.php';
// verificar si existe un mensaje de error
require_once 'mensaje_de_error.php';
// verificar si hay ventana emergente
require_once 'ventana_emergente.php';
// verificar si hay ventana emergente
require_once 'obtener_libros.php';
// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica que se haya seleccionado un archivo

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["Cargar"]) && $_POST["Cargar"] === "Cargar") {    
    if (isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] === UPLOAD_ERR_OK) {
        session_start(); // Start the session
            // Verifica que se haya seleccionado un archivo
                $nombreArchivo = $_FILES["archivo"]["name"];
                $nombreTemporal = $_FILES["archivo"]["tmp_name"];
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        
                // Verifica que el archivo sea CSV o Excel
                if ($extension === "csv") {
                    // Abre el archivo CSV en modo lectura
                    if (($archivo = fopen($nombreTemporal, "r")) !== false) {
                        // Salta la primera fila del archivo (encabezados)
                        fgetcsv($archivo);
        
                        $data = array(); // Array to store CSV data
                        while (($row = fgetcsv($archivo)) !== false) {
                            // Process each row and add it to the data array
                            $data[] = $row;
                        }
        
                        fclose($archivo);
        
                        // Store the data array in a session variable
                        $_SESSION["csv_data"] = $data;
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
        window.location.href = "subir_archivocsv.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "subir_csv.php",
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
header("Location: subir_archivocsv.php");
                    } else {
                        echo "Error al abrir el archivo CSV.";
                    }
                } else {
                    $error2 = '<p class="error-message">Debe subir un archivo csv</p>';
    session_start();
    $_SESSION['error2'] = $error2;
    header("Location: subir_archivocsv.php");
                }
            } else {
                $error2 = '<p class="error-message">Debe subir un archivo csv</p>';
    session_start();
    $_SESSION['error2'] = $error2;
    header("Location: subir_archivocsv.php");
    exit;
            }
        }
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
                session_start(); // Iniciar la sesión (asegúrate de que sea la misma sesión que en procesar.php)

                if (isset($_SESSION["csv_data"])) {
                    // Recuperar los datos del archivo CSV de la variable de sesión
                    $data = $_SESSION["csv_data"];
                
                    // Aquí puedes realizar el procesamiento necesario con los datos
                    // Por ejemplo, puedes obtener el ISBN de cada fila y utilizarlo para obtener la información del libro desde la API y almacenarla en la base de datos.
                    // El siguiente código es similar al que proporcionaste inicialmente:
                    
                    $contadorisbn = -1;
                    $contadorisbn2 = 0;
                    $arrayerrores = array();
                
                    foreach ($data as $fila) {
                        // Obtiene el ISBN de la quinta columna (índice 4) de la fila actual
                        $isbn = $fila[4];
                        $contadorisbn2++;
                
                        // Ahora puedes usar el ISBN para obtener la información del libro desde la API
                        // y almacenarla en la base de datos.
                
                        // Aquí incluirías el código para hacer la solicitud a la API y guardar
                        // la información en la base de datos. Supongamos que la función
                        // 'obtenerInformacionLibro' hace esto y retorna un arreglo con los datos
                        // del libro (título, autor, editorial, etc.).
                        $infoLibro = obtenerInformacionLibro($isbn);
                
                        // Resto del código para almacenar los datos en la base de datos...
                        // (No se ha modificado el código posterior al cambio de $isbn)
                
                        if (!empty($infoLibro)) {
                            $titulo = $infoLibro['Título'];
                            $autor = $infoLibro['Autor'];
                            $editorial = $infoLibro['Editorial'];
                            $isbn = $infoLibro['ISBN'];
                            $anodepublicacion = $infoLibro['Año de publicación'];
                            $descripcion = $infoLibro['Descripción'];
                            $categorias = $infoLibro['Categorías'];
                            $portada = $infoLibro['Portada'];
                            almacenarDatosEnBD($titulo, $autor, $editorial, $isbn, $anodepublicacion, $descripcion, $categorias, $portada);
                        } else {
                            $contadorisbn++;
                            $arrayerrores[$contadorisbn] = $isbn;
                            $posición[$contadorisbn] = $contadorisbn2;
                        }
                    }
            }
       
                         $_SESSION['carga']= "<p>Archivo procesado y datos almacenados en la base de datos.</p>";
         
                         if (!empty($arrayerrores)) 
         
                         // Obtenemos la longitud del array
                         $tamaño = count($arrayerrores);
         
         
                         $_SESSION['error2']="";
                         // Usamos un bucle for para recorrer el array y mostrar cada elemento
                         for ($i = 0; $i < $tamaño; $i++) {
                         $error2='<p class="error-message"> No encontramos el libro número' . $posición[$i] . ': ' . $arrayerrores[$i] . '</p>';
                         $_SESSION['error2'].= $error2;
                         }
                        }
                         }
                         else {
                            header("Location: home.php");
                        }   
