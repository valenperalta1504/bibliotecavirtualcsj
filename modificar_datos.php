<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// Carga los datos actuales como predeterminados
require_once 'datos_actuales_predeterminados.php';
// Verifica si hay una ventana emergente
require_once 'ventana_emergente.php';
// Verifica si hay un msj de error
require_once 'mensaje_de_error.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';

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
    <p class="titulo4">MODIFICAR MIS DATOS:</p>
    <p><p>
        <form method="post" action="actualizar_datos.php" >
        <?php //Muestra msj de error si existe
                if (isset($error)) {
                    echo $error;}
            ?>
            <div class="form-group">
                <label for="nombre_completo">Nombre completo:</label>
                <br><br>
                <input type="text" class="lindo-input2" id="nombre_completo" name="nombre_completo" maxlength="30" value="<?php echo $nombre;?>" >
                <br><br>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <a class="opcional"> *opcional</a>
                <br><br>
                <input type="text" id="email" class="lindo-input2" name="email" maxlength="319" value="<?php echo $email;?>" >
                <br><br>
            </div>
            <div class="form-group">
                <h4>Nivel:</h4>
                <select id="nivel" name="nivel">
                    <?php echo $nivel;?>
                </select>
            </div>
            <div class="form-group">
                <h4>Curso:</h4>
                <select id="curso" name="curso">
                    <?php echo $curso;?>
                </select> 
            </div>
            <div class="form-group">
                <h4>División:</h4>
                <select id="division" name="division">
                    <?php echo $division;?>
                </select>
            </div>
            <div class="form-group">
                <label for="dni">DNI:</label>
                <br><br>
                <input type="number" id="dni" name="dni" class="lindo-input2" oninput="javascript: if (this.value.length > 8) this.value = this.value.slice(0, 8);" value="<?php echo $dni;?>" required>
                <br><br>
            </div>
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <br><br>
                <input type="text" id="usuario" name="usuario" class="lindo-input2" maxlength="30" value="<?php echo $usuario;?>" required>
                <br><br>
            </div>
            <button class="botón" type="submit" name="modificar" value="modificar">Modificar</button>
        </form>
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
            } 
            }
        

        // Attach event listener to "Nivel" select element
        document.getElementById('nivel').addEventListener('change', handleNivelChange);

        // Initial call to set the initial state of the form
        handleNivelChange();
    </script>
</body>
</html>

