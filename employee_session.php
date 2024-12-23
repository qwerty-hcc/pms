<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first'); window.location='login.html';</script>";
    exit();
}

if ($_SESSION['user_role'] !== 'employee') {
    session_unset();
    session_destroy();
    echo "<script>alert('You are not an employee'); window.location='login.html';</script>";
    exit();
}
?>