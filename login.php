<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['correo'];
    $password = $_POST['contraseña'];

    // Cambia la consulta para usar la tabla 'usuario'
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE correo = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['contraseña'])) {
            $_SESSION['user_id'] = $user['idusuario'];
            $_SESSION['user_role'] = $user['idrol']; // Guarda el rol de usuario en la sesión
// Al iniciar sesión
$_SESSION['user_points'] = $user['puntos'] ?? 0; // Esto debe funcionar

            
            // Redirigir a operador.php si es Admin, si no, a menu.php
            if ($user['idrol'] == 1) {
                header('Location: operador.php');
            } else {
                header('Location: menu.php');
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Iniciar Sesión</h1>
    </header>
    <div class="container">
        <form method="POST" action="login.php">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="register.php">Registrarse</a></p>
    </div>
</body>
</html>
