<?php
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cursos"]) && $_POST["cursos"] === "cursos") {

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
                url: "actualizar_cursos.php",
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
    header("Location: modificar_registros.php");
    exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "confirmar") {
        define('ACCESO_PERMITIDO', true);
require_once 'controles_admin.php';
require_once 'conexion_bd_libros.php';
global $conn;
      
        // Actualizar los datos del usuario en la base de datos
       // Consulta SQL
        
       $query = "SELECT * FROM registro WHERE curso='6to' AND nivel='Secundario'";
       $result = mysqli_query($conn, $query);
       
       while ($registro = mysqli_fetch_assoc($result)) {
           $hasOverdueDevolutions = PrestamoVencido3($registro["dni"]);
           
           if (!$hasOverdueDevolutions) {
               // No debe libros, podemos eliminar este alumno
               $deleteQuery = "DELETE FROM registro WHERE dni='" . $registro["dni"] . "'";
               $deleteResult = mysqli_query($conn, $deleteQuery);
               
           } else {
               $noeliminados=true;
               echo '<span class="notificacion5"> Alumno no eliminado (debe libros) </span>';
           }
        }

           if ($noeliminados){
           $ventana_emergente2 = '<div id="miVentana" class="modal">
           <div class="modal-content">
               <p>Algunos alumnos de 6to año no han sido eliminados ya que deben libros aún.</p> 
               <div class="boton-centro">
               <button onclick="cerrarVentana()">Aceptar</button>
                      
                       </div>
               
           </div>
           </div>
           
           <script>
           document.addEventListener("DOMContentLoaded", function() {
               mostrarVentana();
           });
           
           function mostrarVentana() {
               document.getElementById("miVentana").style.display = "flex";
           }
           
           function cerrarVentana() {
               document.getElementById("miVentana").style.display = "none";
           }
           </script>';
           session_start();
           $_SESSION['ventana_emergente2'] = $ventana_emergente2;
       }
  
        $query = "UPDATE registro SET curso='6to' WHERE curso='5to'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE registro SET curso='5to' WHERE curso='4to'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE registro SET curso='4to' WHERE curso='3ro'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE registro SET curso='3ro' WHERE curso='2do'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE registro SET curso='2do' WHERE curso='1ro'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE registro SET curso='1ro', nivel='Secundario' WHERE curso='6to' AND nivel='Primario'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE registro SET curso='1ro', nivel='Primario' WHERE curso='Sala de 5' AND nivel='Inicial'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE registro SET curso='Sala de 5' WHERE curso='Sala de 4'";
        $result = mysqli_query($conn, $query);



        $query = "UPDATE reservaciones SET Curso='6to' WHERE Curso='5to'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE reservaciones SET Curso='5to' WHERE Curso='4to'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE reservaciones SET Curso='4to' WHERE Curso='3ro'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE reservaciones SET Curso='3ro' WHERE Curso='2do'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE reservaciones SET Curso='2do' WHERE Curso='1ro'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE reservaciones SET Curso='1ro', nivel='Secundario' WHERE Curso='6to' AND Nivel='Primario'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE reservaciones SET Curso='1ro', nivel='Primario' WHERE Curso='Sala de 5' AND Nivel='Inicial'";
        $result = mysqli_query($conn, $query);
        
        $query = "UPDATE reservaciones SET Curso='Sala de 5' WHERE Curso='Sala de 4'";
        $result = mysqli_query($conn, $query);



        $query = "UPDATE prestamos SET Curso='6to' WHERE Curso='5to'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE prestamos SET Curso='5to' WHERE Curso='4to'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE prestamos SET Curso='4to' WHERE Curso='3ro'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE prestamos SET Curso='3ro' WHERE Curso='2do'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE prestamos SET Curso='2do' WHERE Curso='1ro'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE prestamos SET curso='1ro', nivel='Secundario' WHERE curso='6to' AND nivel='Primario'";
        $result = mysqli_query($conn, $query);

        $query = "UPDATE prestamos SET Curso='1ro', nivel='Primario' WHERE Curso='Sala de 5' AND Nivel='Inicial'";
        $result = mysqli_query($conn, $query);
        
        $query = "UPDATE prestamos SET Curso='Sala de 5' WHERE Curso='Sala de 4'";
        $result = mysqli_query($conn, $query);




    sleep(1);
    exit();
    }
}
    else {
        header("Location: home.php");
    }
    
    ?>
    
