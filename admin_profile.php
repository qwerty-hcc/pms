<?php
require 'admin_session.php';
require 'config.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM admin WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
}
?>
<!doctype html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Management System</title>
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
        <?php include 'admin_bars.html'; // Nav and Side bar?>
        <!-- Content Area -->
        <div class="content">
            <div class="box">
            <h2 class="box-heading">Profile</h2>

            <?php 
            $user_id = $_SESSION['unique_id'];
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }else{
              header("location: users.php");
            }
          ?>
        <div class="profile-picture-container">
            <img src="chats/php/images/<?php echo $row['img']; ?>" alt="Profile Picture" style="width:150px;height:150px;border-radius:50%;margin-top:20px;">
        </div>
            <!-- <form action="" method="post" enctype="multipart/form-data">
                <label for="profile_picture">Change Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                <button type="submit" class="btn btn-primary mt-2">Upload</button>
            </form> -->
            <br>
            <label>Name</label>
            <input type="text" class="form-control" value="<?php echo ($admin['firstname'] ?? '') . ' ' . ($admin['middlename'] ?? '') . ' ' . ($admin['lastname'] ?? '') . ' ' . ($admin['suffix'] ?? ''); ?>" disabled/>

            <label>Email Address</label>
            <input type="text" class="form-control" value="<?php echo $admin['email']; ?>" disabled/>       

            <div class="d-flex justify-content-center mt-3">
                <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            </div>

            <br>
          <a onclick="goBack()" class="btn btn-secondary">Back</a>
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