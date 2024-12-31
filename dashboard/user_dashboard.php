<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user_alojamiento'])) {
    $user_id = $_SESSION['user_id'];
    $alojamiento_id = $_POST['alojamiento_id'];
    $sql = "DELETE FROM user_alojamientos WHERE user_id = :user_id AND alojamiento_id = :alojamiento_id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(['user_id' => $user_id, 'alojamiento_id' => $alojamiento_id])) {
        $_SESSION['message'] = 'Alojamiento eliminado de tu cuenta exitosamente';
    } else {
        $_SESSION['message'] = 'Error al eliminar alojamiento de tu cuenta';
    }

    header("Location: user_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT a.* FROM alojamientos a
        JOIN user_alojamientos ua ON a.id = ua.alojamiento_id
        WHERE ua.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$alojamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Dashboard</title>
    <link rel="stylesheet" href="../assets/css/userDashboard.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">Inicio</a>
        <a href="../auth/logout.php">Cerrar sesión</a>
    </div>
    <div class="container">
        <h1>Mis Alojamientos</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message'] ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <?php foreach ($alojamientos as $alojamiento): ?>
            <div class="alojamiento">
                <img src="../uploads/<?= basename($alojamiento['image']) ?>" alt="Imagen">
                <div class="content">
                    <h2><?= $alojamiento['title'] ?></h2>
                    <p><?= $alojamiento['description'] ?></p>
                    <p><strong>Ubicación:</strong> <?= $alojamiento['location'] ?></p>
                    <form method="post" action="user_dashboard.php" style="display:inline;">
                        <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                        <input type="submit" name="delete_user_alojamiento" value="Eliminar" class="btn-delete">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
