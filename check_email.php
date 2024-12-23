<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);

    $check_email_query = "SELECT * FROM users_login_view WHERE email = '$email'";
    $email_result = $conn->query($check_email_query);

    if ($email_result->num_rows > 0) {
        echo 'exists'; // Email exists
    } else {
        echo 'not_exists'; // Email does not exist
    }
}

// Close connection
$conn->close();
?>
