<?php
require 'config.php';
session_start();

// Check if employee ID is passed (for editing an existing employee)
if (isset($_GET['id'])) {
  $id = $conn->real_escape_string($_GET['id']);

  // Fetch employee details using the primary key (id)
  $result = $conn->query("SELECT * FROM employees WHERE id = '$id'");

  if ($result->num_rows == 1) {
    $employee = $result->fetch_assoc();
  } else {
    echo "<script>alert('Employee not found'); window.location='admin_manage_emp.php';</script>";
    exit;
  }
} else {
  echo "<script>alert('Invalid request'); window.location='admin_manage_emp.php';</script>";
  exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve and sanitize form data
  $last_name = $conn->real_escape_string($_POST['lastname']);
  $first_name = $conn->real_escape_string($_POST['firstname']);
  $middle_name = $conn->real_escape_string($_POST['middlename']);
  $suffix = $conn->real_escape_string($_POST['suffix']);
  $employeeID = $conn->real_escape_string($_POST['employeeID']);
  $employee_name = $first_name . ' ' . $middle_name . ' ' . $last_name . ' ' . $suffix;
  $department = $conn->real_escape_string($_POST['department']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $email = $conn->real_escape_string($_POST['email']);

  // Check if the email already exists (excluding current employee)
  $check_email_query = "SELECT * FROM (SELECT email FROM employees WHERE id != '$id' UNION ALL SELECT email FROM users WHERE employee_id != (SELECT id FROM employees WHERE id = '$id')) AS combined_emails WHERE email = '$email'";
  $email_result = $conn->query($check_email_query);

  if ($email_result->num_rows > 0) {
    echo "<script>alert('Email already exists');</script>";
    exit;
  } else {
    // Update employee information including employeeID using the primary key (id)
    $sql = $conn->prepare("UPDATE employees SET lastname = ?, firstname = ?, middlename = ?, suffix = ?, employee_name = ?, employeeID = ?, department = ?, phone = ?, email = ? WHERE id = ?");
    $sql->bind_param("sssssssssi", $last_name, $first_name, $middle_name, $suffix, $employee_name, $employeeID, $department, $phone, $email, $id);

    if ($sql->execute()) {
      // Now update the users table
      $update_user_sql = $conn->prepare("UPDATE users SET fname = ?, lname = ?, email = ? WHERE employee_id = ?");
      $update_user_sql->bind_param("sssi", $first_name, $last_name, $email, $id);

      if ($update_user_sql->execute()) {
        echo "<script>alert('Employee information edited successfully.'); window.location='admin_manage_emp.php';</script>";
        exit;
      } else {
        echo "<script>alert('Error updating user information: " . $conn->error . "');</script>";
        exit;
      }

      $update_user_sql->close();
    } else {
      echo "<script>alert('Error: " . $conn->error . "');</script>";
      exit;
    }

    $sql->close();
  }

  $conn->close();
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Employee</title>
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

    .form-register {
      max-width: 500px;
      padding: 15px 35px 45px;
      margin: 0 auto;
      background-color: #fff;
      border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .form-register-heading {
      position: 30px;
      text-align: center;
    }

    .checkbox {
      margin-bottom: 30px;
      margin-left: 115px;
    }

    .form-control {
      font-size: 16px;
      padding: 10px;
      margin-bottom: 20px;
    }

    input[type="number"]::-webkit-inner-spin-button {
      -webkit-appearance: none;
    }

    label {
      margin-bottom: 5px;
      font-weight: bold;
    }

    .required {
      color: red;
    }
  </style>
  <script>
    // JavaScript function to show Yes/No confirmation
    function confirmAction() {
      return confirm("Are you sure you want to edit this employee's information?");
    }
  </script>
</head>

<body>
  <?php include "admin_bars.html" ?>
  <div class="content">
    <div class="wrapper">
      <!-- Add onsubmit event to trigger the confirmation prompt -->
      <form class="form-register" action="" method="POST" onsubmit="return confirmAction()">
        <h2 class="form-register-heading">Edit Employee</h2>

        <label for="lastname">Last name<span class="required">*</span></label>
        <input type="text" class="form-control" name="lastname" value="<?php echo $employee['lastname']; ?>" required />

        <label for="firstname">First name<span class="required">*</span></label>
        <input type="text" class="form-control" name="firstname" value="<?php echo $employee['firstname']; ?>" required />

        <label for="middlename">Middle name</label>
        <input type="text" class="form-control" name="middlename" value="<?php echo $employee['middlename']; ?>" />

        <label for="suffix">Suffix</label>
        <input type="text" class="form-control" name="suffix" value="<?php echo $employee['suffix']; ?>" />

        <label for="employeeID">Employee ID<span class="required">*</span></label>
        <input type="text" class="form-control" name="employeeID" value="<?php echo $employee['employeeID']; ?>" required />

        <label for="phone">Phone Number<span class="required">*</span></label>
        <input type="text" class="form-control" name="phone" value="<?php echo $employee['phone']; ?>" required />

        <label for="email">Email Address<span class="required">*</span></label>
        <input type="email" class="form-control" name="email" value="<?php echo $employee['email']; ?>" required />

        <label for="department">Department<span class="required">*</span></label>
        <select class="form-control" name="department" required>
          <option value="">Select Department</option>
          <option value="College" <?php if ($employee['department'] == 'College') echo 'selected'; ?>>College</option>
          <option value="Basic Ed" <?php if ($employee['department'] == 'Basic Ed') echo 'selected'; ?>>Basic Ed</option>
          <option value="Non-Teaching" <?php if ($employee['department'] == 'Non-Teaching') echo 'selected'; ?>>Non-Teaching</option>
        </select>

        <button class="btn btn-lg btn-success btn-block" type="submit">Edit</button>
        <br><br>
        <a href="admin_manage_emp.php" class="btn btn-lg btn-secondary">Back</a>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>