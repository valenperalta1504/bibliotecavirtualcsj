<?php
//Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
  header("Location: home.php");
}
else {


// Verificar la sesión del usuario
require_once 'controles_admin.php';
// Obtener el nombre y DNI del usuario
$nombre_usuario = $datos_usuario['nombre_completo'];
$dni_usuario = $datos_usuario['dni'];

// Consulta para obtener la cantidad de libros reservados por el usuario
$query_cantidad = "SELECT COUNT(*) AS cantidad FROM reservaciones WHERE Nombre_alumno = '$nombre_usuario' AND Dni = '$dni_usuario'";
$resultado_cantidad = mysqli_query($conn, $query_cantidad);

// Verificar si la consulta fue exitosa
if ($resultado_cantidad) {
  $row_cantidad = mysqli_fetch_assoc($resultado_cantidad);
  $cantidad_libros_reservados = $row_cantidad['cantidad'];
} else {
  $cantidad_libros_reservados = 0;
}

// Consulta para obtener los nombres y fechas de los libros reservados por el usuario
$query_reservaciones = "SELECT Nombre_libro, fecha, ISBN FROM reservaciones WHERE Nombre_alumno = '$nombre_usuario' AND Dni = '$dni_usuario'";
$resultado_reservaciones = mysqli_query($conn, $query_reservaciones);

$mis_reservaciones_final ="";
//Crear un formulario para poder seleccionar y modificar las reservaciones
$mis_reservaciones_final = '<form method="post" action="libro_modificar.php">';
while ($row_reservaciones = mysqli_fetch_assoc($resultado_reservaciones)) {
    $libro_reservado = $row_reservaciones['Nombre_libro'];
    $fecha_reservacion = $row_reservaciones['fecha'];
    $isbn = $row_reservaciones['ISBN'];
    $mis_reservaciones_final .= '<label>';
    $mis_reservaciones_final .= '<input type="radio" name="seleccionado" data-fecha="' . $fecha_reservacion . '" value="' . $isbn . '">';
    $mis_reservaciones_final .= $libro_reservado . ' - Fecha: ' . $fecha_reservacion;
    $mis_reservaciones_final .= '</label><br>';
}
$mis_reservaciones_final .= '<h1>';
$mis_reservaciones_final .= '<button class="botón" type="submit">Modificar</button>';
$mis_reservaciones_final .= '</form>';

// Consulta para obtener la cantidad de libros reservados por el usuario
$query_cantidad2 = "SELECT COUNT(*) AS cantidad FROM prestamos WHERE Nombre_alumno = '$nombre_usuario' AND Dni = '$dni_usuario'";
$resultado_cantidad2 = mysqli_query($conn, $query_cantidad2);

// Verificar si la consulta fue exitosa
if ($resultado_cantidad2) {
  $row_cantidad2 = mysqli_fetch_assoc($resultado_cantidad2);
  $cantidad_libros_prestados = $row_cantidad2['cantidad'];
} else {
  $cantidad_libros_prestados = 0;
}

// Consulta para obtener los nombres y fechas de los libros reservados por el usuario
$query_prestamos = "SELECT Nombre_libro, Fecha_retiro, Fecha_devolución, ISBN FROM prestamos WHERE Nombre_alumno = '$nombre_usuario' AND Dni = '$dni_usuario'";
$resultado_prestamos = mysqli_query($conn, $query_prestamos);

$mis_prestamos_final ="";
//Crear un formulario para poder seleccionar y modificar las reservaciones
$mis_prestamos_final = '<form method="post" action="libro_modificar.php">';
while ($row_prestamos = mysqli_fetch_assoc($resultado_prestamos)) {
    $libro_prestado = $row_prestamos['Nombre_libro'];
    $fecha_retiro = $row_prestamos['Fecha_retiro'];
    $fecha_devolución = $row_prestamos['Fecha_devolución'];
    $isbn = $row_prestamos['ISBN'];
    $libro_vencido= PrestamoVencido2Usuario($isbn);
    if ($libro_vencido == $isbn) {
      $mis_prestamos_final .= '<p class="titulo5">';
      $mis_prestamos_final .= $libro_prestado . ': <span class="notificacion5"> Vencido! </span>';
      $mis_prestamos_final .= '</p> ';
    }
    else {
    $mis_prestamos_final .= '<p class="titulo5">';
    $mis_prestamos_final .= $libro_prestado . ':';
    $mis_prestamos_final .= '</p>';
  }
    $mis_prestamos_final .= '<p class="texto-lindo">';
    $mis_prestamos_final .= '- Fecha de retiro: ' . $fecha_retiro . '<br>';
    $mis_prestamos_final .= '- Fecha de devolución: ' . $fecha_devolución;
    $mis_prestamos_final .= '</p>';
}
}
?>