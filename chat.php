<?php

// Permiso para abrir los archivos "require once..."
define('ACCESO_PERMITIDO', true);

// Verificar la sesión del usuario
require_once 'verificar_sesion.php';
require_once 'conexion_bd_libros.php';
require_once 'ventana_emergente.php';
require_once 'controles_admin.php';

// Verificar si el usuario es un administrador o no
$es_admin = $_SESSION['es_admin'] ?? false;

$resultado=mostrarChat();

if ($es_admin== false && $resultado =="oculto"){
    header("Location: home.php");
}

// Función para enviar un mensaje
function enviarMensaje($remitente, $destinatario, $mensaje, $tipo, $estado, $Fecha_envío, $id_usuario, $id_destinatario) {
    global $conn;

    $query = "INSERT INTO chat (Remitente, Destinatario, Mensaje, Tipo, Estado, Fecha_envío, id_usuario, id_destinatario) 
              VALUES ('$remitente', '$destinatario', '$mensaje', '$tipo', '$estado', '$Fecha_envío', '$id_usuario', '$id_destinatario')";

    $stmt = $conn->prepare($query);

    // Execute the query
    $stmt->execute();
}

function obtenerMensajesUsuario($usuario_id, $destinatario_id) {
    global $conn;

    $query = "SELECT Remitente, Mensaje FROM chat WHERE (id_usuario = ? OR id_destinatario = ?) AND (id_destinatario = ? OR id_usuario = ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $usuario_id, $usuario_id, $destinatario_id, $destinatario_id);

    // Execute the query
    $stmt->execute();

    // Get the result set from the statement
    $result = $stmt->get_result();

    // Fetch all the results as an associative array
    $mensajes = $result->fetch_all(MYSQLI_ASSOC);

    // Return the array of messages
    return $mensajes;
}


function obtenerListadoUsuarios() {
    global $conn;

    // Consulta para obtener la lista de usuarios y la última interacción en el chat
    $query = "SELECT r.id, r.nombre_completo, MAX(c.id) AS ultima_interaccion
              FROM registro AS r
              LEFT JOIN chat AS c ON r.id = c.id_usuario OR r.id = c.id_destinatario
              GROUP BY r.id, r.nombre_completo
              ORDER BY ultima_interaccion DESC NULLS LAST";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Obtener los resultados de la consulta
    $result = $stmt->get_result();
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);

    // Retornar el arreglo ordenado de usuarios
    return $usuarios;
}
function marcarMensajesLeidos($usuario_id) {
    global $conn;

    $query = "UPDATE chat SET Estado = 'visto' WHERE id_usuario = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);

    // Execute the query
    $stmt->execute();
}
function marcarMensajesLeidos2($usuario_id) {
    global $conn;

    $query = "UPDATE chat SET Estado = 'visto' WHERE id_destinatario = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);

    // Execute the query
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remitente = $datos_usuario['nombre_completo']; // Cambia esto por la variable de sesión que almacena el nombre del usuario actual

    $mensaje = $_POST['mensaje'];
    // Puedes modificar este campo según tus necesidades
    $estado = 'nuevo'; // Puedes modificar este campo según tus necesidades
    $Fecha_envío = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
    $id_usuario = $datos_usuario['id']; // Puedes modificar este campo según tus necesidades

    if (!isset($_SESSION['es_admin']) || empty($_SESSION['es_admin'])) {
        $id_destinatario = "1"; // ID del administrador (esto puede variar según tu base de datos)
        $destinatario = "admin";
        $tipo = 'usuario'; 
    } else {
        $id_destinatario = $_POST['id_destinatario'];
        $destinatario = $_POST['destinatario'];
        $tipo = 'admin'; 
    }
    enviarMensaje($remitente, $destinatario, $mensaje, $tipo, $estado, $Fecha_envío, $id_usuario, $id_destinatario);
    header("Location: chat.php?usuario_id=" . $id_destinatario);
    exit;
}



function obtenerNombreUsuario($usuario_id) {
    global $conn;

    $query = "SELECT nombre_completo FROM registro WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);

    // Execute the query
    $stmt->execute();

    // Get the result set from the statement
    $result = $stmt->get_result();

    // Fetch the result as an associative array
    $usuario = $result->fetch_assoc();

    // Return the name of the user
    return $usuario['nombre_completo'];
}


function obtenerEstado($usuario_id) {
    global $conn;

    $query = "SELECT Estado FROM chat WHERE id_usuario = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);

    // Execute the query
    $stmt->execute();

    // Get the result set from the statement
    $result = $stmt->get_result();

    // Fetch the result as an associative array
    $estado = $result->fetch_assoc();

    // Return the state or null if no rows are found
    return $estado ? $estado['Estado'] : null;
}


function obtenerCantNuevo($usuario_id) {
    global $conn;

    $query = "SELECT COUNT(*) as total FROM chat WHERE id_usuario = ? AND Estado = 'nuevo'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);

    // Execute the query
    $stmt->execute();

    // Get the result set from the statement
    $result = $stmt->get_result();

    // Fetch the result as an associative array
    $estado = $result->fetch_assoc();

    // Get the total count of new messages
    $totalNuevosMensajes = 0;
    if ($estado) {
        $totalNuevosMensajes = $estado['total'];
    }

    return $totalNuevosMensajes;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
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
    		// Llamada a la función pasando el valor de $es_admin
    		mostrar_menu_chat($_SESSION['es_admin']);
    		?>
			</ul>
		</nav>			
		<header>
		<div class="container-menu">
        <img src="https://colegiodesanjose.edu.ar/img/logo/logo.png" alt="Logo del Colegio de San José" class="logo">
        <nav class="menu3">
                <ul>
        <?php
            if ($es_admin) {
                echo '<li><a href="chat.php">LISTADO DE USUARIOS</a></li>';
            } else {
                // Menú para el usuario normal
                // ...
            }
            ?>
            </ul>
                </nav>		
    </div>
		</header>
	</div>	
<main>
        <?php
        if ($es_admin) {
            if (isset($_GET['usuario_id']) && is_numeric($_GET['usuario_id'])) {
                // Obtener el ID del usuario seleccionado
                $usuario_id_seleccionado = $_GET['usuario_id'];
                $id_usuario = $datos_usuario['id'];
                marcarMensajesLeidos($usuario_id_seleccionado);
                $mensajes = obtenerMensajesUsuario($usuario_id_seleccionado, $id_usuario);

                // Mostrar el nombre del usuario seleccionado
			$nombre_usuario_seleccionado = obtenerNombreUsuario($usuario_id_seleccionado);
			

			echo '<div class="container11">';
            echo '<div class="user-profile">';
            echo '<img src="perfil.png" alt="Perfil de ' . $nombre_usuario_seleccionado . '">';
            echo '<p class="titulo2">' . $nombre_usuario_seleccionado . '</p>';
            echo '</div>';
            echo '<div class="message-container">';

            // Mostrar los mensajes
            foreach ($mensajes as $mensaje) {
                // Determinar si el mensaje es del usuario actual
                $es_mensaje_propio = ($mensaje['Remitente'] === $datos_usuario['nombre_completo']);

                // Agregar la clase CSS según el tipo de mensaje (propio u otro)
                $clase_mensaje = $es_mensaje_propio ? 'mensaje-propio' : 'mensaje-otro';

                // Mostrar el mensaje con la clase correspondiente
                echo '<li class="mensaje ' . $clase_mensaje . '">';
                echo '<strong>'. '</strong> ' . $mensaje['Mensaje'];
                echo '</li>';
            }

            echo '</div>'; // Cerrar el contenedor de mensajes
            
            // Formulario para enviar un mensaje al usuario seleccionado
            echo '<form action="chat.php" method="post" onsubmit="enviarMensaje()">';
            echo '<input type="hidden" name="destinatario" value="' . $nombre_usuario_seleccionado . '">';
            echo '<input type="hidden" name="id_destinatario" value="' . $usuario_id_seleccionado . '">';
            echo '<div class="search-container">';
            echo '<textarea style="resize: none;" class="textarea" name="mensaje" placeholder="Escribe tu mensaje"></textarea>';
            echo '<button class="boton-enviar" type="submit">Enviar</button>';
            echo '</div>'; // Cerrar el contenedor11
            echo '</form>';
            echo '</div>'; // Cerrar el contenedor11
            } else {
                $searchText = $_POST['search'] ?? '';

// Consulta para obtener los usuarios con interacciones recientes en el chat
$query_with_interactions = "SELECT r.id, r.nombre_completo, MAX(c.id) AS ultima_interaccion
                           FROM registro AS r
                           LEFT JOIN chat AS c ON r.id = c.id_usuario OR r.id = c.id_destinatario
                           WHERE r.nombre_completo LIKE '%$searchText%'
                           GROUP BY r.id, r.nombre_completo
                           ORDER BY ultima_interaccion DESC";

$stmt_with_interactions = $conn->prepare($query_with_interactions);

// Execute the query
$stmt_with_interactions->execute();

// Get the result set from the statement
$result_with_interactions = $stmt_with_interactions->get_result();

// Fetch all the results as an associative array
$usuarios_with_interactions = $result_with_interactions->fetch_all(MYSQLI_ASSOC);

// Eliminar al usuario con ID 1 del array de usuarios con interacciones
foreach ($usuarios_with_interactions as $key => $usuario) {
    if ($usuario['id'] === 1) {
        unset($usuarios_with_interactions[$key]);
    }
}

// Consulta para obtener los usuarios sin interacciones en el chat
$query_without_interactions = "SELECT id, nombre_completo
                              FROM registro
                              WHERE nombre_completo LIKE '%$searchText%'
                              AND id NOT IN (SELECT DISTINCT id_usuario FROM chat UNION SELECT DISTINCT id_destinatario FROM chat)";

$stmt_without_interactions = $conn->prepare($query_without_interactions);

// Execute the query
$stmt_without_interactions->execute();

// Get the result set from the statement
$result_without_interactions = $stmt_without_interactions->get_result();

// Fetch all the results as an associative array
$usuarios_without_interactions = $result_without_interactions->fetch_all(MYSQLI_ASSOC);

// Combinar los resultados de ambos conjuntos de usuarios y eliminar duplicados
$usuarios_combined = array_merge($usuarios_with_interactions, $usuarios_without_interactions);
$usuarios = array();
$seen_ids = array();
foreach ($usuarios_combined as $usuario) {
    if (!in_array($usuario['id'], $seen_ids)) {
        $usuarios[] = $usuario;
        $seen_ids[] = $usuario['id'];
    }
}
$resultado=mostrarChat();
if ($resultado =="visible"){
$foto="activar.png";
}
else {
    $foto="desactivar.png";
}
    echo '<div class="container12">';
    echo '<p class="titulo6">LISTADO DE USUARIOS:</>';
    echo '<div class="search-container">
    <form id="user-search-form" method="post" action="chat.php">
        <input type="text" class="lindo-input" id="search-input" name="search" placeholder="Buscar usuario...">
        <button class="botón" type="submit">Buscar</button>
    </form>
    <form method="POST" action="ocultar_chat.php">
        <button type="submit" name="ocultar" value="ocultar" id="btn-editar"> 
        <img src="' . $foto .'" alt="Portada del libro">  
    </button>
    </form>
</div>';

    echo '<p><p>';
    echo '<div class="user-list-container">';
if (count($usuarios) > 0) {
  foreach ($usuarios as $usuario) {
    $estado = obtenerEstado($usuario['id']);
    $cant=obtenerCantNuevo($usuario['id']);
                    if($estado=="nuevo"){
                        
                        echo '<div class="user-link">';
                    echo '<a href="chat.php?usuario_id=' . $usuario['id'] . '">
                    <img src="perfil.png" alt="Perfil de ' . $usuario['nombre_completo'] . '">
                    <span class="user-name">' . $usuario['nombre_completo'] . '</span>
                    <span class="notificacion">'. $cant .'</span>
                  </a>';
                    echo '</div>';
                    }else{
                    echo '<div class="user-link">';
                    echo '<a href="chat.php?usuario_id=' . $usuario['id'] . '">
                    <img src="perfil.png" alt="Perfil de ' . $usuario['nombre_completo'] . '">
                    <span class="user-name">' . $usuario['nombre_completo'] . '</span>
                  </a>';
                    echo '</div>';
                    
                }
  }echo '</div>'; // Cerrar el contenedor11
} else {
  echo '<p>No se encontraron usuarios que coincidan con la búsqueda.</p>';
}
            }
            }
         else {

            
             $id_usuario = $datos_usuario['id'];
             marcarMensajesLeidos2($id_usuario); 
             $id_admin = "1"; // ID del administrador (esto puede variar según tu base de datos)
             $mensajes = obtenerMensajesUsuario($id_usuario, $id_admin);
 
             
			echo '<div class="container11">';
            echo '<div class="user-profile">';
            echo '<img src="perfil.png" alt="Perfil de">';
            echo '<p class="titulo2">CHAT CON LA BIBLIOTECARIA</p>';
            echo '</div>';
           
            echo '<div class="message-container">';

            // Mostrar los mensajes
            foreach ($mensajes as $mensaje) {
                // Determinar si el mensaje es del usuario actual
                $es_mensaje_propio = ($mensaje['Remitente'] === $datos_usuario['nombre_completo']);

                // Agregar la clase CSS según el tipo de mensaje (propio u otro)
                $clase_mensaje = $es_mensaje_propio ? 'mensaje-propio' : 'mensaje-otro';

                // Mostrar el mensaje con la clase correspondiente
                echo '<li class="mensaje ' . $clase_mensaje . '">';
                echo '<strong>'. '</strong> ' . $mensaje['Mensaje'];
                echo '</li>';
            }
            echo '</div>';
            
             $ban = Ban();
             // Formulario para enviar un mensaje al administrador
             
             echo '<form action="chat.php" method="post" onsubmit="enviarMensaje()">';
             echo '<input type="hidden" name="destinatario" value="' . $id_admin . '">';
             echo '<div class="search-container">';
             echo '<textarea style="resize: none;" class="textarea" name="mensaje" maxlength="1000" placeholder="Escribe tu mensaje"></textarea>';
             if ($ban=='disabled')  {
             echo '<button class="botón" name="enviar" id="enviar"  type="submit"' . $ban .' >Enviar</button>';
            }
            else  {
                echo '<button class="botón" type="submit" >Enviar</button>';
            }
             echo '</div>'; // Cerrar el contenedor11
             echo '</form>';
             echo '</div>'; // Cerrar el contenedor11
 
        }
        ?>

    </main>
    <footer>
        <p>Derechos reservados © 2023 Biblioteca virtual - Colegio de San José</p>
    </footer>
    <script>
    // Función para desplazar el scroll al fondo del contenedor de mensajes
    function scrollMensajes() {
        var mensajeContainer = document.querySelector('.message-container');
        mensajeContainer.scrollTop = mensajeContainer.scrollHeight;
    }

    // Llamar a la función para desplazar el scroll al cargar la página
    window.onload = scrollMensajes;

    // Llamar a la función después de enviar un mensaje para que el scroll se desplace al último mensaje
    function enviarMensaje() {
        scrollMensajes();
    }
</script>
<?php
  //muestra ventana emergente si es que hay
  if (isset($ventana_emergente)) {
    echo $ventana_emergente;
  }
  ?>
</body>
</html>
