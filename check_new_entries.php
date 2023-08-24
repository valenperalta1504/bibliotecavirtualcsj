<?php
define('ACCESO_PERMITIDO', true);
// Conexión con la base de datos
require_once 'conexion_bd_libros.php';

// Conectarse a la base de datos (cambia las credenciales según tu configuración)
global $conn;
// Consulta SQL para obtener las nuevas entradas (cambia el nombre de la tabla y el campo de fecha según tu configuración)
$sql = "SELECT * FROM chat WHERE Fecha_envío > (SELECT MAX(Fecha_envío) FROM chat) - INTERVAL 1 HOUR";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Crear un arreglo para almacenar los datos de las nuevas entradas
    $newEntries = array();

    // Recorrer los resultados y agregarlos al arreglo
    while ($row = $result->fetch_assoc()) {
        $newEntries[] = $row;
    }

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($newEntries);
} else {
    // Si no hay nuevas entradas, devolver un arreglo vacío
    echo "[]";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
