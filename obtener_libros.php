<?php

// Conectar con la bd
require_once 'conexion_bd_libros.php';
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
  } else {
// Función para obtener todos los registros de la tabla
function obtenerRegistros() {
    global $conn;
    $query = "SELECT * FROM libros ORDER BY id ASC";

    if (isset($_GET['search']) && !empty($_GET['search'])) {
      $searchTerm = $_GET['search'];
      $query = "SELECT * FROM libros WHERE Título LIKE '%$searchTerm%' ORDER BY id ASC";
    }

    $result = mysqli_query($conn, $query);
    $registros = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $registros;
}
// Función para obtener todos los registros de la tabla
function obtenerReservaciones() {
    global $conn;
  $query = "SELECT * FROM reservaciones ORDER BY id ASC";

  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = "SELECT * FROM reservaciones WHERE 
          Dni LIKE '%$searchTerm%' OR 
          Nombre_alumno LIKE '%$searchTerm%' OR 
          Nivel LIKE '%$searchTerm%' OR 
          Curso LIKE '%$searchTerm%' OR 
          División LIKE '%$searchTerm%' OR 
          ISBN LIKE '%$searchTerm%' OR 
          Nombre_libro LIKE '%$searchTerm%' OR 
          Fecha LIKE '%$searchTerm%'
          ORDER BY id ASC";
  }

  $result = mysqli_query($conn, $query);
  $reservaciones = mysqli_fetch_all($result, MYSQLI_ASSOC);
  return $reservaciones;
}

function obtenerPrestamos() {
    global $conn;
  $query = "SELECT * FROM prestamos ORDER BY id ASC";

  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = "SELECT * FROM prestamos WHERE 
          Dni LIKE '%$searchTerm%' OR 
          Nombre_alumno LIKE '%$searchTerm%' OR 
          Nivel LIKE '%$searchTerm%' OR 
          Curso LIKE '%$searchTerm%' OR 
          División LIKE '%$searchTerm%' OR 
          ISBN LIKE '%$searchTerm%' OR 
          Nombre_libro LIKE '%$searchTerm%' OR 
          Fecha_retiro LIKE '%$searchTerm%' OR 
          Fecha_devolución LIKE '%$searchTerm%'
          ORDER BY id ASC";
  }

  $result = mysqli_query($conn, $query);
  $prestamos = mysqli_fetch_all($result, MYSQLI_ASSOC);
  return $prestamos;
}

function marcarVisto($usuario_id) {
    global $conn;

    $query = "UPDATE reservaciones SET Estado = 'visto' WHERE id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);

    // Execute the query
    $stmt->execute();
}

function generateFormFields($reserva)
{ 
    $Nombre_alumno = isset($reserva['Nombre_alumno']) ? $reserva['Nombre_alumno'] : '';
    $Dni = isset($reserva['Dni']) ? $reserva['Dni'] : '';
    
    $Nivel = isset($reserva['Nivel']) ? $reserva['Nivel'] : '';
    $NivelOptions = "<option value='Secundario'>Secundario</option>";
    $NivelOptions .= "<option value='Primario'>Primario</option>";
    $NivelOptions .= "<option value='Inicial'>Inicial</option>";
    $NivelOptions .= "<option value='Docente'>Docente</option>";
    $NivelOptions .= "<option value='Personal Administrativo'>Personal Administrativo</option>";
    $NivelOptions = str_replace("value='$Nivel'", "value='$Nivel' selected", $NivelOptions);
    
    $División = isset($reserva['División']) ? $reserva['División'] : '';
    $DivisionOptions = "<option value='A'>A</option>";
    $DivisionOptions .= "<option value='B'>B</option>";
    $DivisionOptions = str_replace("value='$División'", "value='$División' selected", $DivisionOptions);
    
    $Curso = isset($reserva['Curso']) ? $reserva['Curso'] : '';
    $CursoOptions = "<option value='1ro'>1ro</option>";
    $CursoOptions .= "<option value='2do'>2do</option>";
    $CursoOptions .= "<option value='3ro'>3ro</option>";
    $CursoOptions .= "<option value='4to'>4to</option>";
    $CursoOptions .= "<option value='5to'>5to</option>";
    $CursoOptions .= "<option value='6to'>6to</option>";
    $CursoOptions = str_replace("value='$Curso'", "value='$Curso' selected", $CursoOptions);
    
    $ISBN = isset($reserva['ISBN']) ? $reserva['ISBN'] : '';
    $Fecha = isset($reserva['Fecha']) ? $reserva['Fecha'] : '';
    
    $html = '
    <form method="post" action="modificar_reserva2.php">
        <div class="form-group">
            <label for="Nombre_alumno">Nombre completo:</label>
            <input class="lindo-input2" type="text" id="Nombre_alumno" name="Nombre_alumno" maxlength="30" value="' . $Nombre_alumno . '" >
        </div>
        <div class="form-group">
            <label for="Dni">Dni:</label>
            <h1><h1>
            <input  class="lindo-input2"  type="text" id="Dni" name="Dni" oninput="javascript: if (this.value.length > 8) this.value = this.value.slice(0, 8);" value="' . $Dni . '" >
        </div>
        <div class="form-group">
            <h4>Nivel:</h4>
            <select id="Nivel" name="Nivel">' . $NivelOptions . '</select>
        </div>
        <div class="form-group">
            <h4>Curso:</h4>
            <select id="Curso" name="Curso">' . $CursoOptions . '</select>
        </div>
        <div class="form-group">
            <h4>División:</h4>
            <select id="División" name="División">' . $DivisionOptions . '</select>
        </div>
        <div class="form-group">
            <label for="ISBN">ISBN/Código interno:</label>
            <h1><h1>
            <input  class="lindo-input2"  type="text" id="ISBN" name="ISBN" oninput="javascript: if (this.value.length > 13) this.value = this.value.slice(0, 13);" value="' . $ISBN . '" >
        </div>
        <div class="form-group">
            <label for="Fecha">Fecha:</label>
            <p><p>
            <input  class="lindo-input2" type="date" id="Fecha" name="Fecha" value="' . $Fecha . '" min="' . date('Y-m-d') . '" max="' . date('Y-m-d', strtotime('+1 month')) . '" >
        </div>
        <button class="botón" type="submit" name="actualizar" value="actualizar">Modificar</button>
    </form>';
    
    return $html;
}

function prestamoModificar($prestamo)
{
    $Nombre_alumno = isset($prestamo['Nombre_alumno']) ? $prestamo['Nombre_alumno'] : '';
    $Dni = isset($prestamo['Dni']) ? $prestamo['Dni'] : '';
    
    $Nivel = isset($prestamo['Nivel']) ? $prestamo['Nivel'] : '';
    $NivelOptions = "<option value='Secundario'>Secundario</option>";
    $NivelOptions .= "<option value='Primario'>Primario</option>";
    $NivelOptions .= "<option value='Inicial'>Inicial</option>";
    $NivelOptions .= "<option value='Docente'>Docente</option>";
    $NivelOptions .= "<option value='Personal Administrativo'>Personal Administrativo</option>";
    $NivelOptions = str_replace("value='$Nivel'", "value='$Nivel' selected", $NivelOptions);
    
    $División = isset($prestamo['División']) ? $prestamo['División'] : '';
    $DivisionOptions = "<option value='A'>A</option>";
    $DivisionOptions .= "<option value='B'>B</option>";
    $DivisionOptions = str_replace("value='$División'", "value='$División' selected", $DivisionOptions);
    
    $Curso = isset($prestamo['Curso']) ? $prestamo['Curso'] : '';
    $CursoOptions = "<option value='1ro'>1ro</option>";
    $CursoOptions .= "<option value='2do'>2do</option>";
    $CursoOptions .= "<option value='3ro'>3ro</option>";
    $CursoOptions .= "<option value='4to'>4to</option>";
    $CursoOptions .= "<option value='5to'>5to</option>";
    $CursoOptions .= "<option value='6to'>6to</option>";
    $CursoOptions = str_replace("value='$Curso'", "value='$Curso' selected", $CursoOptions);
    
    $ISBN = isset($prestamo['ISBN']) ? $prestamo['ISBN'] : '';
    $Fecha_retiro = isset($prestamo['Fecha_retiro']) ? $prestamo['Fecha_retiro'] : '';
    $Fecha_devolución = isset($prestamo['Fecha_devolución']) ? $prestamo['Fecha_devolución'] : '';
    
    $html = '
    <form method="post" action="modificar_prestamo2.php">
        <div class="form-group">
            <label for="Nombre_alumno">Nombre completo:</label>
            <input class="lindo-input2" type="text" id="Nombre_alumno" name="Nombre_alumno" maxlength="30" value="' . $Nombre_alumno . '" >
        </div>
        <div class="form-group">
            <label for="Dni">Dni:</label>
            <h1><h1>
            <input class="lindo-input2"  type="text" id="Dni" name="Dni" oninput="javascript: if (this.value.length > 8) this.value = this.value.slice(0, 8);" value="' . $Dni . '" >
        </div>
        <div class="form-group">
            <h4>Nivel:</h4>
            <select id="Nivel" name="Nivel">' . $NivelOptions . '</select>
        </div>
        <div class="form-group">
            <h4>Curso:</h4>
            <select id="Curso" name="Curso">' . $CursoOptions . '</select>
        </div>
        <div class="form-group">
            <h4>División:</h4>
            <select id="División" name="División">' . $DivisionOptions . '</select>
        </div>
        <div class="form-group">
            <label for="ISBN">ISBN/Código interno:</label>
            <input type="text" class="lindo-input2"  id="ISBN" name="ISBN" oninput="javascript: if (this.value.length > 13) this.value = this.value.slice(0, 13);" value="' . $ISBN . '" >
        </div>
        <div class="form-group">
        <p><p>
            <label for="Fecha_retiro">Fecha de retiro:</label>
            <p><p>
            <input type="date" id="Fecha_retiro" name="Fecha_retiro" value="' . $Fecha_retiro . '" min="' . date('Y-m-d') . '" max="' . date('Y-m-d', strtotime('+1 month')) . '" >
        </div>
        <div class="form-group">
            <label for="Fecha_devolución">Fecha de devolución:</label>
            <p><p>
            <input type="date" id="Fecha_devolución" name="Fecha_devolución" value="' . $Fecha_devolución .'" min="' . date('Y-m-d') . '" max="' . date('Y-m-d', strtotime('+1 month')) . '" >
        </div>
        <button class="botón" type="submit" name="actualizar" value="actualizar">Modificar</button>
    </form>';
    
    return $html;
}

function usuarioModificar($usu)
{
    $nombre_completo = isset($usu['nombre_completo']) ? $usu['nombre_completo'] : '';
    $dni = isset($usu['dni']) ? $usu['dni'] : '';
    
    $Nivel = isset($usu['nivel']) ? $usu['nivel'] : '';
    $NivelOptions = "<option value='Secundario'>Secundario</option>";
    $NivelOptions .= "<option value='Primario'>Primario</option>";
    $NivelOptions .= "<option value='Inicial'>Inicial</option>";
    $NivelOptions .= "<option value='Docente'>Docente</option>";
    $NivelOptions .= "<option value='Personal Administrativo'>Personal Administrativo</option>";
    $NivelOptions = str_replace("value='$Nivel'", "value='$Nivel' selected", $NivelOptions);
    
    $División = isset($usu['division']) ? $usu['division'] : '';
    $DivisionOptions = "<option value='A'>A</option>";
    $DivisionOptions .= "<option value='B'>B</option>";
    $DivisionOptions = str_replace("value='$División'", "value='$División' selected", $DivisionOptions);
    
    $Curso = isset($usu['curso']) ? $usu['curso'] : '';
    $CursoOptions = "<option value='1ro'>1ro</option>";
    $CursoOptions .= "<option value='2do'>2do</option>";
    $CursoOptions .= "<option value='3ro'>3ro</option>";
    $CursoOptions .= "<option value='4to'>4to</option>";
    $CursoOptions .= "<option value='5to'>5to</option>";
    $CursoOptions .= "<option value='6to'>6to</option>";
    $CursoOptions = str_replace("value='$Curso'", "value='$Curso' selected", $CursoOptions);
    
    $email = isset($usu['email']) ? $usu['email'] : '';
    $usuario = isset($usu['usuario']) ? $usu['usuario'] : '';
    
    $html = '
    <form method="post" action="modificar_registro2.php">
        <div class="form-group">
            <label for="nombre_completo">Nombre completo:</label>
            
            <input class="lindo-input2" type="text" id="nombre_completo" name="nombre_completo" maxlength="30" value="' . $nombre_completo . '" >
        </div>
        <div class="form-group">
            <label for="dni">Dni:</label>
            <h1></h1>
            <input type="text" id="dni" class="lindo-input2" name="dni" oninput="javascript: if (this.value.length > 8) this.value = this.value.slice(0, 8);" value="' . $dni . '" >
        </div>
        <div class="form-group">
            <h4>Nivel:</h4>
            <select id="nivel" class="lindo-input2" name="nivel">' . $NivelOptions . '</select>
        </div>
        <div class="form-group">
            <h4>Curso:</h4>
            <select id="curso" class="lindo-input2" name="curso">' . $CursoOptions . '</select>
        </div>
        <div class="form-group"> 
            <h4>División:</h4>
            <select id="division" class="lindo-input2" name="division">' . $DivisionOptions . '</select>
        </div>
        <div class="form-group">
        
            <label for="email">Email: </label>
            <a class="opcional"> *opcional</a>
            <h1></h1>
            <input type="text" id="email" class="lindo-input2" name="email" maxlength="319" value="' . $email . '" >
        </div>
        <div class="form-group">
            <label for="Usuario">Usuario:</label>
            <h1></h1>
            <input type="text" id="usuario" class="lindo-input2" name="usuario" maxlength="30" value="' . $usuario . '">
        </div>
        <button class="botón" type="submit" name="modificar" value="modificar">Modificar</button>
    </form>';
    
    return $html;
}


function obtenerInformacionLibro($isbn) {
    // Reemplaza "TU_CLAVE_DE_API" con tu clave de API de Google Books
    $api_key = "AIzaSyBjOOlQqGA8vgmNTB927iEJkziHyg1_5V4";

    // Construir la URL de la API con el ISBN y la clave de API
    $api_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn&key=$api_key";

    // Realizar la solicitud a la API y obtener la respuesta
    $response = file_get_contents($api_url);

    // Decodificar la respuesta JSON en un array asociativo
    $data = json_decode($response, true);

    // Obtener los datos del libro
    $items = isset($data['items']) ? $data['items'] : array();
    
    if (empty($items)){
        $libro="";
    }
    else {

    // Obtener la información del primer libro en los resultados (asumimos que es el correcto)
    $volumeInfo = $items[0]['volumeInfo'];

    // Extraer los datos relevantes del libro
    $nombre = isset($volumeInfo['title']) ? $volumeInfo['title'] : "No disponible";
    $autores = isset($volumeInfo['authors']) ? $volumeInfo['authors'] : array();
    $autor = !empty($autores) ? implode(", ", $autores) : "No disponible";
    $editorial = isset($volumeInfo['publisher']) ? $volumeInfo['publisher'] : "Editorial desconocida";
    $ano_publicacion = isset($volumeInfo['publishedDate']) ? $volumeInfo['publishedDate'] : "Año de publicación desconocido";
    $descripcion_obra = isset($volumeInfo['description']) ? $volumeInfo['description'] : "Descripción no disponible";
    $categorias = isset($volumeInfo['categories']) ? $volumeInfo['categories'] : array();
    $categoria = !empty($categorias) ? implode(", ", $categorias) : "No disponible";
    $imageUrl = isset($volumeInfo['imageLinks']['thumbnail']) ? $volumeInfo['imageLinks']['thumbnail'] : "no_hay_portada.jpg";

    // Crear un arreglo asociativo con los datos del libro
    $libro = array(
        "Título" => $nombre,
        "Autor" => $autor,
        "Editorial" => $editorial,
        "ISBN" => $isbn,
        "Año de publicación" => $ano_publicacion,
        "Descripción" => $descripcion_obra,
        "Categorías" => $categoria,
        "Portada" => $imageUrl
    );
}
    return $libro; // Retornar el arreglo con la información del libro
}


// Definir la función para almacenar los datos en la base de datos
function almacenarDatosEnBD($titulo, $autor, $editorial, $isbn, $anodepublicacion, $descripcion, $categorias, $portada) {
    // Primero, realiza la conexión a la base de datos (reemplaza los valores con los de tu base de datos)
$host = "localhost";
$user = "root";
$password = "";
$database = "biblioteca_virtual";

    $conn = mysqli_connect($host, $user, $password, $database);

    // Verificar si la conexión fue exitosa
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Escapar los datos para evitar problemas de seguridad (usamos mysqli_real_escape_string)
    $titulo = mysqli_real_escape_string($conn, $titulo);
    $autor = mysqli_real_escape_string($conn, $autor);
    $editorial = mysqli_real_escape_string($conn, $editorial);
    $isbn = mysqli_real_escape_string($conn, $isbn);
    $anodepublicacion = mysqli_real_escape_string($conn, $anodepublicacion);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);
    $categorias = mysqli_real_escape_string($conn, $categorias);
    $portada = mysqli_real_escape_string($conn, $portada);

    // Verificar si el ISBN ya existe en la tabla "libros"
    $sql_check_isbn = "SELECT * FROM libros WHERE ISBN = '$isbn'";
    $result_check_isbn = mysqli_query($conn, $sql_check_isbn);

    if (mysqli_num_rows($result_check_isbn) > 0) {
        // Si el ISBN ya existe, incrementar el stock en uno
        $sql_increment_stock = "UPDATE libros SET Stock = Stock + 1, `Número de ejemplares disponibles` = `Número de ejemplares disponibles` + 1 WHERE ISBN = '$isbn'";
        if (mysqli_query($conn, $sql_increment_stock)) {
            mysqli_close($conn);
            // Retorna verdadero para indicar que se incrementó el stock
            return true;
        } else {
            echo "Error al incrementar el stock: " . mysqli_error($conn);
            mysqli_close($conn);
            // Retorna falso en caso de error
            return false;
        }
    } else {
        // Si el ISBN no existe, insertar el nuevo libro con stock 1
        $sql_insert_libro = "INSERT INTO libros (Título, Autor, Editorial, ISBN, `Año de publicación`, Descripción, Categorías, Portada, `Número de ejemplares disponibles`, Stock)
                            VALUES ('$titulo', '$autor', '$editorial', '$isbn', '$anodepublicacion', '$descripcion', '$categorias', '$portada', 1, 1)";

        if (mysqli_query($conn, $sql_insert_libro)) {
            mysqli_close($conn);
            // Retorna verdadero para indicar que se insertó el libro
            return true;
        } else {
            echo "Error al insertar el libro: " . mysqli_error($conn);
            mysqli_close($conn);
            // Retorna falso en caso de error
            return false;
        }
    }
}


}
?>