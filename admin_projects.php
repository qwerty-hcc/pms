<?php
require 'admin_session.php';

include 'config.php';
// Fetch project data from the database
$query = "SELECT * FROM projects";
$result = mysqli_query($conn, $query);

// Default query to fetch all projects
$projects = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Check if the current date is past the due date
    $currentDate = date('Y-m-d');
    $dueDate = $row['due_date'];
    $progress = $row['progress'];

    // Determine the project status based on progress and due date
    if ($progress == 100) {
        // If progress is 100%, set status as Completed
        $newStatus = 'Completed';
    } elseif ($currentDate > $dueDate) {
        // If the current date is past the due date, set status as Passed Due
        $newStatus = 'Passed Due';
    } else {
        // Otherwise, set status as Ongoing
        $newStatus = 'Ongoing';
    }

    // Update the project status if it's different from the current status
    if ($row['status'] != $newStatus) {
        $updateQuery = "UPDATE projects SET status = '$newStatus' WHERE project_id = " . $row['project_id'];
        mysqli_query($conn, $updateQuery);
        $row['status'] = $newStatus;  // Update the status in the fetched data
    }

    $projects[] = $row;
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .status-completed {
            background-color: #28a745; /* Green */
        }

        .status-in-progress {
            background-color: #ffc107; /* Yellow */
        }

        .status-pending {
            background-color: #dc3545; /* Red */
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for transform and shadow */
        }

        .hover-card:hover {
            transform: translateY(-5px); /* Slightly raise the card */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
        }

        /* Button styles */
        .btn-edit {
            margin-left: 10px;
        }

        .btn-delete {
            margin-left: 5px;
        }
        

        .btn:hover {
            opacity: 0.9;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .table-container {
      margin-top: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table, th, td {
      border: 1px solid #dee2e6;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #023B87;
      color: white;
    }

    </style>
</head>
<body>
    <?php include 'admin_bars.html'; // Nav and Side bar ?>

    <div class="content">
        <h2>Project List</h2>

        <div class="d-flex justify-content-between mb-3">
            <div class="mx-auto">
                <input class="form-control me-2" type="search" placeholder="Search Projects" aria-label="Search" id="searchInput">
            </div>
            <div>
                <a href="create_project_form.php" class="btn btn-success">Create Project</a>
            </div>
        </div>

        <div class="table-container">
            <table class="table table-striped" id="projectTable">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Progress</th>
                        <th>Budget</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="projectResults">
                    <?php
                    foreach ($projects as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['project_name'] . "</td>";
                        echo "<td>" . $row['progress']. "%" . "</td>";
                        echo "<td>" . $row['budget'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['due_date'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td><a href='project_details.php?id=" . $row['project_id'] . "' class='btn btn-info btn-sm'>Details</a>
                              <a href='edit_project.php?id=" . $row['project_id'] . "' class='btn btn-primary btn-sm' style='margin-left: 20px;'>Edit</a>
                              <a href='delete_project.php?id=" . $row['project_id'] . "' onclick=\"return confirm('Are you sure you want to delete this project?');\" class='btn btn-danger btn-sm' style='margin-left: 20px;'>Delete</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <script>
            $(document).ready(function() {
                $('#searchInput').on('input', function() {
                    const searchTerm = $(this).val();

                    $.ajax({
                        url: 'search_projects.php',
                        type: 'GET',
                        data: { search: searchTerm },
                        success: function(data) {
                            $('#projectResults').html(data);
                        }
                    });
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
