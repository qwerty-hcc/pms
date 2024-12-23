<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first'); window.location='login.html';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project Management System</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
  <style>
    .form-signin {
  max-width: 380px;
  padding: 15px 35px 45px;
  margin: 0 auto;
  background-color: #fff;
  border: 1px solid rgba(0,0,0,0.1);  

  .form-signin-heading {
    text-align: center;
	  margin-bottom: 30px;
	}

	.form-control {
	  position: relative;
	  font-size: 16px;
	  height: auto;
	  padding: 10px;
    margin-bottom: 30px;
		@include box-sizing(border-box);

		&:focus {
		  z-index: 2;
		}
	}

	input[type="password"] {
	  margin-bottom: 20px;
	  border-left-radius: 0;
	  border-right-radius: 0;
	}
}
  </style>
</head>
<?php 
$role = $_SESSION['user_role'];
switch ($role){
  case "admin":
    include "admin_bars.html";
    break;
  
  case "employee":
    include "employee_bars.html";
    break;

  default:
    echo "<script>alert('Not an HCC member'); window.location='../login.html';</script>";
}
?>
<body>
<div class="content">
        <form class="form-signin" method="POST">
          <h2 class="form-signin-heading">Change Password</h2>
          <input type="password" class="form-control" name="current_password" placeholder="Current Password" required/>
          <input type="password" class="form-control" name="new_password" placeholder="New Password" required/>
          <input type="password" class="form-control" name="confirm_password" placeholder="Confirm New Password" required/>      
          <button class="btn btn-lg btn-primary btn-block" type="submit" name="change_pass">Change Password</button>
          <br>
          <br>
          <?php
            $role = $_SESSION['user_role']; 

            $link = ($role === "admin") ? "admin_settings.php" : "employee_settings.php";
            ?>
            <a href="<?php echo $link; ?>" class="btn btn-secondary">Back</a>  
                    </form>
</body>
<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first'); window.location='login.html';</script>";
    exit();
}

if (isset($_POST['change_pass'])) {

    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required.'); window.location='change_password.php';</script>";
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo "<script>alert('New passwords do not match.'); window.location='change_password.php';</script>";
        exit();
    }

    if ($role === "admin") {
        $table = "admin";
    } elseif ($role === "employee") {
        $table = "employees";
    } else {
        echo "<script>alert('Invalid user role.'); window.location='login.html';</script>";
        exit();
    }

    // Fetch the current hashed password from the database
    $sql = "SELECT password FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password_db);
        $stmt->fetch();

        // Verify the current password
        if (!password_verify($current_password, $hashed_password_db)) {
            echo "<script>alert('Current password is incorrect.'); window.location='change_password.php';</script>";
            exit();
        }

        // Hash the new password
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the appropriate table
        $update_sql = "UPDATE $table SET password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_hashed_password, $user_id);

        if ($update_stmt->execute()) {
            echo "<script>alert('Password changed successfully! Please log in again.'); window.location='logout.php';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.'); window.location='change_password.php';</script>";
        }

        $update_stmt->close();
    } else {
        echo "<script>alert('User not found.'); window.location='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>