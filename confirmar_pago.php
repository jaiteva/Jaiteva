<?php
session_start();
include 'db.php';

// Verificar si el usuario está autenticado y tiene rol de Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: login.php');
    exit();
}

// Confirmar el pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'])) {
    $id_pedido = $_POST['id_pedido'];

    // Actualizar el estado del pedido a "confirmado"
    $sql = "UPDATE Pedido SET estado = 'confirmado' WHERE id_pedido = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pedido);

    if ($stmt->execute()) {
        // Verificación del estado actualizado
        echo "Estado actualizado a confirmado.<br>";

        // Obtener el total del pedido y el id del usuario que hizo el pedido
        $sql_pedido = "SELECT total, idusuario FROM Pedido WHERE id_pedido = ?";
        $stmt_pedido = $conn->prepare($sql_pedido);
        $stmt_pedido->bind_param("i", $id_pedido);
        $stmt_pedido->execute();
        $result_pedido = $stmt_pedido->get_result();

        if ($result_pedido->num_rows > 0) {
            $pedido = $result_pedido->fetch_assoc();
            $total = $pedido['total'];
            $id_usuario = $pedido['idusuario'];

            echo "Total del pedido: $total, ID usuario: $id_usuario<br>";

            // Calcular los puntos ganados
            $puntos_ganados = floor($total / 100);
            echo "Puntos ganados calculados: $puntos_ganados<br>";

            // Verificar y actualizar los puntos del usuario
            $sql_actualizar_puntos = "UPDATE usuario SET puntos = puntos + ? WHERE idusuario = ?";
            $stmt_actualizar_puntos = $conn->prepare($sql_actualizar_puntos);
            $stmt_actualizar_puntos->bind_param("ii", $puntos_ganados, $id_usuario);

            if ($stmt_actualizar_puntos->execute()) {
                echo "Puntos acumulados correctamente en la base de datos.<br>";
            } else {
                echo "Error al actualizar los puntos: " . $conn->error . "<br>";
            }
        } else {
            echo "Error: No se encontró el pedido o el total es incorrecto.<br>";
        }

        // Redirigir a la página de éxito (puedes eliminar esto temporalmente para ver mensajes)
        // header("Location: pedido_exito.php");
        exit();
    } else {
        echo "Error al confirmar el pago: " . $conn->error . "<br>";
    }
}
?>
