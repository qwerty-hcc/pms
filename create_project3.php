<?php
// Include the database configuration file
include('config.php');

// Check if the form is submitted
if (isset($_POST['create_project'])) {
    // Get form data
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $budget = mysqli_real_escape_string($conn, $_POST['budget']);

    // Insert the project into the database
    $sql = "INSERT INTO projects (project_name, description, start_date, due_date, budget)
            VALUES ('$project_name', '$description', '$start_date', '$due_date', '$budget')";

    if (mysqli_query($conn, $sql)) {
        // Get the last inserted project ID
        $project_id = mysqli_insert_id($conn);
        
        // Trigger the modal and pass the project_id to the form
        echo "<script>
                alert('Project created successfully.');
                document.getElementById('modalProjectId').value = '$project_id';
                var myModal = new bootstrap.Modal(document.getElementById('assignEmployeesModal'));
                myModal.show();
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
}
?>
