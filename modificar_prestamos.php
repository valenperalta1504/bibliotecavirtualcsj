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
$prestamos = obtenerPrestamos();
?> 
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<link rel="icon" type="image/png" href="/logo6.png"/>
    <title>Biblioteca Virtual</title>
    <link rel="stylesheet" href="style20.css">
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
    <p class="titulo">TABLA DE PRESTAMOS:</p>
    <form method="get" action="modificar_prestamos.php">
  <div class="search-container">
    <input type="text" name="search" id="search" class="lindo-input" placeholder="Ingrese su búsqueda...">
    <button type="submit" class="search-button">Buscar</button>
    <p class="invisible">s<p>
    </form>
    <div class="containerdeboton">
    <button class="botón">
    <a href="insertar_prestamo.php">Insertar Prestamo</a>
    </button>
    <button class="botón" id="btnImprimirTabla">Imprimir</button>
  </div>
  </div>
    <table>
        <tr>
            <th>id</th>
            <th>Título</th>
            <th>Portada</th>
            <th>ISBN/Código interno</th>
            <th>Usuario</th>
            <th>Dni</th>
            <th>Nivel</th>
            <th>Curso</th>
            <th>División</th>
            <th>Fecha de retiro</th>
            <th>Fecha de devolución</th>
            <th>Acciones</th>
            <th class="invisible">sssssssss</th>
        </tr>
        <?php foreach ($prestamos as $registro): ?>
            <tr>
                <td><?php echo $registro["id"]; ?></td>
                <td><?php echo $registro["Nombre_libro"]; ?></td>
                <td><?php 
                echo '<div id="portada-container">';
                echo '<img src="' . $registro["Portada"] . '" alt="Portada del libro">';
                echo '</div>';?>
                </td>
                <td><?php echo $registro["ISBN"]; ?></td>
                <td><?php echo $registro["Nombre_alumno"]; ?></td>
                <td><?php echo $registro["Dni"]; ?></td>
                <td><?php echo $registro["Nivel"]; ?></td>
                <td><?php echo $registro["Curso"]; ?></td>
                <td><?php echo $registro["División"]; ?></td>
                <td><?php echo $registro["Fecha_retiro"]; ?></td>
                <td><?php echo $registro["Fecha_devolución"]; ?></td>
                
                <td>
                <div class="containerdeboton">
                        <form method="POST" action="libro_devuelto.php">
                            <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
                            <button class="botón" type="submit" name="devuelto" value="devuelto"> Devuelto </button>
                        </form>
                    </div>
                    <p><p>
                    <div class="containerdeboton">
    <form method="POST" action="modificar_prestamo2.php">
        <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
        <button type="submit" name="modificar" value="modificar" id="btn-editar"> 
        <img src="modificar.png" alt="Portada del libro">
     </button>
    </form>
</div>
<p><p>
<div class="containerdeboton">
    <form method="POST" action="eliminar_prestamo.php">
        <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
        <button type="submit" name="eliminar" value="eliminar" id="btn-editar"> 
        <img src="eliminar.png" alt="Portada del libro">  
    </button>
    </form>
</div>
                </td>
                <td>
                  <?php
                  $hasOverdueDevolutions = PrestamoVencido2($registro["id"]);

                  if ($hasOverdueDevolutions) {
                    echo '<span class="notificacion5"> Vencido! </span>';
                  } 
                  ?>  
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