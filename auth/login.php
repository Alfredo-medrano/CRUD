<?php
require '../db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['role'] == 'admin') {
            $hashed_password = hash('sha256', $password);
            if ($hashed_password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header("Location: ../dashboard/admin_dashboard.php");
                exit();
            } else {
                $error = "Correo electrónico o contraseña incorrectos";
            }
        } else {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header("Location: ../index.php");
                exit();
            } else {
                $error = "Correo electrónico o contraseña incorrectos";
            }
        }
    } else {
        $error = "Correo electrónico o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Iniciar sesión</h1>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Iniciar sesión">
        </form>
        <p>Los administradores pueden iniciar sesión con sus credenciales.</p>
    </div>
</body>
</html>