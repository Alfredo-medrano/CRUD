<?php
require 'db.php';
session_start();

$alojamientos = $pdo->query("SELECT * FROM alojamientos")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_user_alojamiento'])) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = 'Debes iniciar sesión para agregar alojamientos a tu cuenta.';
            header("Location: index.php");
            exit();
        }
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

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Inicio</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="dashboard/admin_dashboard.php">Dashboard</a>
            <?php else: ?>
                <a href="dashboard/user_dashboard.php">Dashboard</a>
            <?php endif; ?>
            <a href="auth/logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="auth/login.php">Iniciar sesión</a>
            <a href="auth/register.php">Registrarse</a>
        <?php endif; ?>
    </div>
    <h1>Alojamientos</h1>
    <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message'] ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    <div class="container">

        <?php foreach ($alojamientos as $alojamiento): ?>
            <div class="alojamiento">
                <h2><?= $alojamiento['title'] ?></h2>
                <p><?= $alojamiento['description'] ?></p>
                <p>Ubicación: <?= $alojamiento['location'] ?></p>
                <img src="uploads/<?= basename($alojamiento['image']) ?>" alt="Imagen" class="alojamiento-image">

                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] != 'admin'): ?>
                    <form method="post" action="index.php" class="form-inline">
                        <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                        <input type="submit" name="add_user_alojamiento" value="Agregar a cuenta" class="button">
                    </form>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'): ?>
                    <form method="post" action="index.php" class="form-inline">
                        <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                        <input type="submit" name="delete_alojamiento" value="Eliminar" class="button delete">
                    </form>
                    <form method="post" action="index.php" enctype="multipart/form-data" class="form-inline">
                        <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                        <input type="hidden" name="current_image" value="<?= $alojamiento['image'] ?>">
                        <label for="title_<?= $alojamiento['id'] ?>">Título:</label>
                        <input type="text" id="title_<?= $alojamiento['id'] ?>" name="title"
                            value="<?= $alojamiento['title'] ?>" required placeholder="Título">
                        <label for="description_<?= $alojamiento['id'] ?>">Descripción:</label>
                        <input type="text" id="description_<?= $alojamiento['id'] ?>" name="description"
                            value="<?= $alojamiento['description'] ?>" required placeholder="Descripción">
                        <label for="location_<?= $alojamiento['id'] ?>">Ubicación:</label>
                        <input type="text" id="location_<?= $alojamiento['id'] ?>" name="location"
                            value="<?= $alojamiento['location'] ?>" required placeholder="Ubicación">
                        <label for="image_<?= $alojamiento['id'] ?>">Imagen:</label>
                        <input type="file" id="image_<?= $alojamiento['id'] ?>" name="image">
                        <input type="submit" name="edit_alojamiento" value="Actualizar" class="button update">
                    </form>

                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>