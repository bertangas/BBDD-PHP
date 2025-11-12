<?php

include("cabecera.php"); 

session_start();

$conn = new mysqli($servidor, $userBD, $passwdBD, $nomBD);
if ($conn->connect_error) {

    die("Error de conexión: " . $conn->connect_error);
}

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$clave = isset($_POST['clave']) ? $_POST['clave'] : '';

$clave_md5 = md5($clave);

$nombre_sanitized = $conn->real_escape_string($nombre);

$sql = sprintf("SELECT id, nombre, tipo FROM usuarios WHERE nombre='%s' AND clave='%s'", 
    $nombre_sanitized, 
    $clave_md5
);

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['tipo'] = $usuario['tipo'];
    
    header("Location: crealibro.php");
    exit;

} else {
    
    header("Location: login.html?error=1");
    exit;
}

$conn->close();
?>