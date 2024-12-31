<?php
require '../db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    $_SESSION['message'] = 'No autorizado';
    header("Location: ../dashboard/admin_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];

        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['id' => $user_id])) {
            $_SESSION['message'] = 'Usuario eliminado exitosamente';
        } else {
            $_SESSION['message'] = 'Error al eliminar usuario: ' . implode(":", $stmt->errorInfo());
        }
    } elseif (isset($_POST['delete_alojamiento'])) {
        $alojamiento_id = $_POST['alojamiento_id'];

        $sql = "DELETE FROM alojamientos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['id' => $alojamiento_id])) {
            $_SESSION['message'] = 'Alojamiento eliminado exitosamente';
        } else {
            $_SESSION['message'] = 'Error al eliminar alojamiento: ' . implode(":", $stmt->errorInfo());
        }
    }
}

header("Location: ../dashboard/admin_dashboard.php");
exit();
?>