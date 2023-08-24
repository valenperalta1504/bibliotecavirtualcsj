<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin.php';
// verificar si existe un msj de error
require_once 'mensaje_de_error.php';
// verificar si existe un msj de error
require_once 'ventana_emergente.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';

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
  
    <script>
        function actualizarNombreArchivo(input) {
  const archivoSeleccionado = input.files[0];
  const label = document.getElementById("customFileLabel");

  if (archivoSeleccionado) {
    label.textContent = archivoSeleccionado.name;
  } else {
    label.textContent = "Seleccionar archivo CSV";
  }
}
        </script>
</head>
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
<body>
    <div class="container3">
    <p class="titulo2">CARGAR CON ISBN:</p>
    <p><p>
    <?php //Mostrar msj de error si hay
        if (isset($error)) {
            echo $error;}
        ?>
  <form action="buscar_libro.php" method="post">
    <label for="isbn">ISBN:</label>
    <br><br>
    <input class="lindo-input2" type="text" name="isbn" id="isbn" >
    <br><br>
    <label for="isbn">Número de ejemplares:</label>
    <input class="lindo-input2" type="text" name="num_ejemplares" id="num_ejemplares" >
    <button class="botón" type="submit" value="Buscar">Cargar</button>
  </form>
    </div>
    
<?php //Mostrar ventana emergente si hay
            if (isset($ventana_emergente)) {
                echo $ventana_emergente;
            }
            ?>
</body>
</html>
