<?php
require '../db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    $_SESSION['message'] = 'No autorizado';
    header("Location: ../dashboard/admin_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_user'])) {
       
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $password, 'role' => $role])) {
            $_SESSION['message'] = 'Usuario agregado exitosamente';
        } else {
            $_SESSION['message'] = 'Error al agregar usuario: ' . implode(":", $stmt->errorInfo());
        }
      
        header("Location: ../dashboard/admin_dashboard.php");
    } elseif (isset($_POST['add_alojamiento'])) {
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
                header("Location: ../dashboard/adminDashboardAlojamientos.php");
                exit();
            }
        } else {
            $_SESSION['message'] = 'Error en la carga de la imagen: ' . $_FILES['image']['error'];
            header("Location: ../dashboard/adminDashboardAlojamientos.php");
            exit();
        }

        $sql = "INSERT INTO alojamientos (title, description, location, image) VALUES (:title, :description, :location, :image)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['title' => $title, 'description' => $description, 'location' => $location, 'image' => $image])) {
            $_SESSION['message'] = 'Alojamiento agregado exitosamente';
        } else {
            $_SESSION['message'] = 'Error al agregar alojamiento: ' . implode(":", $stmt->errorInfo());
        }
      
        header("Location: ../dashboard/adminDashboardAlojamientos.php");
    }
}
exit();
?>
