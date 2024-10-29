<?php
session_start();
include 'db.php';

// Busca el usuario "cliente" con rol "invitado"
$sql = "SELECT * FROM usuario WHERE nombre = 'cliente' AND idrol = (SELECT idrol FROM rol WHERE nombrerol = 'invitado')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Configura la sesión para el usuario "cliente"
    $_SESSION['user_id'] = $user['idusuario'];
    $_SESSION['user_role'] = $user['idrol'];
    $_SESSION['user_name'] = $user['nombre'];
    
    // Redirige al menú o página principal del sistema
    header("Location: menu.php");
    exit();
} else {
    echo "Error: El usuario invitado no existe.";
}
?>
