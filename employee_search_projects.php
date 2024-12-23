<?php
include 'config.php';
session_start();

$logged_in_employee_id = $_SESSION['user_id'];

// Check if the search query is set
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "
        SELECT p.* , pe.projectRole 
        FROM projects p
        JOIN project_employees pe ON p.project_id = pe.project_id
        WHERE pe.employee_id = '$logged_in_employee_id' 
        AND (
            p.project_name LIKE '%$search%' OR 
            p.description LIKE '%$search%' OR 
            p.start_date LIKE '%$search%' OR 
            p.due_date LIKE '%$search%' OR 
            p.status LIKE '%$search%' OR 
            p.budget LIKE '%$search%'
        )";

    $result = mysqli_query($conn, $query);
    // Prepare the results as HTML
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['project_name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['budget'] . "</td>";
            echo "<td>" . $row['start_date'] . "</td>";
            echo "<td>" . $row['due_date'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            $detailsUrl = $row['projectRole'] === 'Project Manager'
                ? "manager_project_details.php?id=" . $row['project_id']
                : "employee_project_details.php?id=" . $row['project_id'];

            echo "<td><a href='" . htmlspecialchars($detailsUrl) . "' class='btn btn-info btn-sm'>Details</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No projects found.</td></tr>";
    }
}
