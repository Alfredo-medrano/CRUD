<?php
require '../db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $error = "El correo electrónico ya está registrado";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $password])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role'] = 'user';
            header("Location: ../index.php");
            exit();
        } else {
            $error = "Error al registrar el usuario";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../assets/css/registerForm.css">
</head>
<body>
    <div class="register-container">
        <h1>Registrarse</h1>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Registrarse">
        </form>
    </div>
</body>
</html>