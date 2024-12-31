<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$alojamientos = $pdo->query("SELECT * FROM alojamientos")->fetchAll(PDO::FETCH_ASSOC);

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Alojamientos</title>
    <link rel="stylesheet" href="../assets/css/alojamientosDash.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">Inicio</a>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="../auth/logout.php">Cerrar sesión</a>
    </div>

    <h1>Alojamientos</h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="container">
        <div class="left">
            <h2>Agregar Alojamiento</h2>
            <form method="post" action="../alojamientos/add.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Título:</label>
                    <input type="text" id="title" name="title" required placeholder="Título"><br>
                </div>
                <div class="form-group">
                    <label for="description">Descripción:</label>
                    <input type="text" id="description" name="description" required placeholder="Descripción"><br>
                </div>
                <div class="form-group">
                    <label for="location">Ubicación:</label>
                    <input type="text" id="location" name="location" required placeholder="Ubicación"><br>
                </div>
                <div class="form-group">
                    <label for="image">Imagen:</label>
                    <input type="file" id="image" name="image" required><br>
                </div>
                <input type="submit" name="add_alojamiento" value="Agregar Alojamiento">
            </form>
        </div>
        <div class="right">
            <h2>Gestión de Alojamientos</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Ubicación</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($alojamientos as $alojamiento): ?>
                <tr>
                    <td><?= $alojamiento['id'] ?></td>
                    <td><?= $alojamiento['title'] ?></td>
                    <td><?= $alojamiento['description'] ?></td>
                    <td><?= $alojamiento['location'] ?></td>
                    <td><img src="../uploads/<?= basename($alojamiento['image']) ?>" alt="Imagen" width="50"></td>
                    <td>
                        <form method="post" action="../alojamientos/delete.php" style="display:inline;">
                            <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                            <input type="submit" name="delete_alojamiento" value="Eliminar">
                        </form>
                        <form method="post" action="../alojamientos/edit.php" style="display:inline;">
                            <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                            <input type="hidden" name="current_image" value="<?= $alojamiento['image'] ?>">
                            <input type="submit" name="edit_alojamiento" value="Editar">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
