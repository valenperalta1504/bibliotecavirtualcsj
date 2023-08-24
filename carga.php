<?php
define('ACCESO_PERMITIDO', true);
require_once 'conexion_bd_libros.php';
session_start(); 
//Comprobar que exista un libro en proceso de carga y no poder acceder desde url
if (isset($_SESSION['libro_cargado'])) {
// Establecer la conexión con la base de datos
global $conn;

$libro_cargado = $_SESSION['libro_cargado'];
$datos_libro = json_encode($libro_cargado);
$datos_libro = json_decode($datos_libro, true);

// Extraer los valores del objeto JSON
$titulo = $datos_libro['Título']; // Corregido: 'Título' en lugar de 'titulo'
$autor = $datos_libro['Autor']; // Corregido: 'Autor' en lugar de 'autor'
$editorial = $datos_libro['Editorial']; // Corregido: 'Editorial' en lugar de 'editorial'
$isbn = $datos_libro['ISBN']; // Corregido: 'ISBN' en lugar de 'isbn'
$publicacion = $datos_libro['Año de publicación']; // Corregido: 'Año de publicación' en lugar de 'publicacion'
$descripcion = $datos_libro['Descripción']; // Corregido: 'Descripción' en lugar de 'descripcion'
$categorias = $datos_libro['Categorías']; // Corregido: 'Categorías' en lugar de 'categorias'
$ejemplares = $datos_libro['Número de ejemplares']; // Corregido: 'Número de ejemplares' en lugar de 'ejemplares'
$portada = $datos_libro['Portada']; // Corregido: 'Número de ejemplares' en lugar de 'ejemplares'

// Insertar los datos en la base de datos
$sql = "INSERT INTO libros (`Título`, `Autor`, `Editorial`, `ISBN`, `Año de publicación`, `Descripción`, `Categorías`, `Número de ejemplares disponibles`, `Stock`, `Portada`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Asignar los valores a los parámetros de la consulta
$stmt->bind_param("sssssssiis", $titulo, $autor, $editorial, $isbn, $publicacion, $descripcion, $categorias, $ejemplares, $ejemplares, $portada);

// Ejecutar la consulta preparada
$resultado = $stmt->execute();

if (!$resultado) {
    $response = array('status' => 'error', 'message' => 'Error al guardar el libro: ' . $stmt->error);
} else {
    // Cerrar la consulta preparada y la conexión a la base de datos
    $stmt->close();
    $conn->close();
    unset($_SESSION['libro_cargado']);
    sleep(1);
}
}
else{
    header("Location: home.php");
}
?>
