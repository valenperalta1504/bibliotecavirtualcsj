<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar si el usuario ha iniciado sesión
require_once 'verificar_sesion.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';
//Condición para saber si se ejecuta como action del metodo post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Obtener los datos del formulario del catalogo
$ISBN = $_POST["ISBN"];
session_start();
$_SESSION['ISBN_LIBRO'] = $ISBN;
//Esta session permitirá el acceso a la página de reserva
$_SESSION['RESERVA'] = $ISBN;
$Dni = $datos_usuario['dni'];

// Obtener el nombre y DNI del usuario
$nombre_usuario = $datos_usuario['nombre_completo'];
$dni_usuario = $datos_usuario['dni'];

// Consulta para obtener la cantidad de libros reservados por el usuario
$query_cantidad = "SELECT COUNT(*) AS cantidad FROM reservaciones WHERE Nombre_alumno = '$nombre_usuario' AND Dni = '$dni_usuario'";
$resultado_cantidad = mysqli_query($conn, $query_cantidad);

// Verificar si la consulta fue exitosa
if ($resultado_cantidad) {
  $row_cantidad = mysqli_fetch_assoc($resultado_cantidad);
  $cantidad_libros_reservados = $row_cantidad['cantidad'];
} else {
  $cantidad_libros_reservados = 0;
}

// Consulta para obtener la cantidad de libros reservados por el usuario
$query_cantidad = "SELECT COUNT(*) AS cantidad FROM prestamos WHERE Nombre_alumno = '$nombre_usuario' AND Dni = '$dni_usuario'";
$resultado_cantidad = mysqli_query($conn, $query_cantidad);

// Verificar si la consulta fue exitosa
if ($resultado_cantidad) {
  $row_cantidad = mysqli_fetch_assoc($resultado_cantidad);
  $cantidad_libros_prestados = $row_cantidad['cantidad'];
} else {
  $cantidad_libros_prestados = 0;
}

$cantidad_de_libros=$cantidad_libros_reservados+$cantidad_libros_prestados;

// Obtener datos del libro elegido y mostrarlos
$sql = "SELECT * FROM libros WHERE ISBN = '$ISBN'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            // ... (existing code for book information)


           // Calculate the sum of ratings and the total number of reviews from the reseñas table
$sql_avg_rating = "SELECT SUM(Valoración) AS suma_valoraciones, COUNT(*) AS total_resenas FROM reseñas WHERE ISBN = '$ISBN'";
echo "Consulta SQL: " . $sql_avg_rating; // Mostrar la consulta SQL
$result_avg_rating = $conn->query($sql_avg_rating);

if ($result_avg_rating && $result_avg_rating->num_rows > 0) {
    $row_avg_rating = $result_avg_rating->fetch_assoc();
    $suma_valoraciones = $row_avg_rating['suma_valoraciones'];
    $total_resenas = $row_avg_rating['total_resenas'];

    if ($total_resenas > 0) {
        $promedio_valoracion = $suma_valoraciones / $total_resenas;
        // Truncate the average to one decimal place
        $promedio_valoracion = number_format($promedio_valoracion, 0);
        $stars = str_repeat('★', $promedio_valoracion) . str_repeat('☆', 5 - $promedio_valoracion);
    } else {
        $promedio_valoracion = 0;
        $stars = '☆☆☆☆☆'; // If no reviews are available, set the stars to zero.
    }
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
            $info_libro .= '</div>';
            $_SESSION['info_libro'] = $info_libro;
          }
            $portada = '<div id="portada-container">';
            $portada .= '<img src="' . $row['Portada'] . '" alt="Portada del libro">';
            $portada .= '</div>';
            $_SESSION['portada'] = $portada;
            header("Location: reservar.php");
          
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
