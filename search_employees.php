<?php
require "config.php"; // Include database connection only once

// Check if the search query is set
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM employees WHERE 
        employee_name LIKE '%$search%' OR 
        middlename LIKE '%$search%' OR 
        email LIKE '%$search%' OR
        department LIKE '%$search%' OR
        phone LIKE '%$search%' OR
        employeeID LIKE '%$search%'";
    
    $result = mysqli_query($conn, $query);

    // Prepare the results as HTML
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['employeeID']) . "</td>";
            echo "<td>" . '+63 ' . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['department']) . "</td>";
            echo "<td><a href='edit_employee.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                  <a href='delete_employee.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this employee?');\" class='btn btn-danger btn-sm' style='margin-left: 20px;'>Delete</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No employees found.</td></tr>";
    }
}

// Close the database connection
$conn->close();
?>
