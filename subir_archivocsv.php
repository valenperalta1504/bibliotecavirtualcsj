<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin2.0.php';
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
    <form action="subir_csv.php" method="post" enctype="multipart/form-data">
    <p class="titulo2">SUBIR CSV:</p>
    <p><p>
    <?php 

                if (isset($_SESSION['carga'])) {
                    $carga = $_SESSION['carga'];
                    echo $carga;
                    unset($_SESSION['carga']);
                }
                if (isset($_SESSION['error2'])) {
                    $error2 = $_SESSION['error2'];
                    echo $error2;
                    unset($_SESSION['error2']);
                }
        ?>
       <label for="archivo" class="custom-file-label" id="customFileLabel">Seleccionar archivo CSV</label>
<input type="file" name="archivo" id="archivo" class="custom-file-input" onchange="actualizarNombreArchivo(this)">
        <p><p>
        <input class="botón" type="submit" name="Cargar" value="Cargar">
    </form>
    </div> 
<?php //Mostrar ventana emergente si hay
            if (isset($ventana_emergente)) {
                echo $ventana_emergente;
            }
            ?>
</body>
</html>
