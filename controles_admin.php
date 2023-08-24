<?php
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
} 
else {

//Permiso para abirir los archivos "require once..."


require_once 'conexion_bd_libros.php';




    function Nuevos_mensajes($usuario_id) {
        global $conn;
    
     
            $query = "SELECT COUNT(*) as total FROM chat WHERE id_destinatario = ? AND Estado = 'nuevo'";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $totalNuevosMensajes = $row['total'];
        
    
        return $totalNuevosMensajes;
    }
    
    function Nueva_reservación() {
        global $conn;
    
        $query = "SELECT Estado FROM reservaciones WHERE Estado = ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($query);
        $estado = "nuevo";
        $stmt->bind_param("s", $estado);
    
        // Execute the query
        $stmt->execute();
    
        // Get the result set from the statement
        $result = $stmt->get_result();
    
        // Fetch the result as an associative array
        $estado = $result->fetch_assoc();
    
        // Get the total number of new reservations
        $totalNuevasReservas = 0;
        if ($estado && $estado['Estado'] === 'nuevo') {
            $stmt->close(); // Close the first statement before preparing the next one
    
            $query = "SELECT COUNT(*) as total FROM reservaciones WHERE Estado = 'nuevo'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $totalNuevasReservas = $row['total'];
        }
    
        $stmt->close(); // Close the statement at the end
    
        return $totalNuevasReservas;
    }

    function PrestamoVencido() {
        global $conn;
        $today = date("Y-m-d");
    
        // Prepare the SQL query to select records with `Fecha_devolución` before today
        $query = "SELECT COUNT(*) as total FROM prestamos WHERE Fecha_devolución < ?";
        $stmt = $conn->prepare($query);
    
        // Bind today's date to the query parameter
        $stmt->bind_param("s", $today);
    
        // Execute the query
        $stmt->execute();
    
        // Get the result set from the statement
        $result = $stmt->get_result();
    
        // Fetch the result as an associative array
        $row = $result->fetch_assoc();
    
        // Get the total number of overdue devolutions
        $totalPrestamosVencidos = $row['total'];
    
        $stmt->close(); // Close the statement
    
        return $totalPrestamosVencidos;
    }

    function PrestamoVencidoUsuario() {
        global $conn;
        $today = date("Y-m-d");
        $datos_usuario = $_SESSION['datos_usuario'];
        $Dni=$datos_usuario["dni"];
        // Prepare the SQL query to select records with `Fecha_devolución` before today
        $query = "SELECT COUNT(*) as total FROM prestamos WHERE Fecha_devolución < ? AND Dni = ?";
        $stmt = $conn->prepare($query);
    
        // Bind today's date to the query parameter
        $stmt->bind_param("si", $today, $Dni);
    
        // Execute the query
        $stmt->execute();
    
        // Get the result set from the statement
        $result = $stmt->get_result();
    
        // Fetch the result as an associative array
        $row = $result->fetch_assoc();
    
        // Get the total number of overdue devolutions
        $totalPrestamosVencidos = $row['total'];
    
        $stmt->close(); // Close the statement
    
        return $totalPrestamosVencidos;
    }

    function PrestamoVencido2Usuario($ISBN) {
        global $conn;
        $today = date("Y-m-d");
        $datos_usuario = $_SESSION['datos_usuario'];
        $dni = $datos_usuario["dni"];
    
        // Prepare the SQL query to select records with `Fecha_devolución` before today
        $query = "SELECT ISBN FROM prestamos WHERE Fecha_devolución < ? AND Dni = ? AND ISBN = ?";
        $stmt = $conn->prepare($query);
    
        // Bind today's date, DNI, and ISBN to the query parameters
        $stmt->bind_param("sis", $today, $dni, $ISBN);
    
        // Execute the query
        $stmt->execute();
    
        // Bind the result to a variable
        $stmt->bind_result($isbn_value);
    
        // Fetch the result
        $stmt->fetch();
    
        // Close the statement
        $stmt->close();
    
        return $isbn_value;
    }

    function PrestamoVencido2($id) {
        global $conn;
        $today = date("Y-m-d");
    
        // Prepare the SQL query to select records with `Fecha_devolución` before today
        $query = "SELECT * FROM prestamos WHERE Fecha_devolución < ? AND id = ?";
        $stmt = $conn->prepare($query);
    
        // Bind today's date and ID to the query parameters
        $stmt->bind_param("si", $today, $id);
    
        // Execute the query
        $stmt->execute();
    
        // Get the result set from the statement
        $result = $stmt->get_result();
    
        // Check if there are any rows in the result set
        $hasOverdueDevolutions = $result->num_rows > 0;
    
        $stmt->close(); // Close the statement
    
        return $hasOverdueDevolutions;
    }

    function PrestamoVencido3($id) {
        global $conn;
        $today = date("Y-m-d");
    
        // Prepare the SQL query to select records with `Fecha_devolución` before today
        $query = "SELECT * FROM prestamos WHERE Fecha_devolución < ? AND Dni = ?";
        $stmt = $conn->prepare($query);
    
        // Bind today's date and ID to the query parameters
        $stmt->bind_param("si", $today, $id);
    
        // Execute the query
        $stmt->execute();
    
        // Get the result set from the statement
        $result = $stmt->get_result();
    
        // Check if there are any rows in the result set
        $hasOverdueDevolutions = $result->num_rows > 0;
    
        $stmt->close(); // Close the statement
    
        return $hasOverdueDevolutions;
    }

    function mostrar_menu_home($es_admin, $resultado)
    {

        $totalNuevasReservas = 0;
        $totalNuevosMensajes = 0;
        $totalPrestamosVencidos = 0;
        $totalPrestamosVencidosUsuario = 0;
        
        // Mostrar las notificaciones si el usuario es administrador
        if ($es_admin) {
            $totalNuevasReservas = Nueva_reservación();
            $totalPrestamosVencidos = PrestamoVencido();
        }
        $datos_usuario = $_SESSION['datos_usuario'];
        $totalNuevosMensajes = Nuevos_mensajes($datos_usuario['id']);

        echo '<li><a href="home.php" class="active">Home</a></li>';
        echo '<li><a href="catalogo.php" >Reservar</a></li>';
        
        if ($es_admin) {
            echo '<li><a href="mi_cuenta.php">Mi cuenta</a></li>';
            if ($totalPrestamosVencidos > 0 && $totalNuevasReservas == 0)  {
                echo  '<li><a href="subir_libros.php"><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas > 0 && $totalPrestamosVencidos > 0) {
                echo  '<li><a href="subir_libros.php"><span class="notificacion">' . $totalNuevasReservas . '</span><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';
            }
            if ($totalPrestamosVencidos == 0 && $totalNuevasReservas > 0)  {
                echo  '<li><a href="subir_libros.php"><span class="notificacion">' . $totalNuevasReservas . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas == 0 && $totalPrestamosVencidos == 0) {
                echo  '<li><a href="subir_libros.php">Controles del Admin</a></li>';
            }

            if ($totalNuevosMensajes > 0) {
                echo '<li><a href="chat.php"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
            } else {
                echo '<li><a href="chat.php">Chat virtual</a></li>';
            }
        } else {
            $totalPrestamosVencidosUsuario = PrestamoVencidoUsuario();
            if ($totalPrestamosVencidosUsuario > 0)  {

            echo '<li><a href="mi_cuenta.php"><span class="notificacion4">' . $totalPrestamosVencidosUsuario . '</span> Mi cuenta</a></li>';
        }
        else  {
            echo '<li><a href="mi_cuenta.php">Mi cuenta</a></li>';
        }
            if ($resultado=="visible"){
            if ($totalNuevosMensajes > 0) {
                echo '<li><a href="chat.php"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
            } else {
                echo '<li><a href="chat.php">Chat virtual</a></li>';
            }
        }
        }
    }

    function mostrar_menu_catalogo($es_admin, $resultado)
    {

        $totalNuevasReservas = 0;
        $totalNuevosMensajes = 0;
        $totalPrestamosVencidos = 0;
        $totalPrestamosVencidosUsuario = 0;
        // Mostrar las notificaciones si el usuario es administrador
        if ($es_admin) {
            $totalNuevasReservas = Nueva_reservación();
            $totalPrestamosVencidos = PrestamoVencido();
        }
        $datos_usuario = $_SESSION['datos_usuario'];
        $totalNuevosMensajes = Nuevos_mensajes($datos_usuario['id']);
        echo '<li><a href="home.php" >Home</a></li>';
        echo '<li><a href="catalogo.php" class="active">Reservar</a></li>';
        
        if ($es_admin) {
            echo '<li><a href="mi_cuenta.php">Mi cuenta</a></li>';
            if ($totalPrestamosVencidos > 0 && $totalNuevasReservas == 0)  {
                echo  '<li><a href="subir_libros.php"><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas > 0 && $totalPrestamosVencidos > 0) {
                echo  '<li><a href="subir_libros.php"><span class="notificacion">' . $totalNuevasReservas . '</span><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';
            }
            if ($totalPrestamosVencidos == 0 && $totalNuevasReservas > 0)  {
                echo  '<li><a href="subir_libros.php"><span class="notificacion">' . $totalNuevasReservas . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas == 0 && $totalPrestamosVencidos == 0) {
                echo  '<li><a href="subir_libros.php">Controles del Admin</a></li>';
            }
            if ($totalNuevosMensajes > 0) {
                echo '<li><a href="chat.php"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
            } else {
                echo '<li><a href="chat.php">Chat virtual</a></li>';
            }
        } else {
            $totalPrestamosVencidosUsuario = PrestamoVencidoUsuario();
            if ($totalPrestamosVencidosUsuario > 0) {
                echo '<li><a href="mi_cuenta.php"><span class="notificacion4">' . $totalPrestamosVencidosUsuario . '</span> Mi cuenta</a></li>';
            } else {
            echo '<li><a href="mi_cuenta.php">Mi cuenta</a></li>';
        }
            if ($resultado=="visible"){
                if ($totalNuevosMensajes > 0) {
                    echo '<li><a href="chat.php"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
                } else {
                    echo '<li><a href="chat.php">Chat virtual</a></li>';
                }
            }
        }
    }

    function mostrar_menu_micuenta($es_admin, $resultado)
    {
        $totalNuevasReservas = 0;
        $totalNuevosMensajes = 0;
        $totalPrestamosVencidos = 0;
        $totalPrestamosVencidosUsuario =0;
        // Mostrar las notificaciones si el usuario es administrador
        if ($es_admin) {
            $totalNuevasReservas = Nueva_reservación();
            $totalPrestamosVencidos = PrestamoVencido();
        }
        $datos_usuario = $_SESSION['datos_usuario'];
        $totalNuevosMensajes = Nuevos_mensajes($datos_usuario['id']);
        echo '<li><a href="home.php" >Home</a></li>';
        echo '<li><a href="catalogo.php" >Reservar</a></li>';
        
        if ($es_admin) {
            echo '<li><a href="mi_cuenta.php" class="active">Mi cuenta</a></li>';
            if ($totalPrestamosVencidos > 0 && $totalNuevasReservas == 0)  {
                echo  '<li><a href="subir_libros.php"><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas > 0 && $totalPrestamosVencidos > 0) {
                echo  '<li><a href="subir_libros.php"><span class="notificacion">' . $totalNuevasReservas . '</span><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';
            }
            if ($totalPrestamosVencidos == 0 && $totalNuevasReservas > 0)  {
                echo  '<li><a href="subir_libros.php"><span class="notificacion">' . $totalNuevasReservas . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas == 0 && $totalPrestamosVencidos == 0) {
                echo  '<li><a href="subir_libros.php">Controles del Admin</a></li>';
            }
            if ($totalNuevosMensajes > 0) {
                echo '<li><a href="chat.php"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
            } else {
                echo '<li><a href="chat.php">Chat virtual</a></li>';
            }
        } else {
            $totalPrestamosVencidosUsuario = PrestamoVencidoUsuario();
            if ($totalPrestamosVencidosUsuario > 0) {
                echo '<li><a href="mi_cuenta.php"><span class="notificacion4">' . $totalPrestamosVencidosUsuario . '</span> Mi cuenta</a></li>';
            } else {
            echo '<li><a href="mi_cuenta.php" class="active">Mi cuenta</a></li>';
        }
            if ($resultado=="visible"){
                if ($totalNuevosMensajes > 0) {
                    echo '<li><a href="chat.php"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
                } else {
                    echo '<li><a href="chat.php">Chat virtual</a></li>';
                }
            }
        }
    }

function mostrar_menu_micuenta2($es_admin)
{
    // Condición para no poder entrar desde la URL
    if (!defined('ACCESO_PERMITIDO')) {
        header("Location: home.php");
    } else {
        // Verificar si inició sesión el admin
        if ($es_admin) {
            // Código del botón del menú para el administrador
            echo '<li><a href="mi_cuenta.php">MI CUENTA</a></li>
            <li><a href="modificar_datos.php">MODIFICAR DATOS</a></li>
            <li><a href="cambiar_contraseña.php">CAMBIAR CONTRASEÑA</a></li>';
        } else {
            // Código del botón del menú para usuarios regulares
            echo '<li><a href="mi_cuenta.php">MI CUENTA</a></li>
            <li><a href="mis_reservaciones.php">MIS RESERVACIONES</a></li>';
            $totalPrestamosVencidosUsuario = PrestamoVencidoUsuario();
            if ($totalPrestamosVencidosUsuario > 0) {
                echo '<li><a href="mis_prestamos.php"><span class="notificacion4">' . $totalPrestamosVencidosUsuario . '</span> MIS PRESTAMOS</a></li>';
            } else {
            echo '<li><a href="mis_prestamos.php">MIS PRESTAMOS</a></li>';
        }
            echo '<li><a href="modificar_datos.php">MODIFICAR DATOS</a></li>
            <li><a href="cambiar_contraseña.php">CAMBIAR CONTRASEÑA</a></li>';
        }
    }
}


function mostrar_menu_admin($es_admin)
{
    $totalNuevasReservas = 0;
        $totalNuevosMensajes = 0;
        $totalPrestamosVencidos = 0;
        
        // Mostrar las notificaciones si el usuario es administrador
        if ($es_admin) {
            $totalNuevasReservas = Nueva_reservación();
            $totalPrestamosVencidos = PrestamoVencido();
        }
        $datos_usuario = $_SESSION['datos_usuario'];
        $totalNuevosMensajes = Nuevos_mensajes($datos_usuario['id']);
        echo '<li><a href="home.php" >Home</a></li>';
        echo '<li><a href="catalogo.php" >Reservar</a></li>';
        
        if ($es_admin) {
            echo '<li><a href="mi_cuenta.php" >Mi cuenta</a></li>';
            if ($totalPrestamosVencidos > 0 && $totalNuevasReservas == 0)  {
                echo  '<li><a href="subir_libros.php" class="active"><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas > 0 && $totalPrestamosVencidos > 0) {
                echo  '<li><a href="subir_libros.php" class="active"><span class="notificacion">' . $totalNuevasReservas . '</span><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';
            }
            if ($totalPrestamosVencidos == 0 && $totalNuevasReservas > 0)  {
                echo  '<li><a href="subir_libros.php" class="active"><span class="notificacion">' . $totalNuevasReservas . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas == 0 && $totalPrestamosVencidos == 0) {
                echo  '<li><a href="subir_libros.php" class="active">Controles del Admin</a></li>';
            }
            if ($totalNuevosMensajes > 0) {
                echo '<li><a href="chat.php"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
            } else {
                echo '<li><a href="chat.php">Chat virtual</a></li>';
            }
        } else {

            echo '<li><a href="mi_cuenta.php" >Mi cuenta</a></li>';
            if ($totalNuevosMensajes > 0) {
                echo '<li><a href="chat.php"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
            } else {
                echo '<li><a href="chat.php">Chat virtual</a></li>';
            }
        }
}


function mostrar_menu_admin2($es_admin)
{
    $totalNuevasReservas = 0;
    $totalNuevosMensajes = 0;
    $totalPrestamosVencidos = 0;
    // Mostrar las notificaciones si el usuario es administrador
    if ($es_admin) {
        $totalNuevasReservas = Nueva_reservación();
         $totalPrestamosVencidos = PrestamoVencido();
    }
    $datos_usuario = $_SESSION['datos_usuario'];
        $totalNuevosMensajes = Nuevos_mensajes($datos_usuario['id']);

            echo  '<ul>
            <li class="dropdown">
            <a href="#">CARGAR LIBROS</a>
                <ul class="dropdown-content">
                <li><a href="subir_libros.php">CON ISBN</a></li>
                <li><a href="cargar_libro_manual.php">MANUAL</a></li>
                <li><a href="subir_archivocsv.php">CSV</a></li>
                </ul>
                </li>';
                
                if ($totalNuevasReservas > 0) {
                echo '<li><a href="modificar_reservaciones.php"><span class="notificacion">' . $totalNuevasReservas . '</span> MODIFICAR RESERVACIONES</a></li>';
            }
            else {
                echo '<li><a href="modificar_reservaciones.php">MODIFICAR RESERVACIONES</a></li>';
            }
            
            if ($totalPrestamosVencidos > 0) {
                echo '<li><a href="modificar_prestamos.php"><span class="notificacion4">' . $totalPrestamosVencidos . '</span> MODIFICAR PRESTAMOS</a></li>';
            }
            else {
                echo '<li><a href="modificar_prestamos.php">MODIFICAR PRESTAMOS</a></li>';
            }
            echo'
                <li><a href="modificar_libros.php">MODIFICAR LIBROS</a></li>
                <li><a href="modificar_registros.php">MODIFICAR REGISTROS</a></li>
                <li><a href="historial_prestamos.php">HISTORIAL DE PRESTAMOS</a></li>
            </ul>';
        
        }





function mostrar_menu_chat($es_admin)
{
    $totalNuevasReservas = 0;
        $totalNuevosMensajes = 0;
        $totalPrestamosVencidos = 0;
        
        // Mostrar las notificaciones si el usuario es administrador
        if ($es_admin) {
            $totalNuevasReservas = Nueva_reservación();
            $totalPrestamosVencidos = PrestamoVencido();
        }
        $datos_usuario = $_SESSION['datos_usuario'];
        $totalNuevosMensajes = Nuevos_mensajes($datos_usuario['id']);
        echo '<li><a href="home.php" >Home</a></li>';
        echo '<li><a href="catalogo.php" >Reservar</a></li>';
        
        if ($es_admin) {
            echo '<li><a href="mi_cuenta.php" >Mi cuenta</a></li>';
            if ($totalPrestamosVencidos > 0 && $totalNuevasReservas == 0)  {
                echo  '<li><a href="subir_libros.php"><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas > 0 && $totalPrestamosVencidos > 0) {
                echo  '<li><a href="subir_libros.php"><span class="notificacion">' . $totalNuevasReservas . '</span><span class="notificacion4">' . $totalPrestamosVencidos . '</span> Controles del Admin</a></li>';
            }
            if ($totalPrestamosVencidos == 0 && $totalNuevasReservas > 0)  {
                echo  '<li><a href="subir_libros.php"><span class="notificacion">' . $totalNuevasReservas . '</span> Controles del Admin</a></li>';

            }
            if ($totalNuevasReservas == 0 && $totalPrestamosVencidos == 0) {
                echo  '<li><a href="subir_libros.php">Controles del Admin</a></li>';
            }
            if ($totalNuevosMensajes > 0) {
                echo '<li><a href="chat.php" class="active"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
            } else {
                echo '<li><a href="chat.php" class="active">Chat virtual</a></li>';
            }
        } else {
            $totalPrestamosVencidosUsuario = PrestamoVencidoUsuario();
            if ($totalPrestamosVencidosUsuario > 0) {
                echo '<li><a href="mi_cuenta.php"><span class="notificacion4">' . $totalPrestamosVencidosUsuario . '</span> Mi cuenta</a></li>';
            } else {
            echo '<li><a href="mi_cuenta.php">Mi cuenta</a></li>';
        }
            if ($totalNuevosMensajes > 0) {
                echo '<li><a href="chat.php" class="active"><span class="notificacion">' . $totalNuevosMensajes . '</span> Chat virtual</a></li>';
            } else {
                echo '<li><a href="chat.php" class="active">Chat virtual</a></li>';
            }
        }
}


function MostrarRegistroBoton()
{
    
    global $conn;
$ocultar="ocultar";
    // Preparar la consulta con un marcador de posición (?)
$sql = "SELECT * FROM habilitar_registro WHERE Estado = ? AND id =1";
$stmt = $conn->prepare($sql);

// Vincular el valor de $ocultar al marcador de posición (?)
$stmt->bind_param("s", $ocultar);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();
    //Verificar que no se repita el nombre de usuario
    if ($result && $result->num_rows > 0) {
    echo '';
    }
    else{  
    echo '<button class="botón"><a href="registrarse.php">Registrarse</a></button>';  
    }
}

function mostrarChat()
{
    
    global $conn;

$ocultar="ocultar";
    // Preparar la consulta con un marcador de posición (?)
$sql = "SELECT * FROM habilitar_registro WHERE Estado = ? AND id = 2";
$stmt = $conn->prepare($sql);

// Vincular el valor de $ocultar al marcador de posición (?)
$stmt->bind_param("s", $ocultar);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();
    //Verificar que no se repita el nombre de usuario
    if ($result && $result->num_rows > 0) {
    $resultado="oculto";
    }
    else{  
    $resultado="visible";
    }

    return $resultado;
}

function mostrarReseñas()
{
    
    global $conn;

$ocultar="ocultar";
    // Preparar la consulta con un marcador de posición (?)
$sql = "SELECT * FROM habilitar_registro WHERE Estado = ? AND id = 3";
$stmt = $conn->prepare($sql);

// Vincular el valor de $ocultar al marcador de posición (?)
$stmt->bind_param("s", $ocultar);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();
    //Verificar que no se repita el nombre de usuario
    if ($result && $result->num_rows > 0) {
    $resultado="oculto";
    }
    else{  
    $resultado="visible";
    }

    return $resultado;
}

function Ban()
{
    
    global $conn;
$datos_usuario = $_SESSION['datos_usuario'];

$id=$datos_usuario["id"];
    // Preparar la consulta con un marcador de posición (?)
$sql = "SELECT * FROM registro WHERE id = ? AND Ban = 'ban' ";
$stmt = $conn->prepare($sql);

// Vincular el valor de $ocultar al marcador de posición (?)
$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();
    //Verificar que no se repita el nombre de usuario
    if ($result && $result->num_rows > 0) {
    $ban = 'disabled';
    }
    else{  
    $ban =  '';  
    }

    return $ban;
}

function MostrarRegistro()
{
    
    global $conn;

$ocultar="ocultar";
    // Preparar la consulta con un marcador de posición (?)
$sql = "SELECT * FROM habilitar_registro WHERE Estado = ? AND id = 1";
$stmt = $conn->prepare($sql);

// Vincular el valor de $ocultar al marcador de posición (?)
$stmt->bind_param("s", $ocultar);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();
    //Verificar que no se repita el nombre de usuario
    if ($result && $result->num_rows > 0) {
    echo '';
    }
    else{  
    echo '<li><a href="registrarse.php">Registrarse</a></li>';  
    }
}



function botónHabilitado($isbn)
{
    global $conn;

    $datos_usuario = $_SESSION['datos_usuario'];
    $sql = "SELECT * FROM libros WHERE isbn = '$isbn' AND `Número de ejemplares disponibles` > 0";
    $result = $conn->query($sql);
    //Verificar que no se repita el nombre de usuario
    if ($result && $result->num_rows > 0) {
        $sql = "SELECT * FROM reservaciones WHERE ISBN = '$isbn' AND `Dni` = '{$datos_usuario["dni"]}'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $error = '<p class="error-message">Usted ya tiene reservado este libro</p>';

    $_SESSION['error'] = $error;
            $botón = '<button type="submit" class="botón" name="reserva" value="reserva" disabled>Reservar</button>';  
        }
        else {
            $sql = "SELECT * FROM prestamos WHERE ISBN = '$isbn' AND `Dni` = '{$datos_usuario["dni"]}'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $error = '<p class="error-message">Usted ya tiene prestado este libro</p>';

    $_SESSION['error'] = $error;
    $botón = '<button type="submit" class="botón" name="reserva" value="reserva" disabled>Reservar</button>';  
        }
        else {
            $sql_reservaciones = "SELECT COUNT(*) as total_reservaciones FROM reservaciones WHERE `Dni` = '{$datos_usuario["dni"]}'";
            $result_reservaciones = $conn->query($sql_reservaciones);
            $row_reservaciones = $result_reservaciones->fetch_assoc();
            $total_reservaciones = $row_reservaciones['total_reservaciones'];

            $sql_prestamos = "SELECT COUNT(*) as total_prestamos FROM prestamos WHERE `Dni` = '{$datos_usuario["dni"]}'";
            $result_prestamos = $conn->query($sql_prestamos);
            $row_prestamos = $result_prestamos->fetch_assoc();
            $total_prestamos = $row_prestamos['total_prestamos'];

            $total_sumado = $total_reservaciones + $total_prestamos;
            if ($total_sumado>2){
                $error = '<p class="error-message">Usted ya ha superado el límite de reservas/prestamos</p>';

    $_SESSION['error'] = $error;
    $botón = '<button type="submit" class="botón" name="reserva" value="reserva" disabled>Reservar</button>'; 
            }
            else {
                $botón = '<button type="submit" class="botón" name="reserva" value="reserva">Reservar</button>'; 
            }
        }
        }
    }   
    else{
        $error = '<p class="error-message">El libro se encuentra agotado</p>';

    $_SESSION['error'] = $error;  
    $botón = '<button type="submit" class="botón" name="reserva" value="reserva" disabled>Reservar</button>';  
    }
    return $botón;
}

function botónHabilitadoReseñas($isbn, $ban)
{
    $datos_usuario = $_SESSION['datos_usuario'];
    $dni=$datos_usuario['dni'];
    global $conn;

    $sql = "SELECT * FROM reseñas WHERE ISBN = '$isbn' AND `Dni` = '$dni'";
    $result = $conn->query($sql);
    //Verificar que no se repita el nombre de usuario
    if ($result && $result->num_rows > 0) {
    echo '<button class="disabled" name="reseña" value="reseña" type="submit" disabled>Enviar reseña</button>';
    }
    else{  
    echo '<button class="botón" name="reseña" value="reseña" type="submit"' . $ban .'>Enviar reseña</button>';  
    }
}

function LibroAgotado($isbn)
{
    
    global $conn;

    $sql = "SELECT * FROM libros WHERE isbn = '$isbn' AND `Número de ejemplares disponibles` > 0";
    $result = $conn->query($sql);
    //Verificar que no se repita el nombre de usuario


}
}
?>