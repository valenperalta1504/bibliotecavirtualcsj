<?php
//Permiso para abrir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin.php';
// verificar si existe un mensaje de error
require_once 'mensaje_de_error.php';
// verificar si hay ventana emergente
require_once 'ventana_emergente.php';
// verificar si hay ventana emergente
require_once 'obtener_libros.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';
// Conexión con la base de datos
require_once 'conexion_bd_libros.php';



$conn = new mysqli($host, $user, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


$sql = "SELECT `Nombre_alumno`,`Valoración`, `Reseña`, `ISBN`
        FROM `reseñas`
        ORDER BY id DESC
        LIMIT 10";

$result = $conn->query($sql);

$reseñas = array();
$libros = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reseñas[] = array(
            "Nombre_alumno" => $row["Nombre_alumno"],
            "Valoración" => $row["Valoración"],
            "Reseña" => $row["Reseña"]
        );

        // Realizar una consulta separada para obtener el título del libro
        $isbn = $row["ISBN"];
        $titulo_sql = "SELECT `Título` FROM `libros` WHERE `ISBN` = '$isbn'";
        $titulo_result = $conn->query($titulo_sql);

        if ($titulo_result->num_rows > 0) {
            $titulo_row = $titulo_result->fetch_assoc();
            $libros[] = $titulo_row["Título"];
        } else {
            $libros[] = "Título no encontrado";
        }
    }
}

// Función para generar las estrellas según la valoración
function generateStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= ($i <= $rating) ? '&#9733;' : '&#9734;';
    }
    return $stars;
  }

function obtenerLibrosMasPrestados($conn) {
    $ultimoMes = date('Y-m-d', strtotime('-1 month'));
    $query = "SELECT ISBN, COUNT(*) AS num_prestamos FROM historial_prestamos GROUP BY ISBN ORDER BY num_prestamos DESC LIMIT 10";
    $resultado = mysqli_query($conn, $query);

    $librosMasPrestados = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $librosMasPrestados[$fila['ISBN']] = $fila['num_prestamos'];
    }

    return $librosMasPrestados;
}

function obtenerLibrosMejorValorados($conn) {
    $query = "SELECT ISBN, AVG(Valoración) AS promedio_valoracion FROM reseñas GROUP BY ISBN ORDER BY promedio_valoracion DESC";
    $resultado = mysqli_query($conn, $query);

    $librosMejorValorados = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $librosMejorValorados[$fila['ISBN']] = $fila['promedio_valoracion'];
    }

    return $librosMejorValorados;
}

function obtenerTitulosLibros($conn, $isbn_array) {
    $isbn_list = implode("','", $isbn_array);
    $query = "SELECT ISBN, Título FROM libros WHERE ISBN IN ('$isbn_list')";
    $resultado = mysqli_query($conn, $query);

    $titulosLibros = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $titulosLibros[$fila['ISBN']] = $fila['Título'];
    }

    return $titulosLibros;
}

// Obtener los 10 libros más prestados en el último mes
$librosMasPrestados = obtenerLibrosMasPrestados($conn);
$isbn_libros_mas_prestados = array_keys($librosMasPrestados);
$titulos_libros_mas_prestados = obtenerTitulosLibros($conn, $isbn_libros_mas_prestados);

// Obtener los libros mejor valorados
$librosMejorValorados = obtenerLibrosMejorValorados($conn);
$isbn_libros_mejor_valorados = array_keys($librosMejorValorados);
$titulos_libros_mejor_valorados = obtenerTitulosLibros($conn, $isbn_libros_mejor_valorados);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<link rel="icon" type="image/png" href="/logo6.png"/>
    <title>Biblioteca Virtual</title>
    <link rel="stylesheet" href="style18.css">

    <!-- Biblioteca Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome para estrellas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body> 
    <div class="container">
        <nav>
            <ul>
                <?php
                // Llamada a la función pasando el valor de $es_admin
                mostrar_menu_admin($_SESSION['es_admin']);
                ?>
            </ul>
        </nav>
        <header>
            <div class="container-menu">
                <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José" class="logo">
                <nav class="menu3">
                <?php
    		// Llamada a la función pasando el valor de $es_admin
    		mostrar_menu_admin2($_SESSION['es_admin']);
    		?>
                </nav>
            </div>
        </header>
    </div>
    <main>


    <div class="container6">

<div class="columna-60">
  <li class=mili>
  <p class="titulo">LIBROS MÁS PRESTADOS:</p>
            <?php foreach ($librosMasPrestados as $isbn => $cantidadPrestamos): ?>
            
            <?php endforeach; ?>
            <div class="chart-container">
                <canvas id="librosPrestadosChart"></canvas>
            </div>
            </div>

            <div class="columna-40">
  <li class=mili>
  <p class="titulo">LIBROS MEJOR VALORADOS:</p>
            <?php foreach ($librosMejorValorados as $isbn => $promedioValoracion): ?>
                
            <?php endforeach; ?>
            <div class="chart-container">
                <canvas id="librosValoradosChart"></canvas>
            </div>
            </li>
        </div>


        </div>
        <li>

  <?php



?>
  <p class="titulo">RESEÑAS RECIENTES:</p>
  <br><br>
<!-- Estructura HTML para mostrar las reseñas con los títulos de libros -->
<div class="foro">
    <?php if (empty($reseñas)) { ?>
        <p>No hay reseñas aún.</p>
    <?php } else { ?>
        <?php foreach ($reseñas as $index => $reseña) { ?>
            <div class="publicacion">
            <p class="titulo2"><?php echo $libros[$index]; ?><p class="titulo2">
                <div class="search-container">
                    <p class="titulo5"><?php echo $reseña['Nombre_alumno']; ?>:</p>
                    <p class="invisible">s</p>
                    <p><?php echo generateStars($reseña['Valoración']); ?></p>
                </div>
                <textarea style="width: 1150px; height: 100px; overflow: auto; resize: none; background-color: transparent;" disabled><?php echo $reseña['Reseña']; ?></textarea>
            </div>
        <?php } ?>
    <?php } ?>
</div>
  </li>
    </main>
    <script>
   // Obtener los datos para los gráficos
  const librosPrestadosData = <?php echo json_encode(array_values($librosMasPrestados)); ?>;
  const librosPrestadosLabels = <?php echo json_encode(array_values($titulos_libros_mas_prestados)); ?>;
  const librosValoradosData = <?php echo json_encode(array_values($librosMejorValorados)); ?>;
  const librosValoradosLabels = <?php echo json_encode(array_values($titulos_libros_mejor_valorados)); ?>;

  // Función para generar una paleta de colores con diferentes tonos de celeste, turquesa, azul, violeta y rosa
  function generateCustomColors() {
    return [
        'hsl(0, 100%, 23%)', // 7C0000
'hsl(6, 80%, 36%)', // 8A1717
'hsl(9, 57%, 43%)', // 9B2F2F
'hsl(13, 54%, 47%)', // A74040
'hsl(18, 50%, 50%)', // BB5454
'hsl(20, 48%, 55%)', // C86A6A
'hsl(21, 52%, 57%)', // CB7777
'hsl(24, 57%, 61%)', // D58E8E
'hsl(27, 67%, 67%)', // E7A0A0
'hsl(30, 73%, 73%)', // F9BCBC
    ];
  }

  // Obtener una paleta de colores con 5 tonos diferentes para los libros más prestados
  const librosPrestadosColors = generateCustomColors();

  // Crear el gráfico de libros más prestados
  const librosPrestadosChart = new Chart(document.getElementById("librosPrestadosChart"), {
    type: 'bar',
    data: {
      labels: librosPrestadosLabels,
      datasets: [{
        label: 'Más Prestados',
        data: librosPrestadosData,
        backgroundColor: librosPrestadosColors,
        borderWidth: 0, // Remove the border color
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Obtener una paleta de colores con 5 tonos diferentes para los libros mejor valorados
  const librosValoradosColors = generateCustomColors();

  // Crear el gráfico de libros mejor valorados
  const librosValoradosChart = new Chart(document.getElementById("librosValoradosChart"), {
    type: 'bar',
    data: {
      labels: librosValoradosLabels,
      datasets: [{
        label: 'Mejor valorados',
        data: librosValoradosData,
        backgroundColor: librosValoradosColors,
        borderWidth: 0, // Remove the border color
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          max: 5 // Establecer el máximo valor del eje Y para la valoración
        }
      }
    }
  });
    </script>
</body>
</html>