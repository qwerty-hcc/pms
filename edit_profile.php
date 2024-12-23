<?php
require 'admin_session.php';
require 'config.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM admin WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $suffix = $_POST['suffix'];
    $email = $_POST['email'];

    $update_sql = "UPDATE admin SET firstname=?, middlename=?, lastname=?, suffix=?, email=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssss", $firstname, $middlename, $lastname, $suffix, $email, $user_id);

    if ($update_stmt->execute()) {
        echo "<script type='text/javascript'>
        alert('Profile changes saved');
        window.location.href = 'admin_profile.php'; // Replace with your desired URL
      </script>";
        exit;
    } else {
        echo "Error updating profile!";
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Profile</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
                    @import "bourbon";

body {
  background: #eee !important;	
}

.wrapper {	
  margin-top: 80px;
  margin-bottom: 80px;
}

.box {
  max-width: 500px;
  padding: 15px 35px 45px;
  margin: 0 auto;
  background-color: #fff;
  border: 1px solid rgba(0,0,0,0.1);  
}

.box-heading {
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

input[type="number"]::-webkit-inner-spin-button{
    -webkit-appearance: none;
  }

label {
  margin-bottom: 5px;
  font-weight: bold;
}

. {
  color: red;
}

.error {
  color: red;
  font-size: 14px;
  margin-top: -10px;
  margin-bottom: 10px;
}

.profile-picture-container {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
  }
        </style>
    </head>
    <body>
        <?php include 'admin_bars.html'; ?>
        <div class="content">
            <div class="box">
                <h2 class="box-heading">Edit Profile</h2>
                <form method="post">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $admin['firstname']; ?>" required>

                    <label for="middlename">Middle Name</label>
                    <input type="text" id="middlename" name="middlename" class="form-control" value="<?php echo $admin['middlename']; ?>">

                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo $admin['lastname']; ?>" required>

                    <label for="suffix">Suffix</label>
                    <input type="text" id="suffix" name="suffix" class="form-control" value="<?php echo $admin['suffix']; ?>">

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $admin['email']; ?>" required>
                    <br>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <br><br>
                    <a onclick="goBack()" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
        <script>
            function goBack() {
                window.history.back();
            }
            </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
