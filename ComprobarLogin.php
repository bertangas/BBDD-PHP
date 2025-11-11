<?php
include("cabecera.php");


$conn = new mysqli($servidor, $userBD, $passwdBD, $nomBD);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$clave = md5($_POST['clave']);

$sql = sprintf("SELECT * FROM usuarios WHERE nombre='%s' AND clave='%s'", $nombre, $clave);
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Login correcto. Bienvenido, $nombre</h3>";
} else {
    echo "<h3>Usuario o clave incorrectos</h3>";
}

$conn->close();
?>
