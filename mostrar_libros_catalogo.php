<?php
// Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
  header("Location: home.php");
} else {
  // Realizar la consulta para obtener los datos de los libros desde la base de datos
  $librosPorPagina = 24;
  $paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
  $offset = ($paginaActual - 1) * $librosPorPagina;

  $sql = "SELECT * FROM libros WHERE `Número de ejemplares disponibles` > 0 ORDER BY Título ASC LIMIT $offset, $librosPorPagina";

  // Verificar si se proporciona un término de búsqueda
  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sql = "SELECT * FROM libros WHERE `Número de ejemplares disponibles` > 0 AND Título LIKE '%$searchTerm%' ORDER BY Título ASC LIMIT $offset, $librosPorPagina";
  }

  $result = $conn->query($sql);

  // Código de muestra para cada libro
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '<div class="container9">';
      echo '<p class="titulo3">' . (isset($row["Título"]) ? $row["Título"] : "Título no disponible") . '</p>';
      echo '<p><p>';
      echo '<div id="portada-container2">';
      echo '<img src="' . $row['Portada'] . '" alt="Portada del libro">';
      echo '</div>';
      echo '<p class="autor">Autor: ' . (isset($row["Autor"]) ? $row["Autor"] : "Autor no disponible") . '</p>';
      // Formulario para reservar el libro
      echo '<form method="post" action="libro_elegido.php">';
      echo '<input type="hidden" name="ISBN" value="' . $row["ISBN"] . '">';
      echo '<div class="boton-centro">';
      echo '<button type="submit">Ver más</button>';
      echo '<p>‎ </p>';
      echo '</div>';
      echo '</div>';
      echo '</form>';
    }

    // Mostrar la paginación
    echo '<div class="pagination">';
    if ($paginaActual > 1) {
      echo '<a href="?pagina=' . ($paginaActual - 1) . '">Anterior</a>';
    }

    for ($i = 1; $i <= ceil($result->num_rows / $librosPorPagina); $i++) {
      echo '<a ' . ($paginaActual === $i ? 'class="active"' : '') . ' href="?pagina=' . $i . '">' . $i . '</a>';
    }

    if ($paginaActual < ceil($result->num_rows / $librosPorPagina)) {
      echo '<a href="?pagina=' . ($paginaActual + 1) . '">Siguiente</a>';
    }
    echo '</div>';
  } else {
    echo "<li>No se encontraron libros</li>";
  }
}
?>
