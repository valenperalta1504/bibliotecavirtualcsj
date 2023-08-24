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
                <ul>
                    <li class="dropdown">
                    <a href="#">CARGAR LIBROS</a>
                        <ul class="dropdown-content">
                        <li><a href="subir_libros.php">CON ISBN</a></li>
                        <li><a href="cargar_libro_manual.php">MANUAL</a></li>
                        <li><a href="subir_archivocsv.php">CSV</a></li>
                        </ul>
                        </li>
						<li><a href="modificar_reservaciones.php">MODIFICAR RESERVACIONES</a></li>
						<li><a href="modificar_prestamos.php">MODIFICAR PRESTAMOS</a></li>
						<li><a href="modificar_libros.php">MODIFICAR LIBROS</a></li>
						<li><a href="modificar_registros.php">MODIFICAR REGISTROS</a></li>
						<li><a href="historial_prestamos.php">HISTORIAL DE PRESTAMOS</a></li>
					</ul>
                </nav>
			</div>
		</header>
	</div>	
<body>
<div class="container3">
    <form action="subir_csv_curso.php" method="post" enctype="multipart/form-data">
    <p class="titulo2">SUBIR CSV DE CURSO:</p>
    <p><p>
    <?php 
                if (isset($_SESSION['error2'])) {
                    $error2 = $_SESSION['error2'];
                    echo $error2; 
                    unset($_SESSION['error2']);
                }
        ?>
       <label for="archivo" class="custom-file-label" id="customFileLabel">Seleccionar archivo CSV</label>
<input type="file" name="archivo" id="archivo" class="custom-file-input" onchange="actualizarNombreArchivo(this)">
        <p><p>
        <div class="form-group">
                <h4>Nivel:</h4>
                <select id="nivel" name="nivel">
                <option value="" disabled selected></option>
                    <option value="Secundario">Secundario</option>
                    <option value="Primario">Primario</option>
                    <option value="Inicial">Inicial</option>
                    <option value="Docente">Docente</option>
                    <option value="Personal Administrativo">Personal Administrativo</option>
                </select>
            </div>
            <div class="form-group">
                <h4>Curso:</h4>
                <select id="curso" name="curso">
                <option value="" disabled selected></option>
                    <option value="1ro">1ro</option>
                    <option value="2do">2do</option>
                    <option value="3ro">3ro</option>
                    <option value="4to">4to</option>
                    <option value="5to">5to</option>
                    <option value="6to">6to</option>
                </select>
            </div>
            <div class="form-group">
                <h4>División:</h4>
                <select id="division" name="division">
                <option value="" disabled selected></option> 
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select>
            </div>
        <input class="botón" type="submit" name="Cargar" value="Cargar">
    </form>
    </div> 
<?php //Mostrar ventana emergente si hay
            if (isset($ventana_emergente)) {
                echo $ventana_emergente;
            }
            ?>
            <script>
        // Function to handle changes in the "Nivel" select element
        function handleNivelChange() {
            const nivelSelect = document.getElementById('nivel');
            const cursoSelect = document.getElementById('curso');
            const divisionSelect = document.getElementById('division');

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
            } else {
                cursoSelect.innerHTML = `
                    <option value="" disabled selected></option>
                    <option value="1ro">1ro</option>
                    <option value="2do">2do</option>
                    <option value="3ro">3ro</option>
                    <option value="4to">4to</option>
                    <option value="5to">5to</option>
                    <option value="6to">6to</option>
                `;
            }
        }

        // Attach event listener to "Nivel" select element
        document.getElementById('nivel').addEventListener('change', handleNivelChange);

        // Initial call to set the initial state of the form
        handleNivelChange();
    </script>
</body>
</html>
