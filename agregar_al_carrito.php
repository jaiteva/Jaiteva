<?php
session_start();
include 'db.php'; // Conexión a la base de datos

// Verificar si se ha enviado la solicitud para agregar al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idmenu = isset($_POST['idmenu']) ? $_POST['idmenu'] : null;
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Obtener detalles del plato
    $sql = "SELECT nombre, precio FROM Menu WHERE idmenu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idmenu);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $nombre = $item['nombre'];
        $precio = $item['precio'];

        // Comprobar si el producto ya está en el carrito
        if (isset($_SESSION['carrito'][$idmenu])) {
            $_SESSION['carrito'][$idmenu]['cantidad'] += $cantidad; // Aumentar la cantidad
        } else {
            $_SESSION['carrito'][$idmenu] = [
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad,
            ]; // Agregar el producto
        }
    }

    // Redirigir de nuevo a la página del menú
    header('Location: menu.php');
    exit();
}
