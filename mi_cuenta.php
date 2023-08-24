<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// Verificar la sesión del usuario
require_once 'controles_admin.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" type="image/png" href="/logo6.png"/>
    <title>Biblioteca Virtual</title>
  <link rel="stylesheet" href="style18.css">

</head>
<body> 
    <div class="container">
		<nav>
			<ul>
      <?php
    		$resultado=mostrarChat();
    		mostrar_menu_micuenta($_SESSION['es_admin'], $resultado);
    		?>
			</ul>
		</nav>
		<header>
    <div class="container-menu">
        <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José" class="logo">
        <nav class="menu3">
					<ul>
          <?php
    		// Llamada a la función pasando el valor de $es_admin
    		mostrar_menu_micuenta2($_SESSION['es_admin']);
    		?>
					</ul>
				</nav>
			</div>
		</header>
	</div>
  <main>
    <section class="mi-cuenta">
	<ul>
  <div class="container6">
        <li>
        <p class="titulo2">MI CUENTA:</p>
            <p>Bienvenido/a, <?php echo $datos_usuario['nombre_completo']; ?></p>
			<form method="post" action="borrar_datos.php" >
  			<input type="submit" value="Cerrar sesión" class="botón">
			</form>
        </li>
    <li>
    <p class="titulo2">DETALLES DEL USUARIO:</p>
        
        <p><?php $datos_usuario = $_SESSION['datos_usuario'];//obtener y mostrar datos del usuario
        if (!empty($datos_usuario['email'])){
          echo "<h4>Email:</h4>";
        echo $datos_usuario['email']; }?></p>
        <h4>DNI:</h4>
        <p><?php echo $datos_usuario['dni']; ?></p>
        <h4>Nivel:</h4>
        <p><?php echo $datos_usuario['nivel']; ?></p>
        <?php 
        if (!empty($datos_usuario['curso'])){
        echo "<h4>Curso:</h4>";
        echo "<p>" . $datos_usuario['curso'] . "</p>"; }?>
        <?php 
        if (!empty($datos_usuario['division'])){
        echo "<h4>División:</h4>";
        echo "<p>" . $datos_usuario['division'] . "</p>"; }?>
    </li>
</div>
</ul>
    </section>
  </main>
  <footer>
    <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
  </footer>
</body>
</html>
