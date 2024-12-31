<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Debes iniciar sesión para eliminar alojamientos de tu cuenta.';
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $alojamiento_id = $_POST['alojamiento_id'];
    $sql = "DELETE FROM user_alojamientos WHERE user_id = :user_id AND alojamiento_id = :alojamiento_id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(['user_id' => $user_id, 'alojamiento_id' => $alojamiento_id])) {
        $_SESSION['message'] = 'Alojamiento eliminado de tu cuenta exitosamente';
    } else {
        $_SESSION['message'] = 'Error al eliminar alojamiento de tu cuenta';
    }
}

header("Location: user_dashboard.php");
exit();
?>