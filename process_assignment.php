<?php
// Include the database configuration file
include('config.php');

// Check if the form is submitted
if (isset($_POST['assign_employees'])) {
    // Get the project ID
    $project_id = intval($_POST['project_id']);
    $employees = isset($_POST['employees']) ? $_POST['employees'] : []; // This is an array of selected employee IDs

    // Insert selected employees into the project_employees table
    foreach ($employees as $employee_id) {
        $sql_employee = "INSERT INTO project_employees (project_id, employee_id) 
                         VALUES ('$project_id', '$employee_id')";
        if (!mysqli_query($conn, $sql_employee)) {
            echo "Error: " . $sql_employee . "<br>" . mysqli_error($conn);
        }
    }

    // Redirect back to the projects page with a success message
    echo "<script>alert('Employee/s assigned successfully.'); window.location='admin_projects.php';</script>";
}

// Close the database connection
mysqli_close($conn);
?>
