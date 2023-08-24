<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'conexion_bd_libros.php';
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
require_once 'conexion_bd_libros.php';
?>

<!DOCTYPE html>
<html lang="en">
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
    <div class="container3">
    <p class="titulo4">INSERTAR REGISTRO:</p>
    <p><p>
            <?php //Mostrar msj de error
                if (isset($error)) {
                    echo $error;}
            ?> 
        <form method="post" action="guardar_registro2.php">
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <br><br>
                <input class="lindo-input2" type="text" id="nombre" name="nombre" maxlength="30">
                <br><br>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <a class="opcional"> *opcional</a>
                <br><br>
                <input class="lindo-input2" type="text" id="email" name="email" maxlength="319">
                <br><br>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <br><br>
                <input  class="lindo-input2" type="password" id="contrasena" name="contrasena" maxlength="30">
                <br><br>
            </div>
            <div class="form-group">
                <label for="confirmar-contrasena">Confirmar contraseña:</label>
                <br><br>
                <input class="lindo-input2" type="password" id="confirmar-contrasena" name="confirmar-contrasena" maxlength="30">
                <br><br>
            </div>
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
            <div class="form-group">
    <label for="dni">DNI:</label>
    <br><br>
    <input type="number" id="dni" class="lindo-input2" name="dni" oninput="javascript: if (this.value.length > 8) this.value = this.value.slice(0, 8);">
    <br><br>
</div>
            <div class="form-group">
                <label for="usu">Usuario:</label>
                <br><br>
                <input type="text" class="lindo-input2" id="usu" name="usu" maxlength="30">
                <br><br>
            </div>
            <button class="botón" type="submit" name="registrarse" value="registrarse">Insertar</button>
        </form>
    </div>
    <footer>
        <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
    </footer>
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
