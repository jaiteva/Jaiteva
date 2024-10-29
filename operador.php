<?php
session_start();
include 'db.php';

// Actualizar el estado de reservas vencidas a "pendiente"
$sql_update_reservas = "
    UPDATE Pedido p
    JOIN Reserva r ON p.id_pedido = r.id_pedido
    SET p.estado = 'pendiente'
    WHERE p.estado = 'en reserva' 
    AND CONCAT(r.fecha_reserva, ' ', r.hora_reserva) < NOW();
";
$conn->query($sql_update_reservas);

// Verificar si el usuario está autenticado y tiene rol de Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: login.php');
    exit();
}

// Manejar la confirmación de pago y el rechazo de pedidos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_pedido'])) {
    $id_pedido = $_POST['id_pedido'];
    $accion = $_POST['accion'];

    if ($accion === 'confirmar') {
        // Actualizar el estado del pedido a 'confirmado'
        $sql = "UPDATE Pedido SET estado = 'confirmado' WHERE id_pedido = ?";
    } elseif ($accion === 'rechazar') {
        // Actualizar el estado del pedido a 'rechazado'
        $sql = "UPDATE Pedido SET estado = 'rechazado' WHERE id_pedido = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pedido);

    if ($stmt->execute()) {
        // Redirigir automáticamente a la página de operador
        header('Location: operador.php');
        exit();
    } else {
        echo "Error al actualizar el estado del pedido: " . $stmt->error;
    }
}

// Consulta para obtener los pedidos, excluyendo los que tienen opcion_pedido como 'reservar'
// Consulta para obtener los pedidos pendientes, incluyendo los que cambiaron de "en reserva" a "pendiente"
$result = $conn->query("
    SELECT p.*, u.nombre
    FROM Pedido p
    JOIN usuario u ON p.idusuario = u.idusuario
    WHERE p.estado = 'pendiente'
");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Operador - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .button {
            display: inline-block;
            padding: 10px 15px;
            margin-right: 10px; /* Separación entre botones */
            background-color: #FB852E; /* Color naranja */
            color: #FFFFFF; /* Texto blanco */
            border: none;
            border-radius: 5px;
            text-decoration: none; /* Quitar subrayado */
            text-align: center;
        }

        .button:hover {
            background-color: #DDAA66; /* Color más claro al pasar el mouse */
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel de Operador</h1>
    </header>
    
    <div class="container">
        <h2>Pedidos Pendientes</h2>
        <a href="estado_pedido.php" class="button">Controlar Estado del Pedido</a>
        <a href="reserva_pedido.php" class="button">Ver Reservas</a> <!-- Nuevo enlace -->
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
                <th>Acciones</th>
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
                <td>
                    <form action="operador.php" method="POST">
                        <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
                        <button type="submit" name="accion" value="confirmar">Confirmar Pago</button>
                        <button type="submit" name="accion" value="rechazar">Rechazar Pedido</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
