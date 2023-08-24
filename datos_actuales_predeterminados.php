<?php
//Condición para no poder entrar desde la url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
  }
  else {
    //Colocar los datos actuales (Guardados en la session)
$nombre = isset($datos_usuario['nombre_completo']) ? $datos_usuario['nombre_completo'] : ''; 

$email = isset($datos_usuario['email']) ? $datos_usuario['email'] : ''; 

$nivel = isset($datos_usuario['nivel']) ? $datos_usuario['nivel'] : ''; 
$nivel = isset($datos_usuario['nivel']) ? $datos_usuario['nivel'] : ''; 
if ($nivel === "Secundario") {
    $nivel = "<option value='Secundario' selected>Secundario</option>";
    $nivel .= "<option value='Primario'> Primario</option>";
    $nivel .= "<option value='Inicial'> Inicial</option>";
    $nivel .= "<option value='Docente'> Docente</option>";
    $nivel .= "<option value='Personal Administrativo'> Personal Administrativo</option>";
} 
if ($nivel === "Primario") {
    $nivel = "<option value='Secundario'>Secundario</option>";
    $nivel .= "<option value='Primario' selected> Primario</option>";
    $nivel .= "<option value='Inicial'> Inicial</option>";
    $nivel .= "<option value='Docente'> Docente</option>";
    $nivel .= "<option value='Personal Administrativo'> Personal Administrativo</option>";
} 
if ($nivel === "Inicial") {
    $nivel = "<option value='Secundario'>Secundario</option>";
    $nivel .= "<option value='Primario'> Primario</option>";
    $nivel .= "<option value='Inicial' selected> Inicial</option>";
    $nivel .= "<option value='Docente'> Docente</option>";
    $nivel .= "<option value='Personal Administrativo'> Personal Administrativo</option>";
} 
if ($nivel === "Docente") {
    $nivel = "<option value='Secundario'>Secundario</option>";
    $nivel .= "<option value='Primario' > Primario</option>";
    $nivel .= "<option value='Inicial'> Inicial</option>";
    $nivel .= "<option value='Docente' selected> Docente</option>";
    $nivel .= "<option value='Personal Administrativo'> Personal Administrativo</option>";
} 
if ($nivel === "Personal Administrativo") {
    $nivel = "<option value='Secundario'>Secundario</option>";
    $nivel .= "<option value='Primario'> Primario</option>";
    $nivel .= "<option value='Inicial'> Inicial</option>";
    $nivel .= "<option value='Docente' > Docente</option>";
    $nivel .= "<option value='Personal Administrativo' selected> Personal Administrativo</option>";
} 

$division = isset($datos_usuario['division']) ? $datos_usuario['division'] : ''; 
if ($division === "A") {
    $division = "<option value='A' selected>A</option>";
    $division .= "<option value='B'> B</option>";
} 
else {
    $division = "<option value='A'> A</option>";
    $division .= "<option value='B' selected>B</option>";
}

$curso = isset($datos_usuario['curso']) ? $datos_usuario['curso'] : ''; 
if ($curso === "1ro") {
    $curso = "<option value='1ro' selected>1ro</option>";
    $curso .= "<option value='2do'> 2do</option>";
    $curso .= "<option value='3ro'> 3ro</option>";
    $curso .= "<option value='4to'> 4to</option>";
    $curso .= "<option value='5to'> 5to</option>";
    $curso .= "<option value='6to'> 6to</option>";
} 
if ($curso === "2do") {
    $curso = "<option value='1ro'> 1ro</option>";
    $curso .= "<option value='2do' selected> 2do</option>";
    $curso .= "<option value='3ro'> 3ro</option>";
    $curso .= "<option value='4to'> 4to</option>";
    $curso .= "<option value='5to'> 5to</option>";
    $curso .= "<option value='6to'> 6to</option>";
} 
if ($curso === "3ro") {
    $curso = "<option value='1ro'> 1ro</option>";
    $curso .= "<option value='2do'> 2do</option>";
    $curso .= "<option value='3ro' selected> 3ro</option>";
    $curso .= "<option value='4to'> 4to</option>";
    $curso .= "<option value='5to'> 5to</option>";
    $curso .= "<option value='6to'> 6to</option>";
} 
if ($curso === "4to") {
    $curso = "<option value='1ro'>1ro</option>";
    $curso .= "<option value='2do'> 2do</option>";
    $curso .= "<option value='3ro'> 3ro</option>";
    $curso .= "<option value='4to' selected> 4to</option>";
    $curso .= "<option value='5to'> 5to</option>";
    $curso .= "<option value='6to'> 6to</option>";
} 
if ($curso === "5to") {
    $curso = "<option value='1ro'> 1ro</option>";
    $curso .= "<option value='2do' > 2do</option>";
    $curso .= "<option value='3ro'> 3ro</option>";
    $curso .= "<option value='4to'> 4to</option>";
    $curso .= "<option value='5to' selected> 5to</option>";
    $curso .= "<option value='6to'> 6to</option>";
} 
if ($curso === "6to") {
    $curso = "<option value='1ro'> 1ro</option>";
    $curso .= "<option value='2do'> 2do</option>";
    $curso .= "<option value='3ro' > 3ro</option>";
    $curso .= "<option value='4to'> 4to</option>";
    $curso .= "<option value='5to'> 5to</option>";
    $curso .= "<option value='6to' selected> 6to</option>";
} 

$dni = isset($datos_usuario['dni']) ? $datos_usuario['dni'] : ''; 
$usuario = isset($datos_usuario['usuario']) ? $datos_usuario['usuario'] : ''; 
}
?>
