<?php
session_start();
require 'config.php';

if (isset($_POST['login'])) {
    // Check if email and password are set and not empty
    if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and execute the query for the unified view
        $sql = "SELECT * FROM users_login_view WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['hashed_password'])) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_role'] = $row['role'];

                // Redirect based on the user's role
                if ($row['role'] == 'superadmin') {
                    $sql = "SELECT id FROM superadmin WHERE email=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $_SESSION['user_id'] = $row['id'];
                    header("Location: register_admin.php");
                    exit();
                } elseif ($row['role'] == 'admin') {
                    $sql = "SELECT id, firstname, lastname, unique_id FROM admin WHERE email=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['firstname'] = $row['firstname'];
                    $_SESSION['lastname'] = $row['lastname'];               
                    header("Location: admin_dashboard.php");
                    exit();
                } elseif ($row['role'] == 'employee') {
                    $sql = "SELECT id, firstname, lastname, unique_id FROM employees WHERE email=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['firstname'] = $row['firstname'];
                    $_SESSION['lastname'] = $row['lastname'];
                    header("Location: employee_dashboard.php");
                    exit();
                }
            }
        }

        // If user not found or password incorrect, redirect to login page
        echo "<script>alert('Invalid account information.'); window.location='login.html';</script>";
        exit();
    } else {
        // If email or password is empty, redirect to login page
        header("Location: login.html?error=empty");
        exit();
    }
} else {
    // If login form is not submitted, redirect to login page
    header("Location: login.html");
    exit();
}

$conn->close();
?>
