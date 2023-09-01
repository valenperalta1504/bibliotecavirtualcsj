<?php
//Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
  }
  else {
$host = "localhost";
$user = "root";
$password = "";
$database = "biblioteca_virtual";

$conn = new mysqli($host, $user, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
}
?>