<?php
session_start();
include 'db.php';

// Verificar si el usuario está autenticado y tiene rol de Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: login.php');
    exit();
}

// Consulta para obtener los pedidos con opcion_pedido como 'reservar'
// Consulta para obtener los pedidos con opcion_pedido como 'reservar' y estado 'en reserva'
$result = $conn->query("
    SELECT p.*, u.nombre
    FROM Pedido p
    JOIN usuario u ON p.idusuario = u.idusuario
    WHERE p.opcion_pedido = 'reservar' AND p.estado = 'en reserva'
");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Pedidos Reservados</h1>
    </header>
    
    <div class="container">
        <h2>Todos los Pedidos Reservados</h2>
        <a href="operador.php" style="float: right; margin-bottom: 10px;">Regresar a Operador</a>
        <table border="1">
            <tr>
                <th>ID Pedido</th>
                <th>ID Usuario</th>
                <th>Nombre Usuario</th>
                <th>Total</th>
                <th>Método de Pago</th>
                <th>Opción de Pedido</th>
                <th>Ticket</th>
                <th>Fecha de Pedido</th>
            </tr>

            <?php while ($pedido = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $pedido['id_pedido'] ?></td>
                <td><?= $pedido['idusuario'] ?></td>
                <td><?= $pedido['nombre'] ?></td>
                <td>$<?= number_format($pedido['total'], 2) ?></td>
                <td><?= $pedido['metodo_pago'] ?></td>
                <td><?= $pedido['opcion_pedido'] ?></td>
                <td><?= $pedido['ticket'] ?></td>
                <td><?= $pedido['fecha_pedido'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>