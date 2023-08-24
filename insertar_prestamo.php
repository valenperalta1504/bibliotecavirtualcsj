<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin.php';
// verificar si existe un mensaje de error
require_once 'mensaje_de_error.php';
// verificar si hay ventana emergente
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
        <body>
        <div class="container3">
    <p class="titulo4">INSERTAR PRESTAMO:</p>
    <p><p>
    <?php //Mostrar msj de error
                if (isset($error)) {
                    echo $error;}
            ?>
    <form method="POST" action="nuevo_prestamo.php">
    <label for="Nombre_alumno">Nombre del alumno:</label>
    <input class="lindo-input2" type="text" name="Nombre_alumno" maxlength="30">
    <br>
    <label for="Dni">Dni:</label>
    <h1></h1>
    <input type="number" class="lindo-input2" name="Dni" oninput="javascript: if (this.value.length > 8) this.value = this.value.slice(0, 8);">
    <br>
    <label for="Nivel">Nivel:</label>
    <br>
    <br>
    <br>
    <select id="Nivel" name="Nivel">
        <option value="" disabled selected></option>
        <option value="Secundario">Secundario</option>
        <option value="Primario">Primario</option>
        <option value="Inicial">Inicial</option>
    </select>
    <br>
    <label for="Curso">Curso:</label>
    <br>
    <br>
    <br>
    <select id="Curso" name="Curso">
        <option value="" disabled selected></option>
        <option value="1ro">1ro</option>
        <option value="2do">2do</option>
        <option value="3ro">3ro</option>
        <option value="4to">4to</option>
        <option value="5to">5to</option>
        <option value="6to">6to</option>
    </select>
    <br>
    <label for="División">División:</label>
    <br>
    <br>
    <br>
    <select id="División" name="División">
    <option value="" disabled selected></option>
        <option value="A">A</option>
        <option value="B">B</option>
    </select>
    <br>
    <label for="isbn">ISBN/Código interno:</label>
    <input type="text" class="lindo-input2" name="isbn" oninput="javascript: if (this.value.length > 13) this.value = this.value.slice(0, 13);">
    <br>
    <button class="botón" type="submit" name="insertar" value="insertar">Insertar</button>
</form>
</main>
<footer>
	<p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
</footer>
<?php
  //muestra ventana emergente si es que hay
  if (isset($ventana_emergente)) {
    echo $ventana_emergente;
  }
  ?>
</body>
