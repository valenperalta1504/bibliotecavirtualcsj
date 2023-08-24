<?php
// Permiso para abrir los archivos "require once..."
define('ACCESO_PERMITIDO', true);
// Conectar con la bd
require_once 'conexion_bd_libros.php';
// Condición para comprobar que se ejecuta como action del método post y no desde la url
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario de inicio de sesión
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Comprobar si hay campos vacíos
    if (empty($usuario) || empty($contrasena)) {
        $error = '<p class="error-message">Debe completar todos los campos para efectuar el inicio de sesión</p>';
        session_start();
        $_SESSION['error'] = $error;
        header("Location: iniciar_sesion.php");
        exit;
    }

    // Buscar el registro del usuario en la base de datos based on username or email
    $query = "SELECT * FROM registro WHERE usuario = '$usuario' OR email = '$usuario'";
    $resultado = mysqli_query($conn, $query);
    $registro = mysqli_fetch_assoc($resultado);

    // Comprobación de errores de consulta
    if (!$resultado) {
        die("Error al realizar la consulta: " . mysqli_error($conn));
    }

    // Comprobación de si los datos son correctos
    if ($registro && password_verify($contrasena, $registro['contrasena'])) {
        echo "Inicio de sesión correcto";
        // Consulta SQL para obtener los datos del usuario
        $query = "SELECT * FROM registro WHERE usuario = '$usuario' OR email = '$usuario'";
        $resultado = mysqli_query($conn, $query);

        // Verifica si se encontró el usuario en la base de datos
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            // Extrae los datos del usuario de la consulta
            $datos_usuario = mysqli_fetch_assoc($resultado);

            // Almacena los datos del usuario en una sesión con claves asociativas
            session_start();
            $_SESSION['datos_usuario'] = array(
                'id' => $datos_usuario['id'],
                'nombre_completo' => $datos_usuario['nombre_completo'],
                'email' => $datos_usuario['email'],
                'nivel' => $datos_usuario['nivel'],
                'dni' => $datos_usuario['dni'],
                'curso' => $datos_usuario['curso'],
                'division' => $datos_usuario['division'],
                'usuario' => $datos_usuario['usuario']
            );
        }

        // Verificar si el usuario and the password match the admin credentials
        if ($usuario === 'admin' || $usuario === 'admin2') {
        $_SESSION['es_admin'] = true;
        }else {
            $_SESSION['es_admin'] = false;
        }
        // Si los datos coinciden ingresa a home.php
        header("Location: home.php");
        exit;
    } else {
        // Condición si los datos de inicio de sesión son incorrectos
        $error = '<p class="error-message">La contraseña, nombre de usuario y/o correo electrónico son incorrectos</p>';
        session_start();
        $_SESSION['error'] = $error;
        header("Location: iniciar_sesion.php");
        exit;
    }
} else {
    header("Location: home.php");
    exit;
}

// Cerrar la conexión a la base de datos
require_once 'cerrar_conexion_bd.php';
?>
