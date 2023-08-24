<?php
//Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
  header("Location: home.php");
} else {
  // Realizar la consulta para obtener los datos de los libros con 0 ejemplares disponibles desde la base de datos
  $sql = "SELECT * FROM libros WHERE `Número de ejemplares disponibles` = 0 ORDER BY Título ASC";
  
  // Verificar si se proporciona un término de búsqueda
  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sql = "SELECT * FROM libros WHERE `Número de ejemplares disponibles` = 0 AND Título LIKE '%$searchTerm%' ORDER BY Título ASC";
  }
  
  $result = $conn->query($sql);

  //Código para mostrar los libros
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '<li>';
      echo '<p class="titulo3">' . (isset($row["Título"]) ? $row["Título"] : "Título no disponible") . '</p>';
      echo '<p><p>';
      echo '<div id="portada-container">';
      echo '<img src="' . $row['Portada'] . '" alt="Portada del libro">';
      echo '</div>';
      echo '<p class="autor">Autor: ' . (isset($row["Autor"]) ? $row["Autor"] : "Autor no disponible") . '</p>';

    }
  } 
  //Mensaje por si no hay libros no disponibles o agotados
  else {
    $ventana_emergente = '<div id="miVentana" class="modal">
        <div class="modal-content">
            <p>¡Todos los libros están disponibles!</p> 
            <div class="boton-centro">
            <button onclick="cerrarVentana()">Aceptar</button>
            </div>
            
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            mostrarVentana();
        });

        function mostrarVentana() {
            document.getElementById("miVentana").style.display = "flex";
        }

        function cerrarVentana() {
            document.getElementById("miVentana").style.display = "none";
        }
    </script>';

    session_start();
    $_SESSION['ventana_emergente'] = $ventana_emergente;
    header("Location: catalogo.php");
  }
}
?>