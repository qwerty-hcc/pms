<!DOCTYPE html>
<!-- Coding By CodingNepal - youtube.com/codingnepal -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project Management System</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
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
    echo "<script>alert('NO ROLE'); window.location='../login.html';</script>";
}
?>