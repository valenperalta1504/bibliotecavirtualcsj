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
require_once 'obtener_libros.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';

// Obtener todos los registros
$registros = obtenerRegistros();
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
    <div class="container8">
    <p class="titulo">TABLA DE LIBROS:</p>
    <form method="get" action="modificar_libros.php">
  <div class="search-container">
    <input type="text" name="search" id="search" class="lindo-input2" placeholder="Ingrese su búsqueda...">
    <button type="submit" class="search-button">Buscar</button>
    <a class="invisible">s<a>
    <button class="botón" id="btnImprimirTabla">Imprimir</button>
  </div>
</form>
   <table style="border-collapse: collapse;">
    <tr>
        <th style="padding: 4px; font-size: 12px;">id</th>
        <th style= padding: 4px;">Título</th>
        <th style="padding: 4px;">Portada</th>
        <th style="padding: 4px;">Autor</th>
        <th style="padding: 4px;">Editorial</th>
        <th style="padding: 4px;">Año de publicación</th>
        <th style="padding: 4px;">ISBN/Código Interno</th>
        <th style="padding: 4px;">Descripción</th>
        <th style="padding: 4px;">Categoría</th>
        <th style="padding: 4px;">Ejemplares disponibles</th>
        <th style="padding: 4px;">Stock</th>
        <th style="padding: 4px;">Acciones</th>
    </tr>
        <?php foreach ($registros as $registro): ?>
            <tr>
                <td class="letracute"><?php echo $registro["id"]; ?></td>
              <td ><?php echo $registro["Título"]; ?></td>

                <td><?php 
                echo '<div id="portada-container">';
                echo '<img src="' . $registro["Portada"] . '" alt="Portada del libro">';
                echo '</div>';?>
                </td>
                <td><?php echo $registro["Autor"]; ?></td>
                <td><?php echo $registro["Editorial"]; ?></td>
                <td><?php echo $registro["Año de publicación"]; ?></td>
                <td><?php echo $registro["ISBN"]; ?></td>
                <td class="letracute">
                <?php $descripcion = $registro["Descripción"];
                $primeros_10_caracteres = strlen($descripcion) > 57 ? substr($descripcion, 0, 57) . "..." : $descripcion;
                echo $primeros_10_caracteres;?>   
                </td>
                <td><?php echo $registro["Categorías"]; ?></td>
                <td><?php echo $registro["Número de ejemplares disponibles"]; ?></td>
                <td><?php echo $registro["Stock"]; ?></td>
                <td>
                <div class="containerdeboton">
    <form method="POST" action="actualizar_libros2.php">
        <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
        <button type="submit" name="editar" value="editar" id="btn-editar"> 
        <img src="modificar.png" alt="Portada del libro">
     </button>
    </form>
</div>
<p><p>
<div class="containerdeboton">
    <form method="POST" action="eliminar_libro.php">
        <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
        <button type="submit" name="eliminar" value="eliminar" id="btn-editar"> 
        <img src="eliminar.png" alt="Portada del libro">  
    </button>
    </form>
</div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
        </div>
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
    <script>
    // Función para imprimir el contenido de la tabla
    function imprimirTabla() {
        // Abre una ventana de impresión
        window.print();
    }

    // Asignar el evento clic al botón de impresión
    document.getElementById('btnImprimirTabla').addEventListener('click', imprimirTabla);
</script>
</body>
</html>