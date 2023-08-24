<?php
//Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
  }
  else {
    //Colocar los datos actuales (Guardados en la session)
    $reserva = $_SESSION['usuario'];

$Nombre_alumno = isset($reserva['Nombre_alumno']) ? $reserva['Nombre_alumno'] : ''; 

$Dni = isset($reserva['Dni']) ? $reserva['Dni'] : ''; 

$Nivel = isset($reserva['Nivel']) ? $reserva['Nivel'] : ''; 
$Nivel = isset($reserva['Nivel']) ? $reserva['Nivel'] : ''; 
if ($Nivel === "Secundario") {
    $Nivel = "<option value='Secundario' selected>Secundario</option>";
    $Nivel .= "<option value='Primario'> Primario</option>";
    $Nivel .= "<option value='Inicial'> Inicial</option>";
} 
if ($Nivel === "Primario") {
    $Nivel = "<option value='Secundario'>Secundario</option>";
    $Nivel .= "<option value='Primario' selected> Primario</option>";
    $Nivel .= "<option value='Inicial'> Inicial</option>";
} 
if ($Nivel === "Inicial") {
    $Nivel = "<option value='Secundario'>Secundario</option>";
    $Nivel .= "<option value='Primario'> Primario</option>";
    $Nivel .= "<option value='Inicial' selected> Inicial</option>";
} 

$División = isset($reserva['División']) ? $reserva['División'] : ''; 
if ($División === "A") {
    $División = "<option value='A' selected>A</option>";
    $División .= "<option value='B'> B</option>";
} 
else {
    $División = "<option value='A'> A</option>";
    $División .= "<option value='B' selected>B</option>";
}

$Curso = isset($reserva['Curso']) ? $reserva['Curso'] : ''; 
if ($Curso === "1ro") {
    $Curso = "<option value='1ro' selected>1ro</option>";
    $Curso .= "<option value='2do'> 2do</option>";
    $Curso .= "<option value='3ro'> 3ro</option>";
    $Curso .= "<option value='4to'> 4to</option>";
    $Curso .= "<option value='5to'> 5to</option>";
    $Curso .= "<option value='6to'> 6to</option>";
} 
if ($Curso === "2do") {
    $Curso = "<option value='1ro'> 1ro</option>";
    $Curso .= "<option value='2do' selected> 2do</option>";
    $Curso .= "<option value='3ro'> 3ro</option>";
    $Curso .= "<option value='4to'> 4to</option>";
    $Curso .= "<option value='5to'> 5to</option>";
    $Curso .= "<option value='6to'> 6to</option>";
} 
if ($Curso === "3ro") {
    $Curso = "<option value='1ro'> 1ro</option>";
    $Curso .= "<option value='2do'> 2do</option>";
    $Curso .= "<option value='3ro' selected> 3ro</option>";
    $Curso .= "<option value='4to'> 4to</option>";
    $Curso .= "<option value='5to'> 5to</option>";
    $Curso .= "<option value='6to'> 6to</option>";
} 
if ($Curso === "4to") {
    $Curso = "<option value='1ro'>1ro</option>";
    $Curso .= "<option value='2do'> 2do</option>";
    $Curso .= "<option value='3ro'> 3ro</option>";
    $Curso .= "<option value='4to' selected> 4to</option>";
    $Curso .= "<option value='5to'> 5to</option>";
    $Curso .= "<option value='6to'> 6to</option>";
} 
if ($Curso === "5to") {
    $Curso = "<option value='1ro'> 1ro</option>";
    $Curso .= "<option value='2do' > 2do</option>";
    $Curso .= "<option value='3ro'> 3ro</option>";
    $Curso .= "<option value='4to'> 4to</option>";
    $Curso .= "<option value='5to' selected> 5to</option>";
    $Curso .= "<option value='6to'> 6to</option>";
} 
if ($Curso === "6to") {
    $Curso = "<option value='1ro'> 1ro</option>";
    $Curso .= "<option value='2do'> 2do</option>";
    $Curso .= "<option value='3ro' > 3ro</option>";
    $Curso .= "<option value='4to'> 4to</option>";
    $Curso .= "<option value='5to'> 5to</option>";
    $Curso .= "<option value='6to' selected> 6to</option>";
} 

$ISBN = isset($reserva['ISBN']) ? $reserva['ISBN'] : ''; 
$Fecha = isset($reserva['Fecha']) ? $reserva['Fecha'] : ''; 
}
?>
