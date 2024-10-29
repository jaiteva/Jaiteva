<?php
include 'db.php'; // Cambia 'database.php' a 'db.php' para la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $password = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
    $idrol = 2; // Rol 'Usuario'

    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO usuario (nombre, correo, contraseña, idrol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $email, $password, $idrol);
    
    if ($stmt->execute()) {
        header('Location: login.php'); // Redirigir a la página de login
    } else {
        echo "Error al registrar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Registrarse</h1>
    </header>
    <div class="container">
        <form method="POST" action="register.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>
</html>
