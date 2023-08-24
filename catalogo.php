<?php
// Permiso para abrir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// Conexión con la base de datos
require_once 'conexion_bd_libros.php';
// Verificar si existe una ventana emergente
require_once 'ventana_emergente.php';
// Verificar si existe una ventana emergente
require_once 'controles_admin.php';
// Verificar si existe una ventana emergente

// Realizar la consulta para obtener los datos de los libros desde la base de datos
$librosPorPagina = 16;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($paginaActual - 1) * $librosPorPagina;

// Obtener la lista de categorías disponibles
$sqlCategorias = "SELECT DISTINCT Categorías FROM libros";
$resultCategorias = $conn->query($sqlCategorias);
$categorias = array();
while ($rowCategoria = $resultCategorias->fetch_assoc()) {
    $categorias[] = $rowCategoria['Categorías'];
}

// Obtener la lista de autores disponibles
$sqlAutor = "SELECT DISTINCT Autor FROM libros";
$resultAutor = $conn->query($sqlAutor);
$autores = array();
while ($rowAutor = $resultAutor->fetch_assoc()) {
    // Divide los autores utilizando la coma como delimitador y elimina los espacios en blanco alrededor de cada autor
    $autoresTemp = array_map('trim', explode(',', $rowAutor['Autor']));

    // Agrega los autores divididos al arreglo de autores
    $autores = array_merge($autores, $autoresTemp);
}
// Elimina los elementos duplicados en caso de que un autor tenga varias publicaciones
$autores = array_unique($autores);

// Ordena el arreglo de autores alfabéticamente
sort($autores);

// Obtener la lista de editoriales disponibles
$sqlEditoriales = "SELECT DISTINCT Editorial FROM libros";
$resultEditoriales = $conn->query($sqlEditoriales);
$editoriales = array();
while ($rowEditorial = $resultEditoriales->fetch_assoc()) {
    $editoriales[] = $rowEditorial['Editorial'];
}
// Ordena el arreglo de editoriales alfabéticamente
sort($editoriales);

// Verificar si se proporciona un término de búsqueda
$searchTerm = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Construir la cláusula WHERE con las condiciones seleccionadas
$whereClause = '';

// Verificar si se ha seleccionado una categoría
if (isset($_GET['Categorías']) && in_array($_GET['Categorías'], $categorias)) {
    $categoriaSeleccionada = $_GET['Categorías'];
    $whereClause .= "Categorías = '$categoriaSeleccionada' AND ";
}

// Verificar si se ha seleccionado un autor
if (isset($_GET['Autores']) && $_GET['Autores'] !== '') {
    $autorSeleccionado = $_GET['Autores'];
    $whereClause .= "Autor LIKE '%$autorSeleccionado%' AND ";
}

// Verificar si se ha seleccionado una editorial
if (isset($_GET['Editoriales']) && $_GET['Editoriales'] !== '') {
    $editorialSeleccionada = $_GET['Editoriales'];
    $whereClause .= "Editorial = '$editorialSeleccionada' AND ";
}

// Verificar el estado de disponibilidad
$disponibles = isset($_GET['Disponibles']) ? $_GET['Disponibles'] : 'Todos';
if ($disponibles === 'Disponibles') {
    $whereClause .= "`Número de ejemplares disponibles` > 0 AND ";
} elseif ($disponibles === 'Agotados') {
    $whereClause .= "`Número de ejemplares disponibles` = 0 AND ";
}

// Verificar si se proporcionó un término de búsqueda
if (!empty($searchTerm)) {
    $whereClause .= "((Título LIKE '%$searchTerm%' OR Autor LIKE '%$searchTerm%' OR Categorías LIKE '%$searchTerm%' OR Editorial LIKE '%$searchTerm%')) AND ";
}

// Quitar el "AND" adicional al final de la cláusula WHERE
if (!empty($whereClause)) {
    $whereClause = 'WHERE ' . rtrim($whereClause, 'AND ') . ' ';
}

$sqlCount = "SELECT COUNT(*) AS total FROM libros $whereClause";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$totalLibros = $rowCount['total'];

$sql = "SELECT * FROM libros $whereClause ORDER BY Título ASC LIMIT $offset, $librosPorPagina";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="/logo6.png"/>
    <title>Biblioteca Virtual</title>
    <link rel="stylesheet" href="style13.css">
    <script>
        function submitForm(ISBN) {
            // Crear un formulario dinámicamente
            var form = document.createElement("form");
            form.method = "post";
            form.action = "libro_elegido.php";

            // Crear un campo oculto para el ISBN y agregarlo al formulario
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "ISBN";
            input.value = ISBN;
            form.appendChild(input);

            // Agregar el formulario al cuerpo del documento y enviarlo
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>
<body>
<div class="container">
    <nav>
        <ul>
            <?php
            $resultado=mostrarChat();
            mostrar_menu_catalogo($_SESSION['es_admin'], $resultado);
            ?>
        </ul>
    </nav>
    <header>
        <div class="container-menu">
            <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José"
                 class="logo">
        </div>
    </header>
    <main>
        <div class="container5">
            <p class="titulo">LIBROS DISPONIBLES</p>
            <!-- Formulario de búsqueda y filtros -->
            <form method="get" action="catalogo.php">
    <div class="search-container">
        <input type="text" name="search" id="search" class="lindo-input2" placeholder="Ingrese su búsqueda..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="search-button">Buscar</button>
    </div>
</form>
            <form id="filter-form" method="get" action="catalogo.php">
                <div class="filter-container">
                <select name="Disponibles" id="Disponibles" class="filter-select">
            <option value="Todos" <?php if(isset($_GET['Disponibles']) && $_GET['Disponibles'] === 'Todos') echo 'selected'; ?>>Todos</option>
            <option value="Disponibles" <?php if(isset($_GET['Disponibles']) && $_GET['Disponibles'] === 'Disponibles') echo 'selected'; ?>>Disponibles</option>
            <option value="Agotados" <?php if(isset($_GET['Disponibles']) && $_GET['Disponibles'] === 'Agotados') echo 'selected'; ?>>Agotados</option>
        </select>
                    <select name="Categorías" id="categoria" class="filter-select">
                        <option value="">Género</option>
                        <?php
                        foreach ($categorias as $categoria) {
                            $selectedCategoria = isset($_GET['Categorías']) && $_GET['Categorías'] === $categoria ? 'selected' : '';
                            echo '<option value="' . $categoria . '" ' . $selectedCategoria . '>' . $categoria . '</option>';
                        }
                        ?>
                    </select>
                    <select name="Autores" id="Autor" class="filter-select">
                        <option value="">Autor</option>
                        <?php
                        foreach ($autores as $autor) {
                            $selectedAutor = isset($_GET['Autores']) && $_GET['Autores'] === $autor ? 'selected' : '';
                            echo '<option value="' . $autor . '" ' . $selectedAutor . '>' . $autor . '</option>';
                        }
                        ?>
                    </select>
                    <select name="Editoriales" id="Editorial" class="filter-select">
                        <option value="">Editorial</option>
                        <?php
                        foreach ($editoriales as $editorial) {
                            $selectedEditorial = isset($_GET['Editoriales']) && $_GET['Editoriales'] === $editorial ? 'selected' : '';
                            echo '<option value="' . $editorial . '" ' . $selectedEditorial . '>' . $editorial . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </form>
            <div class="book-gallery">
                <?php
                // Mostrar los libros de la página actual
            $counter = 0;
            while ($row = $result->fetch_assoc()) {
                $counter++;
                // Aplicar una clase especial para los primeros libros en cada página
                $classToShow = $counter <= $librosPorPagina ? "show" : "hide";
                echo '<div class="container9 ' . $classToShow . '">';

                echo '<p><p>';
                echo '<div id="portada-container2">';
                echo '<img src="' . $row['Portada'] . '" alt="Portada del libro" onclick="submitForm(\'' . $row["ISBN"] . '\')">';
                echo '</div>';
                echo '<p><p>';
                echo '<p class="titulo3">' . (isset($row["Título"]) ? $row["Título"] : "Título no disponible") . '</p>';
                echo '<p class="autor">Autor: ' . (isset($row["Autor"]) ? $row["Autor"] : "Autor no disponible") . '</p>';
                echo '<p class="autor">' . (isset($row["Categorías"]) ? $row["Categorías"] : "Autor no disponible") . '</p>';
                
                // Verificar si hay ejemplares disponibles
                $ejemplaresDisponibles = intval($row["Número de ejemplares disponibles"]);
                if ($ejemplaresDisponibles > 0) {
                    // Formulario para reservar el libro
                    echo '<form method="post" action="libro_elegido.php">';
                    echo '<input type="hidden" name="ISBN" value="' . $row["ISBN"] . '">';
                    echo '<div class="boton-centro">';
                    // Aquí puedes agregar el botón de reserva si es necesario
                    echo '</div>';
                    echo '</form>';
                } else {
                    // Mostrar el mensaje de agotado
                    echo '<p class="error-message">AGOTADO</p>';
                }

                echo '</div>';
            }
            if ($counter === 0) {
                echo '<p>No se encontraron libros con los filtros seleccionados.</p>';
            }
            ?>
            </div>
            <div class="pagination">
    <?php
    // Mostrar la paginación
    if ($totalLibros > 0) {
        $totalPaginas = ceil($totalLibros / $librosPorPagina);
    
        $prevPage = $paginaActual - 1;
        $nextPage = $paginaActual + 1;
    
        $paginationLinkPrev = http_build_query(array_merge($_GET, array("pagina" => $prevPage)));
        $paginationLinkNext = http_build_query(array_merge($_GET, array("pagina" => $nextPage)));
    
        if ($paginaActual > 1) {
            echo '<a href="?' . $paginationLinkPrev . '">' . $prevPage . '</a>';
        }
    
        echo '<a class="active" href="?pagina=' . $paginaActual . '">' . $paginaActual . '</a>';
    
        if ($paginaActual < $totalPaginas) {
            echo '<a href="?' . $paginationLinkNext . '">' . $nextPage . '</a>';
        }
    } 
    ?>
</div>
        </div>
    </main>
    <footer>
        <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
    </footer>
    <?php
    // Muestra la ventana emergente si es que hay
    if (isset($ventana_emergente)) {
        echo $ventana_emergente;
    }
    ?>
     <script>
        // Función para enviar el formulario automáticamente y deshabilitar las opciones seleccionadas
        function autoSubmitForm() {
            const form = document.getElementById("filter-form");
            form.submit();
        }

        // Función para cargar las opciones originales en cada selector
        function resetSelectors() {
            const categoriaSelector = document.getElementById("categoria");
            const autorSelector = document.getElementById("Autor");
            const editorialSelector = document.getElementById("Editorial");
            const DisponiblesSelector = document.getElementById("Disponibles");

            resetOptions(categoriaSelector);
            resetOptions(autorSelector);
            resetOptions(editorialSelector);
            resetOptions(DisponiblesSelector);
        }

        // Función para resetear las opciones originales en un selector
        function resetOptions(selector) {
            for (let i = 0; i < selector.options.length; i++) {
                selector.options[i].disabled = false;
            }
        }

        // Event listeners para los selectores
        document.getElementById("categoria").addEventListener("change", function() {
            autoSubmitForm();
            resetSelectors();
            const selectedCategoria = this.value;
            if (selectedCategoria) {
                const autorSelector = document.getElementById("Autor");
                for (let i = 0; i < autorSelector.options.length; i++) {
                    if (autorSelector.options[i].getAttribute("data-categoria") !== selectedCategoria) {
                        autorSelector.options[i].disabled = true;
                    }
                }

                const editorialSelector = document.getElementById("Editorial");
                for (let i = 0; i < editorialSelector.options.length; i++) {
                    if (editorialSelector.options[i].getAttribute("data-categoria") !== selectedCategoria) {
                        editorialSelector.options[i].disabled = true;
                    }
                }

                const DisponiblesSelector = document.getElementById("Disponibles");
                for (let i = 0; i < DisponiblesSelector.options.length; i++) {
                    if (DisponiblesSelector.options[i].getAttribute("data-categoria") !== selectedCategoria) {
                        DisponiblesSelector.options[i].disabled = true;
                    }
                }
            }
        });

        document.getElementById("Autor").addEventListener("change", function() {
            autoSubmitForm();
            resetSelectors();
            const selectedAutor = this.value;
            if (selectedAutor) {
                const categoriaSelector = document.getElementById("categoria");
                for (let i = 0; i < categoriaSelector.options.length; i++) {
                    if (categoriaSelector.options[i].getAttribute("data-autor") !== selectedAutor) {
                        categoriaSelector.options[i].disabled = true;
                    }
                }

                const editorialSelector = document.getElementById("Editorial");
                for (let i = 0; i < editorialSelector.options.length; i++) {
                    if (editorialSelector.options[i].getAttribute("data-autor") !== selectedAutor) {
                        editorialSelector.options[i].disabled = true;
                    }
                }

                const DisponiblesSelector = document.getElementById("Disponibles");
                for (let i = 0; i < DisponiblesSelector.options.length; i++) {
                    if (DisponiblesSelector.options[i].getAttribute("data-autor") !== selectedAutor) {
                        DisponiblesSelector.options[i].disabled = true;
                    }
                }
            }
        });

        document.getElementById("Editorial").addEventListener("change", function() {
            autoSubmitForm();
            resetSelectors();
            const selectedEditorial = this.value;
            if (selectedEditorial) {
                const categoriaSelector = document.getElementById("categoria");
                for (let i = 0; i < categoriaSelector.options.length; i++) {
                    if (categoriaSelector.options[i].getAttribute("data-editorial") !== selectedEditorial) {
                        categoriaSelector.options[i].disabled = true;
                    }
                }

                const autorSelector = document.getElementById("Autor");
                for (let i = 0; i < autorSelector.options.length; i++) {
                    if (autorSelector.options[i].getAttribute("data-editorial") !== selectedEditorial) {
                        autorSelector.options[i].disabled = true;
                    }
                }

                const DisponiblesSelector = document.getElementById("Disponibles");
                for (let i = 0; i < DisponiblesSelector.options.length; i++) {
                    if (DisponiblesSelector.options[i].getAttribute("data-editorial") !== selectedEditorial) {
                        DisponiblesSelector.options[i].disabled = true;
                    }
                }
            }
        });
        document.getElementById("Disponibles").addEventListener("change", function() {
            autoSubmitForm();
            resetSelectors();
            const selectedDisponibles = this.value;
            if (selectedDisponibles) {
                const categoriaSelector = document.getElementById("categoria");
                for (let i = 0; i < categoriaSelector.options.length; i++) {
                    if (categoriaSelector.options[i].getAttribute("data-Disponibles") !== selectedDisponibles) {
                        categoriaSelector.options[i].disabled = true;
                    }
                }

                const autorSelector = document.getElementById("Autor");
                for (let i = 0; i < autorSelector.options.length; i++) {
                    if (autorSelector.options[i].getAttribute("data-editorial") !== selectedDisponibles) {
                        autorSelector.options[i].disabled = true;
                    }
                }

                const editorialSelector = document.getElementById("Editorial");
                for (let i = 0; i < editorialSelector.options.length; i++) {
                    if (editorialSelector.options[i].getAttribute("data-editorial") !== selectedDisponibles) {
                        editorialSelector.options[i].disabled = true;
                    }
                }
            }
        });
    </script>
</body>
</html>
