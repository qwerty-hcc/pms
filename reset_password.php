<?php
// Start session and check if user is logged in
session_start();
require 'config.php';

// Redirect to login page if session doesn't contain the necessary data
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Check if the form was submitted
if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];

    // Check if the new password and confirm password match
    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the correct table based on the role
        if ($role == 'superadmin') {
            $sql = "UPDATE superadmin SET password=? WHERE email=?";
        } elseif ($role == 'admin') {
            $sql = "UPDATE admin SET password=? WHERE email=?";
        } elseif ($role == 'employee') {
            $sql = "UPDATE employees SET password=? WHERE email=?";
        }

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            // Password successfully changed
            echo "<script>alert('Password has been reset successfully.'); window.location='login.php';</script>";

            // Clear session data after password reset
            session_unset();
            session_destroy();
        } else {
            // If something went wrong during the update
            echo "<script>alert('An error occurred. Please try again.'); window.location='reset_password.php';</script>";
        }
    } else {
        // If passwords do not match
        echo "<script>alert('Passwords do not match. Please try again.'); window.location='reset_password.php';</script>";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        @import "bourbon";

body {
	background: #eee !important;	
}

.wrapper {	
	margin-top: 80px;
  margin-bottom: 80px;
}

.form-signin {
  max-width: 380px;
  padding: 15px 35px 45px;
  margin: 0 auto;
  background-color: #fff;
  border: 1px solid rgba(0,0,0,0.1);  

  .form-signin-heading,
	.checkbox {
	  margin-bottom: 30px;
      margin-left: 115px;
	}

	.checkbox {
	  font-weight: normal;
	}

	.form-control {
	  position: relative;
	  font-size: 16px;
	  height: auto;
	  padding: 10px;
		@include box-sizing(border-box);

		&:focus {
		  z-index: 2;
		}
	}

	input[type="password"] {
	  margin-bottom: 20px;
	}
}

    </style>
  </head>
  <body>
    <div class="wrapper">
        <form class="form-signin" action="" method="POST">
            <h2>Reset Your Password</h2>
            <div class="form-group">
                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3" name="reset_password">Reset Password</button>
            <br>
            <a href="forgot_pass.html" style="text-decoration: none;">Back to Forgot Password</a>
        </form>
    </div>
  </body>
</html>
