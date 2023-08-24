<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar si el usuario ha iniciado sesión
require_once 'verificar_sesion.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //Obtener datos del form
  $ISBN = $_POST["seleccionado"];

  if (empty($ISBN)) {
    $error = '<p class="error-message">Debe seleccionar una opción para modificar la reservación.</p>';
    session_start();
    $_SESSION['error'] = $error;
    header("Location: mis_reservaciones.php");
    exit;
}
  $fecha = '';
  //Obtener fecha del isbn seleccionado según el usuario
  $Nombre_alumno = $datos_usuario['nombre_completo'];
  $sql = "SELECT Fecha FROM reservaciones WHERE ISBN = '$ISBN' and Nombre_alumno = '$Nombre_alumno'";

  $result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $fecha = $row['Fecha'];
} else {
  echo "No se encontró ninguna reserva con el ISBN y nombre de alumno proporcionados.";
}

session_start();
$_SESSION['ISBN_LIBRO'] = $ISBN;
$_SESSION['FECHA'] = $fecha;
//Session que habilitará entrar a la pag de modificación
$_SESSION['MODIFICAR'] = $ISBN;

//Adquirir datos del libro y mostrarlos
$sql = "SELECT * FROM libros WHERE `ISBN` = $ISBN";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
// ... (existing code for book information)

            // Calculate the average rating from the reseñas table
            $sql_avg_rating = "SELECT Valoración AS promedio FROM reseñas WHERE ISBN = '$ISBN'";
            $result_avg_rating = $conn->query($sql_avg_rating);

            if ($result_avg_rating && $result_avg_rating->num_rows > 0) {
                $row_avg_rating = $result_avg_rating->fetch_assoc();
                $promedio_valoracion = round($row_avg_rating['promedio'], 1);
                $stars = str_repeat('★', $promedio_valoracion) . str_repeat('☆', 5 - $promedio_valoracion);
            } else {
                $promedio_valoracion = 0;
                $stars = '☆☆☆☆☆'; // If no reviews are available, set the stars to zero.
            }

            // Add the average rating to the variable
            $info_libro .= '<a>' . $stars . '</a>';
            $info_libro .= '<p class="titulo2">' . (isset($row["Título"]) ? $row["Título"] : "Título no disponible") . '</p>';
            $info_libro .= '<textarea style="width: 450px; height: 100px; overflow: auto; resize: none;" disabled>' . (isset($row["Descripción"]) ? $row["Descripción"] : "Descripción no disponible") . '</textarea>';
            // Add the book information to the variable
            $info_libro .= '<div style="display: flex; flex-wrap: wrap;">'; // Start of the container

            $info_libro .= '<div style="width: 49%;">'; // First column (50% width)
            $info_libro .= '<p class="infolibro"><span style="color: #960a0a;">Autor:</span> ' . (isset($row["Autor"]) ? $row["Autor"] : "Autor no disponible") . '</p>';
            $info_libro .= '<p class="infolibro"><span style="color: #960a0a;">Editorial:</span> ' . (isset($row["Editorial"]) ? $row["Editorial"] : "Editorial no disponible") . '</p>';
            $info_libro .= '<p class="infolibro"><span style="color: #960a0a;">ISBN:</span> ' . (isset($row["ISBN"]) ? $row["ISBN"] : "ISBN no disponible") . '</p>';
            $info_libro .= '</div>'; // End of the first column
            
            $info_libro .= '<div style="width: 2%;"></div>'; // Second column (50% width)
            
            $info_libro .= '<div style="width: 49%;">'; // Second column (50% width)
            $info_libro .= '<p class="infolibro"><span style="color: #960a0a;">Año de publicación:</span> ' . (isset($row["Año de publicación"]) ? $row["Año de publicación"] : "Año de publicación no disponible") . '</p>';
            $info_libro .= '<p class="infolibro"><span style="color: #960a0a;">Categorías:</span> ' . (isset($row["Categorías"]) ? $row["Categorías"] : "Categorías no disponibles") . '</p>';
            $info_libro .= '<p class="infolibro"><span style="color: #960a0a;">Ejemplares disponibles:</span> ' . (isset($row["Número de ejemplares disponibles"]) ? $row["Número de ejemplares disponibles"] : "Ejemplares no disponibles") . '</p>';

            
            $_SESSION['info_libro'] = $info_libro;
            header("Location: modificar_reserva.php");
          }
        } else {
          echo "<li>No se encontraron libros</li>";
        }
echo $confirmacionHTML;

exit;
}
else {
    header("Location: home.php");
}

?>
