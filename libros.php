<?php

include("cabecera.php");
session_start();


if (!isset($_SESSION['nombre'])) {

    header("Location: login.html");
    exit;
}


$conn = new mysqli($servidor, $userBD, $passwdBD, $nomBD);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


$sql = "SELECT id, nombre, autor, isbn, puntuacion, genero FROM libros";
$condiciones = [];
$filtro_activo = ''; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['puntuacion']) && $_POST['puntuacion'] !== '') {
    $puntuacion_filtro = (int)$_POST['puntuacion'];

    $condiciones[] = "puntuacion = " . $puntuacion_filtro;
    $filtro_activo = $puntuacion_filtro;
} 

if (!empty($condiciones)) {

    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca - Listado de Libros</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        .formulario-busqueda { margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .mensaje { padding: 10px; background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; border-radius: 5px; margin-bottom: 15px; }
        .acciones { margin-top: 20px; }
        .acciones a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .acciones a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Mostar libros</h1>
    <p>Bienvenido, **<?= htmlspecialchars($_SESSION['nombre']) ?>**</p>

    <div class="formulario-busqueda">
        <p>select * from libros</p>
        <form method="POST" action="libros.php">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>Puntuación</th>
                    <th>Género</th>

                    <th></th>
                </tr>
                <tr>
                    <td><input type="text" name="nombre_busqueda" disabled title="Deshabilitado"></td>
                    <td><input type="text" name="autor_busqueda" disabled title="Deshabilitado"></td>
                    <td><input type="text" name="isbn_busqueda" disabled title="Deshabilitado"></td>
                    <td>
                        <select name="puntuacion">
                            <option value="">Todas</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= $filtro_activo == $i ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </td>
                    <td><input type="text" name="genero_busqueda" disabled title="Deshabilitado"></td>
                    <td><input type="submit" value="Enviar"></td>
                </tr>
            </table>
        </form>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <table>

            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Puntuación</th>
                <th>Género</th>
            </tr>
            <?php while ($fila = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['id']) ?></td>
                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                    <td><?= htmlspecialchars($fila['autor']) ?></td>
                    <td><?= htmlspecialchars($fila['isbn']) ?></td>
                    <td><?= htmlspecialchars($fila['puntuacion']) ?></td>
                    <td><?= htmlspecialchars($fila['genero']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="mensaje">No se encontraron libros que coincidan con el filtro.</p>
    <?php endif; ?>

    <div class="acciones">
        <a href="FormLibros.html">Añadir Nuevo Libro</a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>

    <?php $conn->close(); ?>
</body>
</html>