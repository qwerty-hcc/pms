<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    echo "<script>alert('Please log in first'); window.location='login.html';</script>";
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first'); window.location='login.html';</script>";
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    session_unset();
    session_destroy();
    echo "<script>alert('You are not an admin'); window.location='login.html';</script>";
    exit();
}
?>