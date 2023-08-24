<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';
// verificar si hay una ventana emergente
require_once 'ventana_emergente.php';
// Verificar si existe una ventana emergente
require_once 'controles_admin.php';


$ISBN=$_SESSION['ISBN_LIBRO'];
$sql = "SELECT * FROM libros WHERE ISBN = '$ISBN'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $portada = '<a class="invisible">s</a>';
            $portada .= '<div id="portada-container3">';
            $portada .= '<img src="' . $row['Portada'] . '" alt="Portada del libro">';
            $portada .= '</div>';
            
          }
        }   

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" type="image/png" href="/logo6.png"/>
    <title>Biblioteca Virtual</title>
  <link rel="stylesheet" href="style18.css">
</head>
<body> 
    <div class="container">
		<nav>
			<ul>
      <?php
    		$resultado=mostrarChat();
    		mostrar_menu_catalogo($_SESSION['es_admin'], $resultado);
    		?>
			</ul>
		</nav>
		<header>
    <div class="container-menu">
        <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José" class="logo">
			</div>
		</header>
	</div>
  <main>


  <div class="container6">

  <div class="columna-60">
    <li class=mili>
  <div class="portada">
    <?php
    //Muestra la portada del libro
    echo $portada;
    ?>
  </div>
  <div class="info-libro">
  <br><br>
    <?php
    //Muestra info del libro elegido
    $info_libro = $_SESSION['info_libro'];
    echo $info_libro;
    ?>
  </div>
      </li>
</div>
  <div class="columna-40">
    <li>
    <a class="invisible">s</a>
      <p class="titulo2">RESERVAR LIBRO:</p>
      <?php
      // Llamada a la función pasando el valor de $es_admin
      LibroAgotado($_SESSION['ISBN_LIBRO']);
      ?>
      <?php
          // Llamada a la función pasando el valor de $es_admin
          $boton=botónHabilitado($_SESSION['ISBN_LIBRO']);
          
          ?>
      <?php
      //Muestra msj de error si lo hay 
      if (isset($_SESSION['error'])) {
        $error=$_SESSION['error'];
        echo $error;
        unset($_SESSION['error']);
      }
      else  {
        echo "<br><br>";
      }
      ?>
      <p><?php echo $datos_usuario['nombre_completo']; //Muestra nombre del usuario?> ¿Qué fecha deseas reservar tu libro?</p>
      
      <form method="post" action="guardar_reservacion.php">
        <label for="fecha">Fecha de reservación:</label>
        <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); //Establece fecha límite del día de hoy ?>" max="<?php echo date('Y-m-d', strtotime('+1 month')); ?>">
        <div class="search-container">
         <?php
         echo $boton;
         ?> 
      </form>
      <form method="post" action="volver.php">
        <button class="botón" type="submit">Volver al catálogo</button>
      </form>
      <p><p>
      <br><br>
      </div>
      <p class="invisible">s</p>
    </li>
  
    </div>
  </div>
  <?php
          // Llamada a la función pasando el valor de $es_admin
          $resultado=mostrarReseñas();
          if ($resultado=="visible"){
            echo '<li>
    <p class="titulo2">DEJA TU RESEÑA:</p>
    <p></p>';

if (isset($_SESSION['error2'])) {
    $error2 = $_SESSION['error2'];
    echo $error2;
    unset($_SESSION['error2']);
}

echo '<div class="foro">
    <form method="post" action="reseña.php">
    <input type="hidden" id="valoracion" name="valoracion" value="0"> <!-- Campo oculto para valoración -->
    <input type="hidden" id="ISBN" name="ISBN" value="' . $ISBN . '"> <!-- Campo oculto para valoración -->
    <input type="hidden" id="Nombre_alumno" name="Nombre_alumno" value="' . $datos_usuario['nombre_completo'] . '"> <!-- Campo oculto para valoración -->
    <input type="hidden" id="dni" name="dni" value="' . $datos_usuario['dni'] . '"> <!-- Campo oculto para valoración -->
    <div class="fila-container">
        <label for="valoración" >Valoración:</label>
        <label class="invisible">s</label>
        <div class="estrellas">
            <span class="estrella" data-valoracion="1">&#9733;</span>
            <span class="estrella" data-valoracion="2">&#9733;</span>
            <span class="estrella" data-valoracion="3">&#9733;</span>
            <span class="estrella" data-valoracion="4">&#9733;</span>
            <span class="estrella" data-valoracion="5">&#9733;</span>
        </div>
    </div>
    <textarea id="textoReseña" name="textoReseña"  maxlength="1000" placeholder=" Escribe tu reseña..."></textarea>';

$ban = Ban();
botónHabilitadoReseñas($_SESSION['ISBN_LIBRO'], $ban);

echo '</form>
</div>';


$query = "SELECT * FROM reseñas WHERE ISBN = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error in preparing the query: " . $conn->error);
}

$stmt->bind_param("s", $ISBN);
$stmt->execute();
$result = $stmt->get_result();

// Obtener un array con todas las reseñas
$reseñas = $result->fetch_all(MYSQLI_ASSOC);

// Cerrar la conexión
$stmt->close();

function generateStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= ($i <= $rating) ? '&#9733;' : '&#9734;';
    }
    return $stars;
}

echo '<p><p>
<p class="invisible">s</p>
<p class="titulo2">RESEÑAS:</p>
<P><P>
<div class="foro">';

if (empty($reseñas)) {
    echo '<p>No hay reseñas aún.</p>';
} else {
    foreach ($reseñas as $reseña) {
        $eliminar = '';
        if ($reseña['Dni'] == $datos_usuario['dni']) {
            $eliminar = '<form method="POST" action="eliminar_reseña.php">
                <input type="hidden" name="dni" value="' . $datos_usuario["dni"] . '">
                <input type="hidden" name="ISBN" value="' . $reseña["ISBN"] . '">
                <button type="submit" name="eliminar" value="eliminar" id="btn-editar"> 
                    <img src="eliminar_reseña.png" style="width: 25px; height: 25px;" alt="Eliminar reseña">  
                </button>
            </form>';
        }
        $eliminar2 = '<form method="POST" action="eliminar_reseña.php">
            <input type="hidden" name="dni" value="' . $reseña['Dni'] . '">
            <input type="hidden" name="ISBN" value="' . $reseña["ISBN"] . '">
            <button type="submit" name="eliminar" value="eliminar" id="btn-editar"> 
                <img src="eliminar_reseña.png" style="width: 25px; height: 25px;" alt="Eliminar reseña">  
            </button>
        </form>';

        echo '<div class="publicacion">
            <div class="search-container">
                <p class="titulo2">' . $reseña['Nombre_alumno'] . ':</p>
                <p class="invisible">s</p>
                <p>  ' . generateStars($reseña['Valoración']) . '</p>
                <p class="invisible">s</p>';

        if (isset($eliminar)) {
            echo $eliminar;
        }
        if ($datos_usuario['id'] == 1) {
            echo $eliminar2;
        }

        echo '</div>
            <textarea style="width: 1150px; height: 100px; overflow: auto; resize: none; background-color: transparent;" disabled>' . $reseña['Reseña'] . '</textarea>
        </div>';
    }
}
}
?>
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const estrellas = document.querySelectorAll('.estrella');
      const valoracionInput = document.getElementById('valoracion');

      estrellas.forEach(estrella => {
        estrella.addEventListener('click', () => {
          const valoracion = parseInt(estrella.dataset.valoracion);
          valoracionInput.value = valoracion;
          resaltarEstrellas(valoracion);
        });

        estrella.addEventListener('click', () => {
          const valoracion = parseInt(estrella.dataset.valoracion);
          resaltarEstrellas(valoracion);
        });

        estrella.addEventListener('click', () => {
          const valoracion = parseInt(valoracionInput.value);
          resaltarEstrellas(valoracion);
        });
      });

      resaltarEstrellas(0);
    });

    function resaltarEstrellas(numeroEstrellas) {
      const estrellas = document.querySelectorAll('.estrella');
      estrellas.forEach(estrella => {
        const valoracion = parseInt(estrella.dataset.valoracion);
        estrella.innerHTML = valoracion <= numeroEstrellas ? '&#9733;' : '&#9734;';
      });
    }
  </script>
  <footer>
    <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
  </footer>
  
  <?php
            if (isset($ventana_emergente)) {
                echo $ventana_emergente;
            }
            ?>
</body>
</html>
