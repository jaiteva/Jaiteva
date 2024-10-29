<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F2EEE2;
            color: #30261A;
            margin: 0;
            padding: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #E5DFD1;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #DDB999;
        }
        th {
            background-color: #FB852E;
            color: #fff;
        }
        button {
            padding: 10px 15px;
            background-color: #FB852E;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #DDB999;
        }
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
    </style>
</head>
<body>
    <a id="back-menu" href="menu.php">⬅ Volver al Menú</a>
    <header>
        <h1>Carrito de Compras</h1>
    </header>
    <div class="container">
        <?php
        include 'db.php'; // Conexión a la base de datos

        // Verificar si el carrito está creado
        if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
            $total = 0;

            echo "<h1>Carrito de compras</h1>";
            echo "<table>";
            echo "<tr><th>Nombre del plato</th><th>Cantidad</th><th>Precio unitario</th><th>Total</th></tr>";

            // Recorrer los elementos del carrito
            foreach ($_SESSION['carrito'] as $item) {
                $nombre = isset($item['nombre']) ? $item['nombre'] : 'Nombre no disponible';
                $cantidad = isset($item['cantidad']) ? $item['cantidad'] : 0;
                $precio = isset($item['precio']) ? $item['precio'] : 0;
                $subtotal = $cantidad * $precio;

                // Mostrar los detalles del plato
                echo "<tr>";
                echo "<td>" . $nombre . "</td>";
                echo "<td>" . $cantidad . "</td>";
                echo "<td>$" . number_format($precio, 2) . "</td>";
                echo "<td>$" . number_format($subtotal, 2) . "</td>";
                echo "</tr>";

                $total += $subtotal;
            }

            echo "<tr><td colspan='3'>Total</td><td>$" . number_format($total, 2) . "</td></tr>";
            echo "</table>";

            // Opciones para vaciar el carrito
            echo "<form method='POST' action='vaciar_carrito.php'>";
            echo "<button type='submit'>Vaciar Carrito</button>";
            echo "</form>";

            // Formulario para proceder al pago con opciones
            echo "<form method='POST' action='pago.php' id='pedido-form'>";
            echo "<h2>Opciones de Pedido:</h2>";
            echo "<select name='opcion_pedido' required onchange='checkReservationOption(this.value)'>";
            echo "<option value='comer_acá'>Para Comer Acá</option>";
            echo "<option value='para_llevar'>Para Llevar</option>";
            echo "<option value='reservar'>Para Reservar</option>";
            echo "</select>";
            echo "<input type='hidden' name='total' value='$total'>"; // Asegúrate de pasar el total aquí
            echo "<button type='submit'>Proceder al Pago</button>";
            echo "</form>";

        } else {
            echo "<p>El carrito está vacío.</p>";
        }
        ?>
    </div>

    <script>
        function checkReservationOption(opcion) {
            const form = document.getElementById('pedido-form');
            if (opcion === 'reservar') {
                form.action = 'reserva.php'; // Cambia la acción del formulario a reserva.php
            } else {
                form.action = 'pago.php'; // Mantiene la acción del formulario a pago.php
            }
        }
    </script>
</body>
</html>
