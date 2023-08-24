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
// verificar si hay ventana emergente
require_once 'verificar_modificacion_libro.php'; 
// Verificar la sesión del usuario
require_once 'controles_admin.php';
require_once 'conexion_bd_libros.php';


$libro = $_SESSION['libros'];
$id = isset($libro['id']) ? $libro['id'] : '';
$Título = isset($libro['Título']) ? $libro['Título'] : '';
$Portada = isset($libro['Portada']) ? $libro['Portada'] : '';
$Autor = isset($libro['Autor']) ? $libro['Autor'] : '';
$Editorial = isset($libro['Editorial']) ? $libro['Editorial'] : '';
$AñoDePublicación = isset($libro['Año de publicación']) ? $libro['Año de publicación'] : '';
$ISBN = isset($libro['ISBN']) ? $libro['ISBN'] : '';
$Descripción = isset($libro['Descripción']) ? $libro['Descripción'] : '';
$Categorías = isset($libro['Categorías']) ? $libro['Categorías'] : '';
$NúmeroDeEjemplaresDisponibles = isset($libro['Número de ejemplares disponibles']) ? $libro['Número de ejemplares disponibles'] : '';
$Stock = isset($libro['Stock']) ? $libro['Stock'] : '';
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
    <p class="titulo4">MODIFICAR LIBRO:</p>
    <p><p>
    <?php //Mostrar msj de error
                if (isset($error)) {
                    echo $error;}
            ?>
    <form method="POST" action="actualizar_libros2.php">
    <label for="titulo">Título:</label>
    <h1></h1>    
    <input class="lindo-input2" type="text" name="titulo" value="<?php echo $Título; ?>">
    <h1></h1> 
    <label for="portada">Portada:</label>
    <h1></h1>    
    <input class="lindo-input2"type="text" name="portada" value="<?php echo $Portada; ?>">
    <h1></h1>   
    <label for="autor">Autor:</label>
    <h1></h1> 
    <input class="lindo-input2"type="text" name="autor" value="<?php echo $Autor; ?>">
    <h1></h1> 
    <label for="editorial">Editorial:</label>
    <h1></h1>    
    <input class="lindo-input2" type="text" name="editorial" value="<?php echo $Editorial; ?>">
    <h1></h1> 
    <label for="añodepublicación">Año de publicación:</label>
    <h1></h1>
    <input class="lindo-input2" type="text" name="añodepublicación" value="<?php echo $AñoDePublicación; ?>">
    <h1></h1> 
    <label for="isbn">ISBN/Código Interno:</label>
    <h1></h1>   
    <input class="lindo-input2" type="text" name="isbn" value="<?php echo $ISBN; ?>">
    <h1></h1> 
    <label for="descripción">Descripción:</label>
    <h1></h1>   
<textarea style="resize: none;" id="descripción" name="descripción" rows="5" cols="50"><?php echo $Descripción; ?></textarea>
<h1></h1> 
    <label for="categorías">Categoría:</label>
    <h1><h1> 
    <input class="lindo-input2" type="text" name="categorías" value="<?php echo $Categorías; ?>">
    <h1></h1> 
    <label for="númerodeejemplaresdisponibles">Número de ejemplares disponibles:</label>
    <h1></h1>   
    <input class="lindo-input2" type="text" name="númerodeejemplaresdisponibles" value="<?php echo $NúmeroDeEjemplaresDisponibles; ?>">
    <h1></h1> 
    <label for="stock">Stock:</label>
    <h1></h1>   
    <input class="lindo-input2" type="text" name="stock" value="<?php echo $Stock; ?>">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <h1></h1> 
    <button type="submit" name="actualizar" value="actualizar">Actualizar</button>
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
