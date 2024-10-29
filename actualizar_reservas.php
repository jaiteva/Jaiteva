<?php
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

echo "Estado de reservas actualizado correctamente.";
?>
