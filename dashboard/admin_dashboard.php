<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dasboardUser.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">Inicio</a>
        <a href="adminDashboardAlojamientos.php">Admin Dashboard Alojamiento</a>
        <a href="../auth/logout.php">Cerrar sesión</a>
    </div>

    <h1>Panel de Administración de Usuarios</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="container">
        <div class="left">
            <h3>Agregar Usuario</h3>
            <form method="post" action="../alojamientos/add.php">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" required placeholder="Nombre de usuario">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required placeholder="Correo electrónico">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required placeholder="Contraseña">
                <label for="role">Rol:</label>
                <select id="role" name="role">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <button type="submit" name="add_user">Agregar Usuario</button>
            </form>
        </div>

        <div class="right">
            <h3>Gestión de Usuarios</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= ucfirst($user['role']) ?></td>
                            <td>
                                <form method="post" action="adminDashboard.php" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" name="delete_user">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
