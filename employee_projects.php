<?php
include 'config.php';
session_start();

$logged_in_employee_id = $_SESSION['user_id'];


$query = "
    SELECT p.*, pe.projectRole 
    FROM projects p
    JOIN project_employees pe ON p.project_id = pe.project_id
    WHERE pe.employee_id = '$logged_in_employee_id'";

$result = mysqli_query($conn, $query);

$projects = [];
while ($row = mysqli_fetch_assoc($result)) {
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

        table,
        th,
        td {
            border: 1px solid #dee2e6;
        }

        th,
        td {
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
    <?php include 'employee_bars.html'; // Nav and Side bar 
    ?>
    <div class="content">
        <h2>Project List</h2>

        <div class="d-flex justify-content-between mb-3">
            <div class="mx-auto">
                <input class="form-control me-2" type="search" placeholder="Search Projects" aria-label="Search" id="searchInput">
            </div>
        </div>

        <div class="table-container">
            <table class="table table-striped" id="projectTable">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Budget</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="projectResults">
                    <?php
                    foreach ($projects as $row) {
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
                    ?>
                </tbody>
            </table>
        </div>

        <script>
            $(document).ready(function() {
                $('#searchInput').on('input', function() {
                    const searchTerm = $(this).val();

                    $.ajax({
                        url: 'employee_search_projects.php',
                        type: 'GET',
                        data: {
                            search: searchTerm
                        },
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