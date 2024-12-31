<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Debes iniciar sesión o registrarte para agregar alojamientos a tu cuenta.';
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $alojamiento_id = $_POST['alojamiento_id'];

    $sql = "SELECT * FROM user_alojamientos WHERE user_id = :user_id AND alojamiento_id = :alojamiento_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'alojamiento_id' => $alojamiento_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $_SESSION['message'] = 'Este alojamiento ya está en tu cuenta.';
    } else {
        $sql = "SELECT title FROM alojamientos WHERE id = :alojamiento_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['alojamiento_id' => $alojamiento_id]);
        $alojamiento = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($alojamiento) {
            $title = $alojamiento['title'];

            $sql = "INSERT INTO user_alojamientos (user_id, alojamiento_id, title) VALUES (:user_id, :alojamiento_id, :title)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(['user_id' => $user_id, 'alojamiento_id' => $alojamiento_id, 'title' => $title])) {
                $_SESSION['message'] = 'Alojamiento agregado a tu cuenta exitosamente';
            } else {
                $_SESSION['message'] = 'Error al agregar alojamiento a tu cuenta';
            }
        } else {
            $_SESSION['message'] = 'Alojamiento no encontrado';
        }
    }
}

header("Location: user_dashboard.php");
exit();
?>