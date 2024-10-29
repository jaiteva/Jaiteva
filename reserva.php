<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar - Jaiteva</title>
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
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #E5DFD1;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Espacio entre los campos */
        }
        label {
            font-weight: bold;
        }
        input[type="date"],
        input[type="time"],
        input[type="number"],
        button {
            padding: 10px;
            border: 1px solid #DDB999;
            border-radius: 5px;
        }
        button {
            background-color: #FB852E;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #DDB999;
        }
    </style>
</head>
<body>
    <header>
        <h1>Reservar Fecha y Hora</h1>
    </header>
    <div class="container">
        <form method="POST" action="pago.php">
            <label for="fecha_reserva">Fecha:</label>
            <input type="date" name="fecha_reserva" id="fecha_reserva" min="<?= date('Y-m-d', strtotime('-1 day')) ?>" max="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
            
            <label for="hora_reserva">Hora:</label>
            <input type="time" name="hora_reserva" id="hora_reserva" required>
            
            <label for="num_personas">Número de Personas (Máx 15):</label>
            <input type="number" name="num_personas" id="num_personas" min="1" max="15" required>
            
            <input type="hidden" name="total" value="<?= htmlspecialchars($_POST['total']) ?>">
            <input type="hidden" name="opcion_pedido" value="<?= htmlspecialchars($_POST['opcion_pedido']) ?>">
            
            <button type="submit">Continuar</button>
        </form>
    </div>
</body>
</html>
