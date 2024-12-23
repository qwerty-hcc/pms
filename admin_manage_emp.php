<?php
require 'admin_session.php';

require "config.php";
// Default query to fetch all employees
$query = "SELECT * FROM employees";
$result = mysqli_query($conn, $query);
$employees = [];

// Fetch all employees to display initially
while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

    <!-- Content Area -->
    <div class="content">
        <h2>Employee List</h2>

        <!-- Row for Search Bar and Create Project Button -->
        <div class="d-flex justify-content-between mb-3">
            <!-- Centered Search Bar -->
            <div class="mx-auto">
                <input class="form-control me-2" type="search" placeholder="Search Employees" aria-label="Search" id="searchInput">
            </div>
            <!-- Register Employee Button -->
            <div>
                <a href="register_employee.php" class="btn btn-success">Add Employee</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Employee ID</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employeeResults">
                <?php
                // Check if there are results and display them
                if (!empty($employees)) {
                    foreach ($employees as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employeeID']) . "</td>";
                        echo "<td>" . '+63 ' . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "<td><a href='edit_employee.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                        <a href='delete_employee.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this employee\'s information?');\" class='btn btn-danger btn-sm' style='margin-left: 20px;'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No employees found.</td></tr>";
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
                    url: 'search_employees.php', // Separate PHP file to handle search
                    type: 'GET',
                    data: { search: searchTerm },
                    success: function(data) {
                        $('#employeeResults').html(data);
                    }
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
