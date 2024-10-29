<?php
session_start();
include 'db.php';

// Verificar si el ticket está presente en la URL
if (!isset($_GET['ticket'])) {
    header('Location: menu.php'); // Redirigir si no hay ticket
    exit();
}

$ticket = $_GET['ticket'];

// Obtener información del pedido utilizando el ticket
$sql = "SELECT * FROM Pedido WHERE ticket = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error en la preparación de la consulta: " . $conn->error;
    exit();
}
$stmt->bind_param("s", $ticket);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) {
    echo "No se encontró el pedido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        /* Estilos para el botón de "Volver al Menú" */
        #back-menu {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #FB852E;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        #back-menu:hover {
            background-color: #DDB999;
        }

        /* Estilos para el enlace a "pedido_exito.php" */
        #pedido-exito {
            margin-top: 20px;
            display: inline-block;
            background-color: #FB852E;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        #pedido-exito:hover {
            background-color: #DDB999;
        }
    </style>
</head>
<body>
    <a id="back-menu" href="menu.php">⬅ Volver al Menú</a>
    <header>
        <h1>Ticket de Pedido</h1>
    </header>
    <div class="container">
        <h2>Información del Pedido</h2>
        <p><strong>ID Pedido:</strong> <?= htmlspecialchars($pedido['id_pedido']) ?></p>
        <p><strong>Total:</strong> $<?= number_format((float)$pedido['total'], 2, ',', '.') ?></p>
        <p><strong>Método de Pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?></p>
        <p><strong>Ticket:</strong> <?= htmlspecialchars($pedido['ticket']) ?></p>
        <p><strong>Estado:</strong> <?= htmlspecialchars($pedido['estado']) ?></p>

        <!-- Mostrar enlace a la página de éxito solo si el estado es "confirmado" -->
        <?php if ($pedido['estado'] === 'confirmado'): ?>
            <a id="pedido-exito" href="pedido_exito.php?ticket=<?= urlencode($ticket) ?>">Ver Estado de Pedido</a>
        <?php endif; ?>
        
    </div>
</body>
</html>
