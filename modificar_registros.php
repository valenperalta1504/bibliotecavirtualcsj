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

function obtenerUsuarios() {

    global $conn;

    // Construir la cláusula WHERE con las condiciones seleccionadas
$whereClause = '';

// Verificar si se ha seleccionado una categoría
if (isset($_GET['filtro-curso']) && !empty($_GET['filtro-curso'])) {
    $curso = $_GET['filtro-curso'];
    $whereClause .= "curso = '$curso' AND ";
}

// Verificar si se ha seleccionado un autor
if (isset($_GET['filtro-nivel'])  && !empty($_GET['filtro-nivel'])) {
    $nivel = $_GET['filtro-nivel'];
    $whereClause .= "nivel LIKE '$nivel' AND ";
}

if (isset($_GET['filtro-division']) && !empty($_GET['filtro-division'])) {
    $division = $_GET['filtro-division'];
    $whereClause .= "division = '$division' AND ";
}

// Quitar el "AND" adicional al final de la cláusula WHERE
if (!empty($whereClause)) {
    $whereClause = 'WHERE ' . rtrim($whereClause, 'AND ') . ' ';
}

$query = "SELECT * FROM registro $whereClause ORDER BY id ASC";

  
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = "SELECT * FROM registro WHERE 
          dni LIKE '%$searchTerm%' OR 
          nombre_completo LIKE '%$searchTerm%' OR 
          nivel LIKE '%$searchTerm%' OR 
          curso LIKE '%$searchTerm%' OR 
          division LIKE '%$searchTerm%' OR 
          usuario LIKE '%$searchTerm%' OR 
          id LIKE '%$searchTerm%' OR 
          email LIKE '%$searchTerm%'
          ORDER BY id ASC";
  }

    $result = mysqli_query($conn, $query);
    $usuarios = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $usuarios;
  }



// Obtener todos los registros
$usuarios = obtenerUsuarios();

$sql = "SELECT Estado FROM habilitar_registro WHERE id = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$estado = $row['Estado'];
$final="";
if ($estado == "ocultar") {
    $final="Habilitar Registro";
} else {
    $final="Ocultar Registro";
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
    <script>
    // Función para seleccionar o deseleccionar todos los checkboxes
    function seleccionarTodos() {
        const checkboxes = document.getElementsByName('seleccionado[]');
        const btnSeleccionarTodos = document.getElementById('btn-seleccionar-todos');

        // Si al menos un checkbox está deseleccionado, los seleccionamos todos
        let alMenosUnoNoSeleccionado = false;
        for (const checkbox of checkboxes) {
            if (!checkbox.checked) {
                alMenosUnoNoSeleccionado = true;
                break;
            }
        }

        // Dependiendo del estado actual, seleccionamos o deseleccionamos todos
        if (alMenosUnoNoSeleccionado) {
            for (const checkbox of checkboxes) {
                checkbox.checked = true;
            }
            btnSeleccionarTodos.textContent = 'Deseleccionar Todos';
        } else {
            for (const checkbox of checkboxes) {
                checkbox.checked = false;
            }
            btnSeleccionarTodos.textContent = 'Seleccionar Todos';
        }
    }

    // Asociamos la función al evento "click" del botón
    document.getElementById('btn-seleccionar-todos').addEventListener('click', seleccionarTodos);
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
    <p class="titulo">TABLA DE REGISTROS:</p>
    <form method="get" action="modificar_registros.php">
  <div class="search-container">
    <input type="text" name="search" id="search" class="lindo-input2" placeholder="Ingrese su búsqueda...">
    <button type="submit" class="search-button">Buscar</button>  
</form>
    <p class="invisible">
        s
        <p>
    </form>
    <div class="containerdeboton">
    <form method="post" action="ocultar_registro.php">
    <button name="ocultar" value="ocultar" type="submit" class="search-button"><?php 
    echo $final;
    ?></button>
</form>

    </div>
    <a class="invisible"> 
        s
        <a>
    <button class="botón" id="btnImprimirTabla">Imprimir</button>
</div>

<div class="filter-container">
<form method="get" name="filter-form" id="filter-form" action="modificar_registros.php">
  <!-- ... (otros campos de búsqueda) ... -->

    <select name="filtro-curso" class="filter-select2" id="filtro-curso"  >
      <option value="">Curso</option>
      <option value="Sala de 4" <?php if(isset($_GET['filtro-curso']) && $_GET['filtro-curso'] === 'Sala de 4') echo 'selected'; ?>>Sala de 4</option>
      <option value="Sala de 5" <?php if(isset($_GET['filtro-curso']) && $_GET['filtro-curso'] === 'Sala de 5') echo 'selected'; ?>>Sala de 5</option>
      <option value="1ro" <?php if(isset($_GET['filtro-curso']) && $_GET['filtro-curso'] === '1ro') echo 'selected'; ?>>1ro</option>
      <option value="2do" <?php if(isset($_GET['filtro-curso']) && $_GET['filtro-curso'] === '2do') echo 'selected'; ?>>2do</option>
      <option value="3ro" <?php if(isset($_GET['filtro-curso']) && $_GET['filtro-curso'] === '3ro') echo 'selected'; ?>>3ro</option>
      <option value="4to" <?php if(isset($_GET['filtro-curso']) && $_GET['filtro-curso'] === '4to') echo 'selected'; ?>>4to</option>
      <option value="5to" <?php if(isset($_GET['filtro-curso']) && $_GET['filtro-curso'] === '5to') echo 'selected'; ?>>5to</option>
      <option value="6to" <?php if(isset($_GET['filtro-curso']) && $_GET['filtro-curso'] === '6to') echo 'selected'; ?>>6to</option>
      <!-- Agrega más opciones según tus cursos disponibles -->
    </select>


    <select name="filtro-division" class="filter-select2" id="filtro-division"  >
      <option value="">División</option>
      <option value="B" <?php if(isset($_GET['filtro-division']) && $_GET['filtro-division'] === 'B') echo 'selected'; ?>>B</option>
      <option value="A" <?php if(isset($_GET['filtro-division']) && $_GET['filtro-division'] === 'A') echo 'selected'; ?>>A</option>
    
      <!-- Agrega más opciones según tus divisiones disponibles -->
    </select>


    <select name="filtro-nivel" class="filter-select2" id="filtro-nivel" >
        
      <option value="">Nivel</option>
      <option value="Primario" <?php if(isset($_GET['filtro-nivel']) && $_GET['filtro-nivel'] === 'Primario') echo 'selected'; ?>>Primario</option>
      <option value="Inicial" <?php if(isset($_GET['filtro-nivel']) && $_GET['filtro-nivel'] === 'Inicial') echo 'selected'; ?>>Inicial</option>
      <option value="Secundario" <?php if(isset($_GET['filtro-nivel']) && $_GET['filtro-nivel'] === 'Secundario') echo 'selected'; ?>>Secundario</option>
      <option value="Docente" <?php if(isset($_GET['filtro-nivel']) && $_GET['filtro-nivel'] === 'Docente') echo 'selected'; ?>>Docente</option>
      <option value="Personal_Administrativo" <?php if(isset($_GET['filtro-nivel']) && $_GET['filtro-nivel'] === 'Personal_Administrativo') echo 'selected'; ?>>Personal_Administrativo</option>

      <!-- Agrega más opciones según tus niveles disponibles -->
    </select>
</form>
</div>
<div class="search-container">
<form method="POST" action="actualizar_cursos.php">
<button type="submit" class="botón" name="cursos" value="cursos">Actualizar Cursos</button>
</form>
<a class="invisible"> 
        s
        <a>
<button class="botón"><a href="subir_csv_alumnos.php">Subir curso CSV</a></button>
<a class="invisible"> 
        s
        <a>
<button class="botón"><a href="alta_registros.php">Insertar Registro</a></button>
        </div>
    <table>
        <tr> 
            <th>id</th>
            <th>Nombre Completo</th>
            <th>Dni</th>
            <th>Email</th>
            <th>Nivel</th>
            <th>Curso</th>
            <th>Divisón</th>
            <th>Usuario</th>
            <th>Acciones</th>
            <th class="invisible">ssssssssssssssssss</th>
        </tr> 
        <?php foreach ($usuarios as $registro): ?>
            <tr>
            <td>
            
        
        <?php echo $registro["id"]; 
        $id =$registro["id"]; ?>
    </form>
    <?php
// Assuming you have already established the database connection ($conn) and have the value of $id set.

// Step 1: Create the prepared statement
$query = "SELECT Ban FROM registro WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt === false) {
    // Handle the error if the prepared statement couldn't be created
    die("Error: Could not prepare the statement.");
}

// Step 2: Bind the parameter(s)
mysqli_stmt_bind_param($stmt, "i", $id);

// Step 3: Execute the prepared statement
mysqli_stmt_execute($stmt);

// Step 4: Bind the result
mysqli_stmt_bind_result($stmt, $banValue);

// Step 5: Fetch the result
mysqli_stmt_fetch($stmt);

// Step 6: Close the statement
mysqli_stmt_close($stmt);

// Now you can use the $banValue variable for further processing or comparison
?>
<td>
    <?php
    if ($banValue == "ban") {
        echo '<p class="error-message">' . $registro["nombre_completo"] . '</p>';
    } else {
        echo $registro["nombre_completo"];
    }
    ?>

</td>
                <td><?php echo $registro["dni"]; ?></td>
                <td><?php echo $registro["email"]; ?></td>
                <td><?php echo $registro["nivel"]; ?></td>
                <td><?php echo $registro["curso"]; ?></td>
                <td><?php echo $registro["division"]; ?></td>
                <td><?php echo $registro["usuario"]; ?></td>
                <td>
                <div class="containerdeboton">
    <form method="POST" action="modificar_registro2.php">
        <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
        <button type="submit" name="editar" value="editar" id="btn-editar">
        <img src="modificar.png" alt="Portada del libro">
        </button>
    </form>
</div>
<p><p>
<div class="containerdeboton">
    <form method="POST" action="eliminar_registro.php">
        <input type="hidden" name="id" value="<?php echo $registro["id"]; ?>">
        <button type="submit" name="eliminar" value="eliminar" id="btn-editar"> 
        <img src="eliminar.png" alt="Portada del libro">
        </button>
        </form>
</div>
<p><p>

<div class="containerdeboton">
<?php
    if ($banValue == "ban") {
        echo '<form method="POST" action="desbanear_usuario.php">
        <input type="hidden" name="id" value="' . $registro["id"] . '">
        <button type="submit" name="desbanear" value="desbanear" id="btn-editar"> 
        <img src="desban.png" alt="Portada del libro">';
    } else {
        echo '<form method="POST" action="banear_usuario.php">
        <input type="hidden" name="id" value="' . $registro["id"] . '">
        <button type="submit" name="banear" value="banear" id="btn-editar"> 
        <img src="ban.png" alt="Portada del libro">';
    }
    ?>
        </button>
        </form>
</div>
<p><p>
<div class="containerdeboton">
    <form method="POST" action="blanquear_contra.php">
        <input type="hidden" name="dni" value="<?php echo $registro["dni"]; ?>">
        <button type="submit" name="cambiar" value="cambiar" id="btn-editar"> 
        <img src="contra.png" alt="Portada del libro">
        </button>
        </form>
</div>
<p><p>
<td> 
                  <?php
                  $hasOverdueDevolutions = PrestamoVencido3($registro["dni"]);

                  if ($hasOverdueDevolutions) {
                    echo '<span class="notificacion5"> Debe libro! </span>';
                  } 
                  ?>  
                </td>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
        </div>
        </div>
        </main>
        <footer>
	<p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
</footer>
<script>
    function autoSubmitForm() {
            const form = document.getElementById("filter-form");
            form.submit();
        }

         // Función para cargar las opciones originales en cada selector
         function resetSelectors() {
            const cursoSelector = document.getElementById("filtro-curso");
            const nivelSelector = document.getElementById("filtro-nivel");
            const divisionSelector = document.getElementById("filtro-division");

            resetOptions(cursoSelector);
            resetOptions(nivelSelector);
            resetOptions(divisionSelector);
        }

        // Función para resetear las opciones originales en un selector
        function resetOptions(selector) {
            for (let i = 0; i < selector.options.length; i++) {
                selector.options[i].disabled = false;
            }
        }

        // Event listeners para los selectores
        document.getElementById("filtro-curso").addEventListener("change", function() {
            autoSubmitForm();
            resetSelectors();
            const selectedCurso = this.value;
            if (selectedCurso) {

                const nivelSelector = document.getElementById("filtro-nivel");
                for (let i = 0; i < nivelSelector.options.length; i++) {
                    if (nivelSelector.options[i].getAttribute("data-Curso") !== selectedCurso) {
                        nivelSelector.options[i].disabled = true;
                    }
                }

                const divisionSelector = document.getElementById("filtro-division");
                for (let i = 0; i < divisionSelector.options.length; i++) {
                    if (divisionSelector.options[i].getAttribute("data-Curso") !== selectedCurso) {
                        divisionSelector.options[i].disabled = true;
                    }
                }

            }
        });

        // Event listeners para los selectores
        document.getElementById("filtro-nivel").addEventListener("change", function() {
            autoSubmitForm();
            resetSelectors();
            const selectedNivel = this.value;
            if (selectedNivel) {

                const cursoSelector = document.getElementById("filtro-curso");
                for (let i = 0; i < cursoSelector.options.length; i++) {
                    if (cursoSelector.options[i].getAttribute("data-Nivel") !== selectedNivel) {
                        cursoSelector.options[i].disabled = true;
                    }
                }

                const divisionSelector = document.getElementById("filtro-division");
                for (let i = 0; i < divisionSelector.options.length; i++) {
                    if (divisionSelector.options[i].getAttribute("data-Nivel") !== selectedNivel) {
                        divisionSelector.options[i].disabled = true;
                    }
                }

            }
        });

        // Event listeners para los selectores
        document.getElementById("filtro-division").addEventListener("change", function() {
            autoSubmitForm();
            resetSelectors();
            const selectedDivision = this.value;
            if (selectedDivision) {

                const cursoSelector = document.getElementById("filtro-curso");
                for (let i = 0; i < cursoSelector.options.length; i++) {
                    if (cursoSelector.options[i].getAttribute("data-Division") !== selectedDivision) {
                        cursoSelector.options[i].disabled = true;
                    }
                }

                const nivelSelector = document.getElementById("filtro-nivel");
                for (let i = 0; i < nivelSelector.options.length; i++) {
                    if (nivelSelector.options[i].getAttribute("data-Division") !== selectedDivision) {
                        nivelSelector.options[i].disabled = true;
                    }
                }

            }
        });

        </script>
<?php
  //muestra ventana emergente si es que hay
  if (isset($ventana_emergente)) {
    echo $ventana_emergente;
  }

  if (isset($_SESSION['ventana_emergente2'])) {
    $ventana_emergente2 = $_SESSION['ventana_emergente2'];
    echo $ventana_emergente2;
    unset($_SESSION['ventana_emergente2']);
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