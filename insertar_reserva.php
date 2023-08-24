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
    <p class="titulo4">INSERTAR RESERVA:</p>
    <p><p>
    <?php //Mostrar msj de error
                if (isset($error)) {
                    echo $error;}
            ?>
    <form method="post" action="nueva_reserva.php">
    <div class="form-group">
                <label for="Nombre_alumno">Nombre completo:</label>
                <input class="lindo-input2" type="text" id="Nombre_alumno" name="Nombre_alumno" maxlength="30" >
            </div>
            <div class="form-group">
                <h4>Nivel:</h4>
                <select id="Nivel" name="Nivel">
                <option value="" disabled selected></option>
                    <option value="Secundario">Secundario</option>
                    <option value="Primario">Primario</option>
                    <option value="Inicial">Inicial</option>
                </select>
            </div>
            <div class="form-group">
                <h4>Curso:</h4>
                <select id="Curso" name="Curso">
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
                <select id="División" name="División">
                <option value="" disabled selected></option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select>
            </div>
            <div class="form-group">
                <label for="Dni">Dni:</label>
                <h1><h1>
                <input class="lindo-input2" type="number" id="Dni" name="Dni" oninput="javascript: if (this.value.length > 8) this.value = this.value.slice(0, 8);">
            </div>
            <div class="form-group">
                <label for="ISBN">ISBN/Código interno:</label>
                <h1><h1>
                <input class="lindo-input2" type="number" id="ISBN" name="ISBN" oninput="javascript: if (this.value.length > 13) this.value = this.value.slice(0, 13);">
            </div>
        <div class="form-group">
            <label for="Fecha">Fecha:</label>
            <p><p>
            <input type="date" id="Fecha" name="Fecha" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 month')); ?>">
        </div>
        <button class="botón" type="submit" name="insertar" value="insertar">Insertar
        </button>
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
