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
// Conectar con la bd
require_once 'conexion_bd_libros.php';

function obtenerHistorialPrestamos($filtroFecha, $searchTerm) {
global $conn;
    $query = "SELECT * FROM historial_prestamos";
      
      // Aplicar el filtro de fecha según la opción seleccionada
      $fechaLimite = null;
      switch ($filtroFecha) {
          case 'semana':
              $fechaLimite = date('Y-m-d', strtotime('-1 week'));
              break;
          case 'mes':
              $fechaLimite = date('Y-m-d', strtotime('-1 month'));
              break;
          case 'anio':
              $fechaLimite = date('Y-m-d', strtotime('-1 year'));
              break;
          default:
              // Si se selecciona "siempre", no hay límite de fecha
              break;
      }
  
      // Agregar condiciones a la consulta según el filtro de fecha y búsqueda
      if ($fechaLimite) {
          $query .= " WHERE Fecha_retiro >= '$fechaLimite'";
      }
  
      if (!empty($searchTerm)) {
          if ($fechaLimite) {
              $query .= " AND";
          } else {
              $query .= " WHERE";
          }
          $query .= " (Dni LIKE '%$searchTerm%' OR 
            Nombre_alumno LIKE '%$searchTerm%' OR 
            Nivel LIKE '%$searchTerm%' OR 
            Curso LIKE '%$searchTerm%' OR 
            División LIKE '%$searchTerm%' OR 
            ISBN LIKE '%$searchTerm%' OR 
            Nombre_libro LIKE '%$searchTerm%' OR 
            Fecha_retiro LIKE '%$searchTerm%' OR 
            Fecha_devolución LIKE '%$searchTerm%')";
      }
  
      $query .= " ORDER BY id ASC";
  
    $result = mysqli_query($conn, $query);
    $prestamos = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $prestamos;
  }
// Obtener el valor seleccionado para el filtro de fecha y el término de búsqueda
$filtroFecha = isset($_GET['filtro_fecha']) ? $_GET['filtro_fecha'] : 'siempre';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Obtener todos los registros
$prestamos = obtenerHistorialPrestamos($filtroFecha, $searchTerm);
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
    <script>
        // Función para filtrar el historial de préstamos por fecha
        function filtrarHistorialPorFecha() {
            const fechaSeleccionada = document.getElementById('filtro-fecha').value;
            const prestamos = document.getElementsByClassName('prestamo-row');

            const fechaHoy = new Date();
            const fechaLimite = new Date();

            switch (fechaSeleccionada) {
                case 'semana':
                    fechaLimite.setDate(fechaLimite.getDate() - 7);
                    break;
                case 'mes':
                    fechaLimite.setMonth(fechaLimite.getMonth() - 1);
                    break;
                case 'anio':
                    fechaLimite.setFullYear(fechaLimite.getFullYear() - 1);
                    break;
                default:
                    // Si se selecciona "siempre", no hay límite de fecha
                    fechaLimite = new Date(0);
            }

            // Ocultar o mostrar filas según el filtro de fecha
            for (let i = 0; i < prestamos.length; i++) {
                const fechaPrestamo = new Date(prestamos[i].getAttribute('data-fecha'));
                if (fechaPrestamo >= fechaLimite && fechaPrestamo <= fechaHoy) {
                    prestamos[i].style.display = '';
                } else {
                    prestamos[i].style.display = 'none';
                }
            }
        }
    </script>
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
    <main>
        <div class="container8">
            <p class="titulo">HISTORIAL DE PRESTAMOS:</p>
            <form method="get" action="historial_prestamos.php">
                <div class="search-container">
                    <input type="text" name="search" id="search" class="lindo-input2" placeholder="Ingrese su búsqueda..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit" class="search-button">Buscar</button>
                    <a class="invisible">s<a>
                    <button class="botón"><a href="estadisticas.php" class="botón">Estadísticas</a></button>
                    <a class="invisible">s<a>
                    <button class="botón" id="btnImprimirTabla">Imprimir</button>
                </div>
                <div class="filter-container">
                <select id="filtro-fecha" name="filtro_fecha" class="filter-select2" onchange="this.form.submit()">
                <option value="semana" <?php if ($filtroFecha === 'semana') echo 'selected'; ?>>Última semana</option>
                <option value="mes" <?php if ($filtroFecha === 'mes') echo 'selected'; ?>>Último mes</option>
                <option value="anio" <?php if ($filtroFecha === 'anio') echo 'selected'; ?>>Último año</option>
                <option value="siempre" <?php if ($filtroFecha === 'siempre') echo 'selected'; ?>>Siempre</option>
                </select>
                </div>
            </form>
            
        
        <table>
            <tr>
                <th>id</th>
                <th>Título</th>
                <th>Portada</th>
                <th>ISBN</th>
                <th>Usuario</th>
                <th>Dni</th>
                <th>Nivel</th>
                <th>Curso</th>
                <th>División</th>
                <th>Fecha de retiro</th>
                <th>Fecha de devolución</th>
            </tr>
            <?php foreach ($prestamos as $registro): ?>
                <tr class="prestamo-row" data-fecha="<?php echo $registro["Fecha_retiro"]; ?>">
                    <td><?php echo $registro["id"]; ?></td>
                    <td><?php echo $registro["Nombre_libro"]; ?></td>
                    <td>
                        <?php 
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
                </tr>
            <?php endforeach; ?>
        </table>
        </div>
    </main>
    <footer>
        <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
    </footer>
    <?php
    // Muestra ventana emergente si es que hay
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