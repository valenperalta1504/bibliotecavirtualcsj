<?php

// Conectar con la bd
require_once 'conexion_bd_libros.php';
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
}
else {
  

$ocultar="ocultar";
    // Preparar la consulta con un marcador de posición (?)
$sql = "SELECT * FROM habilitar_registro WHERE Estado = ? and id=1";
$stmt = $conn->prepare($sql);

// Vincular el valor de $ocultar al marcador de posición (?)
$stmt->bind_param("s", $ocultar);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();
    //Verificar que no se repita el nombre de usuario
    if ($result && $result->num_rows > 0) {
        header("Location: home.php");
    }
}
?>