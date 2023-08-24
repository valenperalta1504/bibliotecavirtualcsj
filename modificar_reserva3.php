<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// Verificar la sesión del usuario
require_once 'verificar_admin.php';
// Verificar la sesión del usuario
require_once 'verificar_permiso.php';
// Verifica si hay una ventana emergente
require_once 'ventana_emergente.php';
// Verifica si hay un msj de error
require_once 'mensaje_de_error.php';
// Verifica si hay un msj de error
require_once 'obtener_libros.php';
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
    <div class="container3">
    <p class="titulo4">MODIFICAR RESERVACIÓN:</p>
    <p><p>
    <?php //Muestra msj de error si existe
            if (isset($error)) {
                echo $error;
            }
        ?>
    <?php
                // Call the function and echo the generated form fields
                echo generateFormFields($_SESSION['reserva']);
            ?>
    </div>
  </main>
  <footer>
    <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
  </footer>
  <?php
            if (isset($ventana_emergente)) {
                echo $ventana_emergente;
            }
            ?>
            <script>
        // Function to handle changes in the "Nivel" select element
        function handleNivelChange() {
            const nivelSelect = document.getElementById('Nivel');
            const cursoSelect = document.getElementById('Curso');
            const divisionSelect = document.getElementById('División');

            // Get the selected value from "Nivel" select
            const selectedNivel = nivelSelect.value;

            // Enable or disable "Curso" and "Division" based on "Nivel" selection
            if (selectedNivel === 'Docente' || selectedNivel === 'Personal Administrativo') {
                cursoSelect.value = ''; // Reset "Curso" selection
                divisionSelect.value = ''; // Reset "Division" selection
                cursoSelect.disabled = true;
                divisionSelect.disabled = true;
            } else {
                cursoSelect.disabled = false;
                divisionSelect.disabled = false;
            }

            // Change the options in "Curso" based on "Nivel" selection
            if (selectedNivel === 'Inicial') {
                cursoSelect.innerHTML = `
                    <option value="" disabled selected></option>
                    <option value="Sala de 4">Sala de 4</option>
                    <option value="Sala de 5">Sala de 5</option>
                `;
            } 
        }

        // Attach event listener to "Nivel" select element
        document.getElementById('Nivel').addEventListener('change', handleNivelChange);

        // Initial call to set the initial state of the form
        handleNivelChange();
    </script>
</body>
</html>

