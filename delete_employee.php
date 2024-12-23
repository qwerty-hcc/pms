<?php
require 'config.php';
session_start();

// Check if the id is provided
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Fetch employee details to verify existence before deleting
    $result = $conn->query("SELECT * FROM employees WHERE id = '$id'");
    
    if ($result->num_rows == 1) {
        // Disable foreign key checks to allow deletion
        $conn->query("SET FOREIGN_KEY_CHECKS = 0");

        // Prepare to delete related user from the users table
        $delete_user_sql = $conn->prepare("DELETE FROM users WHERE employee_id = ?");
        $delete_user_sql->bind_param("i", $id);
        
        // Execute the deletion of the user
        if ($delete_user_sql->execute()) {
            // Proceed to delete employee record
            $delete_sql = $conn->prepare("DELETE FROM employees WHERE id = ?");
            $delete_sql->bind_param("i", $id);
            
            if ($delete_sql->execute()) {
                // Optionally delete related records in other tables
                $delete_related_sql = $conn->prepare("DELETE FROM project_employees WHERE employee_id = ?");
                $delete_related_sql->bind_param("i", $id);
                $delete_related_sql->execute();
                $delete_related_sql->close();

                echo "<script>alert('Employee record and related user deleted successfully.'); window.location='admin_manage_emp.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error deleting employee: " . $conn->error . "');</script>";
                exit;
            }

            $delete_sql->close();
        } else {
            echo "<script>alert('Error deleting user: " . $conn->error . "');</script>";
            exit;
        }

        // Enable foreign key checks after deletion
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    } else {
        echo "<script>alert('Employee not found'); window.location='admin_manage_emp.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request'); window.location='admin_manage_emp.php';</script>";
    exit;
}

$conn->close();
?>
