<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin2.0.php';
// verificar si existe un mensaje de error
require_once 'mensaje_de_error.php';
// verificar si hay ventana emergente
require_once 'ventana_emergente.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';
require_once 'conexion_bd_libros.php';
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
        <div class="container3">
        <p class="titulo2">CARGAR MANUALMENTE:</p>
        <p><p>
            <?php //Mostrar msj de error si hay
            if (isset($error)) {
                echo $error;
            }
            ?>
            <form method="post" action="carga_manual.php">
                <div class="form-group">
                    <label for="Título">Título del libro:</label>
                    <input class="lindo-input2" type="text" id="Título" name="Título" maxlength="150">
                </div>
                <div class="form-group">
                    <label for="Portada">URL de la portada:</label>
                    <input class="lindo-input2" type="text" id="Portada" name="Portada" maxlength="1000">
                </div>
                <div class="form-group">
                    <label for="Autor">Autor:</label>
                    <h1><h1>
                    <input class="lindo-input2" type="text" id="Autor" name="Autor" maxlength="30">
                </div>
                <div class="form-group">
                    <label for="Editorial">Editorial:</label>
                    <h1><h1>
                    <input class="lindo-input2" type="text" id="Editorial" name="Editorial" maxlength="30">
                </div>
                <div class="form-group">
                    <label for="ISBN">ISBN/Código interno:</label>
                    <h1><h1>
                    <input class="lindo-input2" type="text" id="ISBN" name="ISBN" oninput="javascript: if (this.value.length > 13) this.value = this.value.slice(0, 13);">
                </div>
                <div class="form-group">
                    <label for="Año_de_publicación">Año de publicación:</label>
                    <input class="lindo-input2" type="text" id="Año_de_publicación" name="Año_de_publicación" oninput="javascript: if (this.value.length > 4) this.value = this.value.slice(0, 4);">
                </div>
                <div class="form-group">
                    <label for="descripción">Descripción:</label>
                    <textarea style="resize: none;" id="descripción" name="descripción" rows="5" cols="50" maxlength="1200"></textarea>
                </div>
                 <div class="form-group">
                <h4>Categorías:</h4>
                <select id="Categorías" name="Categorías">
                <option value="" disabled selected></option>
                    <option value="Clásicos">Clásicos</option>
                    <option value="Terror, Misterio y Suspenso">Terror, Misterio y Suspenso</option>
                    <option value="Ficción Niños">Ficción Niños</option>
                    <option value="Historia, Naturaleza y Ciencia">Historia, Naturaleza y Ciencia</option>
                    <option value="Infantiles">Infantiles</option>
                    <option value="Elige tu propia aventura">Elige tu propia aventura</option>
                    <option value="Ficción Juvenil">Ficción Juvenil</option>
                    <option value="Religiosos">Religiosos</option>
                    <option value="Filosofía">Filosofía</option>
                    <option value="Ciudadanía y Participación">Ciudadanía y Participación</option>
                    <option value="ESI">ESI</option>
                    <option value="Sociología">Sociología</option>
                    <option value="Ficción Niños">Ficción Niños</option>
                    <option value="Antropología">Antropología</option>
                    <option value="Derecho">Derecho</option>
                    <option value="Economía">Economía</option>
                    <option value="Administración">Administración</option>
                    <option value="Geografía">Geografía</option>
                    <option value="Manual">Manual</option>
                    <option value="Historia">Historia</option>
                    <option value="Ciencias Sociales">Ciencias Sociales</option>
                    <option value="Ciencias Naturales">Ciencias Naturales</option>
                    <option value="Lengua y Literatura">Lengua y Literatura</option>
                    <option value="Matemática">Matemática</option>
                    <option value="Física y Química">Física y Química</option>
                    <option value="Tecnología">Tecnología</option>
                    <option value="Ambiental">Ambiental</option>
                    <option value="Medicina">Medicina</option>
                    <option value="Biología">Biología</option>
                    <option value="Ciudadanía y Participación">Docentes</option>

                </select>
            </div>
                <div class="form-group">
                    <label for="Número_de_ejemplares">Número de ejemplares:</label>
                    <input class="lindo-input2" type="number" id="Número_de_ejemplares" name="Número_de_ejemplares" oninput="javascript: if (this.value.length > 2) this.value = this.value.slice(0, 2);">
                </div>
                <button class="botón" type="submit">Cargar</button>
            </form>
        </div>
        <?php //Mostrar ventana emergente si hay
            if (isset($ventana_emergente)) {
                echo $ventana_emergente;
            }
            ?>
</body>
</html>
