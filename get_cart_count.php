<?php
session_start();

// Inicializar el contador
$count = 0;

// Verificar si el carrito existe en la sesión
if (isset($_SESSION['carrito'])) {
    $count = count($_SESSION['carrito']); // Contar los elementos en el carrito
}

// Devolver la cantidad como JSON
echo json_encode(['count' => $count]);
