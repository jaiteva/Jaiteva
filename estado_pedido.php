<?php
session_start();
include 'db.php';

// Verificar si el usuario está autenticado y tiene rol de Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: login.php');
    exit();
}

// Manejar el cambio de estado de los pedidos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_pedido'])) {
    $id_pedido = $_POST['id_pedido'];
    $accion = $_POST['accion'];

    if ($accion === 'reservar') {
        // Actualizar el estado del pedido a 'en reserva'
        $sql = "UPDATE Pedido SET estado = 'en reserva' WHERE id_pedido = ?";
    } elseif ($accion === 'cocinando') {
        $sql = "UPDATE Pedido SET estado = 'cocinando' WHERE id_pedido = ?";
    } elseif ($accion === 'entregado') {
        $sql = "UPDATE Pedido SET estado = 'entregado' WHERE id_pedido = ?";
    } elseif ($accion === 'cancelar') {
        $sql = "UPDATE Pedido SET estado = 'cancelado' WHERE id_pedido = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pedido);

    if ($stmt->execute()) {
        header('Location: estado_pedido.php');
        exit();
    } else {
        echo "Error al actualizar el estado del pedido: " . $stmt->error;
    }
}

/// Consulta para obtener solo los pedidos en estado 'confirmado' o 'cocinando'
$result = $conn->query("
SELECT p.*, u.nombre
FROM Pedido p
JOIN usuario u ON p.idusuario = u.idusuario
WHERE p.estado IN ('confirmado', 'cocinando')
");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Estado del Pedido - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Control de Estado del Pedido</h1>
    </header>
    
    <div class="container">
        <h2>Todos los Pedidos</h2>
        <a href="operador.php" style="float: right; margin-bottom: 10px;">Regresar a Operador</a>
        <table border="1">
            <tr>
                <th>ID Pedido</th>
                <th>ID Usuario</th>
                <th>Nombre Usuario</th>
                <th>Total</th>
                <th>Método de Pago</th>
                <th>Ticket</th>
                <th>Fecha de Pedido</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php while ($pedido = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $pedido['id_pedido'] ?></td>
                <td><?= $pedido['idusuario'] ?></td>
                <td><?= $pedido['nombre'] ?></td>
                <td>$<?= number_format($pedido['total'], 2) ?></td>
                <td><?= $pedido['metodo_pago'] ?></td>
                <td><?= $pedido['ticket'] ?></td>
                <td><?= $pedido['fecha_pedido'] ?></td>
                <td><?= $pedido['estado'] ?></td>
                <td>
                    <form action="estado_pedido.php" method="POST">
                        <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
                        <button type="submit" name="accion" value="cocinando">Cocinando</button>
                        <button type="submit" name="accion" value="entregado">Entregado</button>
                        <button type="submit" name="accion" value="cancelar">Cancelar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
