<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .signup-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .input-group {
            margin-bottom: 15px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 3px;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<div class="signup-container">
    <h2>Create Admin</h2>

    <?php
    // Include the config file
    require 'config.php';
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please log in first'); window.location='login.html';</script>";
        exit();
    }

    // Check if user is an admin
    if ($_SESSION['user_role'] !== 'superadmin') {
        session_unset();
        session_destroy();
        echo "<script>alert('You are not a superadmin'); window.location='login.html';</script>";
        exit();
    }

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data and sanitize it
        $firstname = $conn->real_escape_string($_POST['lastname']);
        $lastname = $conn->real_escape_string($_POST['firstname']);
        $middlename = $conn->real_escape_string($_POST['middlename']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz0123456789', 8)), 0, 8);
        $role = $conn->real_escape_string('admin');
        $unique_id = rand(time(), 100000000);

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an insert statement
        $sql = $conn->prepare("INSERT INTO admin (lastname, firstname, middlename, email, password, role, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("sssssss", $lastname, $firstname, $middlename, $email, $hashed_password, $role, $unique_id);

        // Execute the prepared statement
        if ($sql->execute()) {
            echo "<p style='color: green;'>Admin created successfully</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $sql->error . "</p>";
        }

        // Close statement and connection
        $sql->close();
        $conn->close();
    }
    ?>

    <form action="" method="post" id="signupForm">
        <div class="input-group">
            <label for="name">Last Name:</label>
            <input type="text" id="name" name="lastname" required>
            <label for="name">First Name:</label>
            <input type="text" id="name" name="firstname" required>
            <label for="name">Middle Name:</label>
            <input type="text" id="name" name="middlename">
        </div>
        <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
