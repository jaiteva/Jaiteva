<?php
// Configuración de la conexión a la base de datos
$servername = "sql312.infinityfree.com"; // Nombre del host de MySQL
$username = "if0_37617560"; // Tu usuario de MySQL
$password = "EwGkNhHxKZLRX"; // Tu contraseña de MySQL
$dbname = "if0_37617560_jaiteva_final"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
