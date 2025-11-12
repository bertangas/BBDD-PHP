<?php

include("cabecera.php"); 

session_start();


$conn = new mysqli($servidor, $userBD, $passwdBD, $nomBD);

if ($conn->connect_error) {
    die("Error de conexión a la BBDD: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    echo "<h3>[DEBUG] Contenido de \$_POST:</h3>";
    print_r($_POST);
    echo "<hr>";

    $isbn       = trim($_POST['isbn']);
    $nombre     = trim($_POST['nombre']);
    $autor      = trim($_POST['autor']);
    
    $puntuacion = isset($_POST['puntuacion']) && $_POST['puntuacion'] !== '' ? (int)trim($_POST['puntuacion']) : null; 
    $genero     = isset($_POST['genero']) ? trim($_POST['genero']) : null;
    
} else {

    header("Location: FormLibros.html");
    exit;
}

$isbn_sanitized = $conn->real_escape_string($isbn);

$sql_check = sprintf("SELECT isbn FROM libros WHERE isbn = '%s'", $isbn_sanitized);

echo "<h3>[DEBUG] Consulta de Verificación:</h3>";
echo $sql_check . "<hr>";

$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    
    echo "<h3> ❌ Error: Ya existe un libro con el ISBN $isbn. No se puede añadir.</h3>";
    
} else {

    $nombre_sanitized = $conn->real_escape_string($nombre);
    $autor_sanitized  = $conn->real_escape_string($autor);
    $genero_sanitized = $conn->real_escape_string($genero);
    
    $puntuacion_value = is_null($puntuacion) ? 'NULL' : $puntuacion;

    $sql_insert = sprintf("INSERT INTO libros (isbn, nombre, autor, puntuacion, genero) 
                             VALUES ('%s', '%s', '%s', %s, '%s')",
                             $isbn_sanitized, 
                             $nombre_sanitized, 
                             $autor_sanitized, 
                             $puntuacion_value, 
                             $genero_sanitized
    );
    
    echo "<h3>[DEBUG] Consulta de Inserción:</h3>";
    echo $sql_insert . "<hr>";
    
    if ($conn->query($sql_insert) === TRUE) {
        echo "<h3> ✅ Éxito: El libro '$nombre' ha sido añadido correctamente.</h3>";
    } else {
        echo "<h3> ❌ Error al crear el libro: " . $conn->error . "</h3>";
    }
}

$conn->close();

?>