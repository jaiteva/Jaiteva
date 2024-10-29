<?php
session_start();

// Vaciar el carrito
unset($_SESSION['carrito']);

// Redirigir al carrito
header('Location: carrito.php');
exit;
?>
