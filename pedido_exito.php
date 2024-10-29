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
$stmt->bind_param("s", $ticket);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) {
    echo "No se encontró el pedido.";
    exit();
}

// Verificar si el usuario está logueado
if (isset($_SESSION['user_id']) && $pedido['estado'] === 'confirmado') {
    $user_id = $_SESSION['user_id'];
    $total = (float)$pedido['total'];

    // Calcular puntos acumulados (1 punto por cada $100)
    $puntos_acumulados = floor($total / 100);

    // Actualizar los puntos en la base de datos del usuario
    $update_sql = "UPDATE usuario SET puntos = puntos + ? WHERE idusuario = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $puntos_acumulados, $user_id);
    $update_stmt->execute();

    // Actualizar los puntos en la sesión
    $_SESSION['user_points'] += $puntos_acumulados;

    // Mensaje de confirmación de puntos acumulados
    $mensaje_puntos = "Has acumulado $puntos_acumulados puntos con este pedido.";
} else {
    $mensaje_puntos = "No se han acumulado puntos en este pedido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Exitoso - Jaiteva</title>
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

        /* Estilos para la línea de tiempo */
        .timeline {
            margin: 20px 0;
            padding: 10px;
            border-left: 4px solid #FB852E;
        }

        .timeline-item {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #FB852E;
        }
    </style>
</head>
<body>
    <a id="back-menu" href="menu.php">⬅ Volver al Menú</a>
    <header>
        <h1>¡Pedido Realizado con Éxito!</h1>
    </header>
    <div class="container">
        <p><strong>ID Pedido:</strong> <?= $pedido['id_pedido'] ?></p>
        <p><strong>Total:</strong> $<?= number_format($pedido['total'], 2) ?></p>
        <p><strong>Método de Pago:</strong> <?= $pedido['metodo_pago'] ?></p>
        <p><strong>Ticket:</strong> <?= $pedido['ticket'] ?></p>
        
        <?php if ($pedido['estado'] === 'cancelado'): ?>
            <p style="color: red;"><strong>Su pedido ha sido cancelado.</strong></p>
        <?php else: ?>
            <div class="timeline">
                <div class="timeline-item">
                    <strong>Confirmado</strong>
                </div>
                <?php if ($pedido['estado'] === 'cocinando' || $pedido['estado'] === 'entregado'): ?>
                    <div class="timeline-item">
                        <strong>Cocinando</strong>
                    </div>
                <?php endif; ?>
                <?php if ($pedido['estado'] === 'entregado'): ?>
                    <div class="timeline-item">
                        <strong>Entregado</strong>
                    </div>
                <?php endif; ?>
            </div>
            <p><?= $mensaje_puntos ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
