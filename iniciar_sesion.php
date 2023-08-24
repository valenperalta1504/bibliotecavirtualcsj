<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// verificar si tiene sesión ya iniciada
require_once 'verificar_sesion2.php';
// verificar si existe un msj de error
require_once 'mensaje_de_error.php';
// verificar si existe un msj de error
require_once 'controles_admin.php';
?>

<!DOCTYPE html>
<html lang="en">
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
            <li><a href="iniciar_sesion.php"class="active" >Iniciar Sesión</a></li>
            <?php
    		// Llamada a la función pasando el valor de $es_admin
    		MostrarRegistro();
    		?>
            </ul>
			</ul>
		</nav>
    <header>
    <div class="container-menu">
        <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José" class="logo">

        </div>
    </header>
</div>
    <div class="container3">
    <p class="titulo4">INICIAR SESIÓN:</p>
    <p><p>
        <form method="post" action="confirmar_iniciodesesion.php">
            <?php
                if (isset($error)) {
                    echo $error;}
            ?>
            <div class="form-group">
                <label for="usuario">Usuario o correo electrónico:</label>
                <br><br>
                <input class="lindo-input2" type="text" id="usuario" name="usuario" maxlength="30">
                <br><br>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <br><br>
                <input class="lindo-input2" type="password" id="contrasena" name="contrasena" maxlength="30">
            </div>
            <br><br>
            <div >
                <button type="submit" class="botón">Iniciar Sesión</button>
        </form>
        <?php
    		// Llamada a la función pasando el valor de $es_admin
    		MostrarRegistroBoton();
    		?>    
            </div>
    </div>
    <footer class="footerpro">
        <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
    </footer>
</body>
</html>
