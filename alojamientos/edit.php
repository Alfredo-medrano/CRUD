<?php
require '../db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    $_SESSION['message'] = 'No autorizado';
    header("Location: ../dashboard/admin_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $sql = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['username' => $username, 'email' => $email, 'role' => $role, 'id' => $user_id])) {
            $_SESSION['message'] = 'Usuario actualizado exitosamente';
        } else {
            $_SESSION['message'] = 'Error al actualizar usuario: ' . implode(":", $stmt->errorInfo());
        }
    } elseif (isset($_POST['update_alojamiento'])) {
        $alojamiento_id = $_POST['alojamiento_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $image = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $image = $uploadDir . basename($_FILES['image']['name']);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                $_SESSION['message'] = 'Error al subir la imagen';
                header("Location: ../dashboard/admin_dashboard.php");
                exit();
            }
        } else {
            $image = $_POST['current_image'];
        }

        $sql = "UPDATE alojamientos SET title = :title, description = :description, location = :location, image = :image WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['title' => $title, 'description' => $description, 'location' => $location, 'image' => $image, 'id' => $alojamiento_id])) {
            $_SESSION['message'] = 'Alojamiento actualizado exitosamente';
        } else {
            $_SESSION['message'] = 'Error al actualizar alojamiento: ' . implode(":", $stmt->errorInfo());
        }
    }
}

header("Location: ../dashboard/admin_dashboard.php");
exit();
?>