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
// Verificar la sesión del usuario
require_once 'conexion_bd_libros.php';

// Obtener todos los registros
$reservaciones = obtenerReservaciones();

$sql = "SELECT Estado FROM habilitar_registro WHERE id = 3";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$estado = $row['Estado'];
$final="";
if ($estado == "ocultar") {
    $final="Habilitar Reseñas";
} else {
    $final="Deshabilitar Reseñas";
}

if (isset($_SESSION['isChecked'])) {
    $isChecked = $_SESSION['isChecked'];
    unset($_SESSION['isChecked']);
} else {
    $isChecked = "";
}
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
    <p class="titulo">TABLA DE RESERVACIONES:</p>
    <form method="get" action="modificar_reservaciones.php">
  <div class="search-container">
    <input type="text" name="search" id="search" class="lindo-input" placeholder="Ingrese su búsqueda...">
    <button type="submit" class="search-button">Buscar</button>
    <p class="invisible">s<p>
    </form>
    <div class="containerdeboton">

    </div>
    <a class="invisible">
        s
        <a>
    <button class="botón" id="btnImprimirTabla">Imprimir</button>
  </div>
  <div class="search-container">
  <button class="botón">
    <a href="insertar_reserva.php">Insertar Reserva</a>
    </button>
    <a class="invisible">s<a>
    <div class="containerdeboton">
    <form method="post" action="ocultar_reseñas.php">
    <button name="ocultar" value="ocultar" type="submit" class="botón"><?php 
    echo $final;
    ?></button>
    </form>
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
            <th>Fecha</th>
            <th>Acciones</th>
            <th class="invisible">sssssssss</th>
        </tr>
        <?php foreach ($reservaciones as $registro): ?>
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
                <td><?php echo $registro["Fecha"]; ?></td>
                
                <td>
                <div class="containerdeboton">
                        <form method="POST" action="libro_retirado.php">
                            <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
                            <button class="botón" type="submit" name="retirar" value="retirar"> Retirado </button>
                        </form>
                    </div>
                    <p><p>
                    <div class="containerdeboton">
    <form method="POST" action="modificar_reserva2.php">
        <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
        <button type="submit" name="modificar" value="modificar" id="btn-editar"> 
        <img src="modificar.png" alt="Portada del libro">
     </button>
    </form>
</div>
<p><p>
<div class="containerdeboton">
    <form method="POST" action="eliminar_reservaciones.php">
        <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
        <button type="submit" name="eliminar" value="eliminar" id="btn-editar"> 
        <img src="eliminar.png" alt="Portada del libro">  
    </button>
    </form>
</div>
                </td>
                <td><?php if ($registro["Estado"]=="nuevo"){
                    if ($registro["Acción"]=="modificado"){
                    echo '<span class="notificacion3"> ¡Modificado! </span>';
                    
                    marcarVisto($registro["id"]);
                }
                else{
                    echo '<span class="notificacion2"> ¡Nuevo! </span>';
                    
                    marcarVisto($registro["id"]);

                    }} ?></td>
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