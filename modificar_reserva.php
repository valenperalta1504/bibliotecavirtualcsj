<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar si existe un msj de error
require_once 'mensaje_de_error.php';
// verificar si hay permiso para entrar a la pag
require_once 'verificar_reserva_modificar.php';
// verificar si existe una ventana emergente
require_once 'ventana_emergente.php';
// verificar si existe una ventana emergente
require_once 'controles_admin.php';
// Verificar la sesión del usuario
require_once 'verificar_admin2.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';

$ISBN=$_SESSION['ISBN_LIBRO'];
$sql = "SELECT * FROM libros WHERE `ISBN` = $ISBN";
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
    		mostrar_menu_micuenta($_SESSION['es_admin'], $resultado);
    		?>
			</ul>
		</nav>
		<header>
    <div class="container-menu">
        <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José" class="logo">
				<nav class="menu3">
					<ul>
          <?php
    		// Llamada a la función pasando el valor de $es_admin
    		mostrar_menu_micuenta2($_SESSION['es_admin']);
    		?>
					</ul>
				</nav>
			</div>
		</header>
	</div>
  <main>
    <section class="mi-cuenta">
	<ul>
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
    <p class="titulo2">MODIFICAR RESERVA:</p>
    <?php
      //Muestra msj de error si lo hay 
      if (isset($error)) {
        echo $error;
      }
      else  {
        echo "<br><br>";
      }
      ?>
            <p><?php echo $datos_usuario['nombre_completo'];?> ¿Deseas modificar la fecha de tu reserva?</p>
           
            <form method="post" action="modificar_reservacion.php">	
			      <label for="fecha">Fecha de reservación:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $_SESSION['FECHA'] ?? date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); //mostrar fecha en que lo reservó?>" max="<?php echo date('Y-m-d', strtotime('+1 month')); ?>">
            <div class="button-container">
            <button class="botón" type="submit" name= "modificar" value="modificar">Modificar</button>
                </form> 
            <form method="post" action="eliminar_reservacion.php">	
            <button class="botón" type="submit" name="eliminar" value="eliminar">Eliminar</button>
            </form>
            <form method="post" action="volver.php">

            <button class="botón" type="submit">Volver al catálogo</button>
            </div>
            </form>
            <p><p>
      <br><br>
      </div>
      
    </li>
                </div>
    </section>
  </main>
  <footer>
    <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
  </footer>
  <?php //Mostrar ventana emergente
            if (isset($ventana_emergente)) {
                echo $ventana_emergente;
            }
            ?>
</body>
</html>
