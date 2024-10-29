<?php
include 'db.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_plato = $_POST['id_plato'];
    $comentario = $_POST['comentario'];
    $puntaje = $_POST['puntaje'];
    $id_usuario = $_SESSION['usuario']['id_usuario'];

    $sql = "INSERT INTO Reseñas (id_plato, id_usuario, comentario, puntaje, estado)
            VALUES ($id_plato, $id_usuario, '$comentario', $puntaje, 'Pendiente')";

    if ($conn->query($sql) === TRUE) {
        echo "Reseña enviada para aprobación.";
    } else {
        echo "Error al enviar reseña.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dejar Reseña</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h2>Deja tu reseña</h2>
        <form method="POST" action="">
            <label for="id_plato">Plato:</label>
            <select name="id_plato">
                <?php
                $sql = "SELECT * FROM Menu";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id_plato']}'>{$row['nombre_plato']}</option>";
                }
                ?>
            </select>
            <label for="puntaje">Puntaje:</label>
            <select name="puntaje">
                <option value="1">1 Estrella</option>
                <option value="2">2 Estrellas</option>
                <option value="3">3 Estrellas</option>
                <option value="4">4 Estrellas</option>
                <option value="5">5 Estrellas</option>
            </select>
            <label for="comentario">Comentario:</label>
            <textarea name="comentario" required></textarea>
            <button type="submit">Enviar Reseña</button>
        </form>
    </div>
</body>
</html>
