<?php
define('ACCESO_PERMITIDO', true);
// Conectar con la bd
require_once 'conexion_bd_libros.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["Cargar"]) && $_POST["Cargar"] === "Cargar") {
        $archivo_tmp = $_FILES["archivo"]["tmp_name"];
        $nombre_archivo = $_FILES["archivo"]["name"];
        $nivel = $_POST["nivel"];
        $curso = $_POST["curso"];
        $division = $_POST["division"];
        session_start();
        $_SESSION['registro'] = array(
            'nivel' => $nivel,
            'curso' => $curso,
            'division' => $division,
            );  

            echo "hola";
            echo $archivo_tmp;

            if (empty($nivel) || empty($archivo_tmp) ){
                $error2 = '<p class="error-message">Debe completar todos los campos para efectuar la carga</p>';
                session_start();
                $_SESSION['error2'] = $error2;
                header("Location: subir_csv_alumnos.php");
                exit;
                }


            if ($nivel=="Secundario"||$nivel=="Primario"||$nivel=="Inicial"){
            if (empty($division) || empty($curso) ){
                $error2 = '<p class="error-message">Debe completar todos los campos para efectuar la carga</p>';
                session_start();
                $_SESSION['error2'] = $error2;
                header("Location: subir_csv_alumnos.php");
                exit;
                }
            }
        // Verifica si es un archivo CSV
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        if ($extension != "csv") {
            $error2 = '<p class="error-message">El archivo debe ser un .csv</p>';
            session_start();
            $_SESSION['error2'] = $error2;
            header("Location: subir_csv_alumnos.php");
            exit;
        }
        
        // Lee el archivo CSV
        $lineas = file($archivo_tmp, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        
        $_SESSION['lineas'] = $lineas;

        

       



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
        window.location.href = "subir_csv_alumnos.php";
    }

    function confirmarAccion() {
        mostrarVentanaCargando(); // Mostrar la ventana de carga antes de realizar la solicitud AJAX

        $.ajax({
            url: "subir_csv_curso.php",
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
header("Location: subir_csv_alumnos.php");
}
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
        
                session_start();
                $registro = $_SESSION['registro'];

                $nivel = $registro['nivel'];
                $curso = $registro['curso'];
                $division = $registro['division'];

                $lineas=$_SESSION['lineas']  ;

                // Procesa cada línea del archivo
            foreach ($lineas as $linea) {
            $campos = str_getcsv($linea); // Divide la línea en campos
            
            // Accede a los datos de las columnas específicas
            $apellidoNombres = $campos[0]; // Columna de "Apellido y Nombres"
            $tipoDNI = $campos[2]; // Columna de "Tipo y Nº DNI"
            $entrada = $tipoDNI;
            $valorNumerico = preg_replace("/[^0-9]/", "", $entrada);


            // Aquí puedes hacer lo que necesites con los datos obtenidos
            echo "Apellido y Nombres: $apellidoNombres<br>";
            echo "Tipo y Nº DNI: $valorNumerico <br><br>";
            echo "Nivel: $nivel<br>";
            echo "Curso: $curso <br><br>";
            echo "División: $division<br>";


    
    $contrasenaEncriptada = password_hash($valorNumerico, PASSWORD_DEFAULT);


    if(!empty($apellidoNombres)){
            $query = "INSERT INTO registro (nombre_completo, contrasena, nivel, dni, curso, division, usuario) VALUES ('$apellidoNombres', '$contrasenaEncriptada', '$nivel', '$valorNumerico','$curso', '$division', '$valorNumerico')";
            $resultado = mysqli_query($conn, $query);
        }
    }
    } 
}
?>

