<?php
//Permiso para abirir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
// verificar admin
require_once 'verificar_admin.php';
// verificar si existe un mensaje de error
require_once 'mensaje_de_error.php';
// verificar si hay ventana emergente
require_once 'ventana_emergente.php'; 
// Verificar la sesión del usuario
require_once 'controles_admin.php';
// Verificar la sesión del usuario
require_once 'conexion_bd_libros.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<link rel="icon" type="image/png" href="/logo6.png"/>
    <title>Biblioteca Virtual</title>
    <link rel="stylesheet" href="style22.css">

</head>
<body>
    <div class="container">
        <nav>
            <ul>
            <?php
    		// Llamada a la función pasando el valor de $es_admin
    		mostrar_menu_admin($_SESSION['es_admin']);
    		?>
            </ul>
        </nav>
        <header> 
        <div class="container-menu">
        <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José" class="logo">
                <nav class="menu3">
                <?php
    		// Llamada a la función pasando el valor de $es_admin
    		mostrar_menu_admin2($_SESSION['es_admin']);
    		?>
                </nav>
            </div>
        </header>
    </div>
    <main>
        <body>
        <div class="container3">
    <p class="titulo4">INSERTAR PRESTAMO:</p>
    <p><p>
    <?php //Mostrar msj de error
                if (isset($error)) {
                    echo $error;}
            ?>
<form method="POST" action="nuevo_prestamo.php">
    <?php
        // Obtener los datos de los usuarios (simulados aquí)
        $usuarios = []; // Aquí almacenarás los datos obtenidos de la base de datos
        // Ejemplo de cómo podrías obtener los datos de la base de datos
        $query = "SELECT dni, nombre_completo, nivel, curso, division FROM registro";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }
    ?>

    <label for="Usuario">Seleccionar usuario:</label>
    <input type="text" id="userSearchInput" class="lindo-input5" placeholder="Buscar usuario...">
    <div class="user-list-container2" style="width: 500px;"> <!-- Ajusta el ancho aquí -->

        <div class="user-list">
            <?php foreach ($usuarios as $usuario) { ?>
                <label class="user-item">
                    <input type="radio" name="selectedUsers" value="<?php echo $usuario['dni']; ?>">
                    <span class="user-info">
                        <?php echo $usuario['nombre_completo'] . ' - ' . $usuario['curso'] . ' ' . $usuario['division'] . ' ' . $usuario['nivel']; ?>
                    </span>
                </label>
            <?php } ?>
        </div>
    </div> 
    <?php
        // Obtener los datos de los usuarios (simulados aquí)
        $libros = []; // Aquí almacenarás los datos obtenidos de la base de datos
        // Ejemplo de cómo podrías obtener los datos de la base de datos
        $query = "SELECT Título, ISBN FROM libros";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $libros[] = $row;
        }
    ?>
    <br><br>
<label for="Usuario">Seleccionar libro:</label>
<input type="text" id="bookSearchInput" class="lindo-input5" placeholder="Buscar libro...">
<div class="book-list-container" style="width: 500px;">
    <div class="book-list">
        <?php foreach ($libros as $libro) { ?>
            <label class="book-item">
                <input type="radio" name="isbn" value="<?php echo $libro['ISBN']; ?>">
                <span class="book-info">
                    <?php echo $libro['Título'] . ' - ' . $libro['ISBN']; ?>
                </span>
            </label>
        <?php } ?>

    </div>
</div>
<br><br>
<button class="botón" type="submit" name="insertar" value="insertar">Insertar</button>
</form>
</main>
<footer>
	<p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
</footer>
<?php
  //muestra ventana emergente si es que hay
  if (isset($ventana_emergente)) {
    echo $ventana_emergente;
  }
  ?>
  
  <script>
    const userSearchInput = document.getElementById('userSearchInput');
    const bookSearchInput = document.getElementById('bookSearchInput');
    const userItems = document.querySelectorAll('.user-list-container2 .user-item');
    const bookItems = document.querySelectorAll('.book-list-container .book-item');

    userSearchInput.addEventListener('input', function () {
        const searchTerm = userSearchInput.value.toLowerCase();
        userItems.forEach(item => {
            const userInfo = item.querySelector('.user-info').textContent.toLowerCase();
            if (userInfo.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    bookSearchInput.addEventListener('input', function () {
        const searchTerm = bookSearchInput.value.toLowerCase();
        bookItems.forEach(item => {
            const bookInfo = item.querySelector('.book-info').textContent.toLowerCase();
            if (bookInfo.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>