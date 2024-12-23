<?php
include 'config.php';

// Check if the search query is set
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM projects WHERE 
        project_name LIKE '%$search%' OR 
        progress LIKE '%$search%' OR 
        start_date LIKE '%$search%' OR 
        due_date LIKE '%$search%' OR 
        status LIKE '%$search%' OR 
        budget LIKE '%$search%'";
    
    $result = mysqli_query($conn, $query);
    // Prepare the results as HTML
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['project_name'] . "</td>";
            echo "<td>" . $row['progress'] . "</td>";
            echo "<td>" . $row['budget'] . "</td>";
            echo "<td>" . $row['start_date'] . "</td>";
            echo "<td>" . $row['due_date'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td><a href='project_details.php?id=" . $row['project_id'] . "' class='btn btn-info btn-sm'>Details</a>
                  <a href='edit_project.php?id=" . $row['project_id'] . "' class='btn btn-primary btn-sm' style='margin-left: 20px;'>Edit</a>
                  <a href='delete_project.php?id=" . $row['project_id'] . "' onclick=\"return confirm('Are you sure you want to delete this project?');\" class='btn btn-danger btn-sm' style='margin-left: 20px;'>Delete</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No projects found.</td></tr>";
    }
}
?>
