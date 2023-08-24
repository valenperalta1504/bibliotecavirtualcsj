<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar si existe un msj de error
require_once 'mensaje_de_error.php';
// verificar si hay una ventana emergente
require_once 'ventana_emergente.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';
// Conectar con la bd
require_once 'conexion_bd_libros.php';
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
    <div class="container3">
    <p class="titulo4">CAMBIAR CONTRASEÑA:</p>
    <p><p>
          <?php //Mostrar msj de error si es que existe
            if (isset($error)) {
              echo $error;}
          ?>
        <form method="post" action="actualizar_contraseña.php">
            <div class="form-group">
                <label for="contrasena">Contraseña actual:</label>
                <input class="lindo-input2" type="password" id="contrasena" name="contrasena" maxlength="30">
            </div>
            <div class="form-group">
                <label for="contrasena_nueva">Nueva contraseña:</label>
                <input class="lindo-input2" type="password" id="contrasena_nueva" name="contrasena_nueva" maxlength="30">
            </div>
            <div class="form-group">
                <label for="contrasena_nueva2">Confirmar nueva contraseña:</label>
                <input class="lindo-input2" type="password" id="contrasena_nueva2" name="contrasena_nueva2" maxlength="30">
            </div>
            <button class="botón" type="submit"  name="cambiar" value="cambiar">Cambiar</button>
        </form>
    </div>
  </main>
  <footer>
    <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
  </footer>
  <?php //Mostrar ventana emergente si hay
            if (isset($ventana_emergente)) {
                echo $ventana_emergente;
            }
            ?>
</body>
</html>

