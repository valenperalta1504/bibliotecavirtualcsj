<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// Conexión con la bd
require_once 'conexion_bd_libros.php';
//mostrar mis reservaciones
require_once 'mostrar_mis_reservaciones.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';
// Verificar la sesión del usuario
require_once 'verificar_admin2.php';

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
        <li>
      <p class="titulo2">MI CUENTA:</p>
          <p>Bienvenido/a, <?php echo $datos_usuario['nombre_completo']; ?></p>
          <form method="post" action="borrar_datos.php">
            <input type="submit" value="Cerrar sesión" class="botón">
          </form>
</li>
<li>
<p class="titulo2">MIS PRESTAMOS:</p>
          <p>Cantidad: <?php echo $cantidad_libros_prestados;//Muestra cantidad de libros reservados ?></p>
            <?php
              echo $mis_prestamos_final //Muestra los titulos y fechas reservadas
            ?>
</li>
</div>
      </ul>
    </section>
  </main>
  <p class="a"> ‎  </p>
  <footer class="footerpro">
    <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
  </footer>
</body>
</html>
