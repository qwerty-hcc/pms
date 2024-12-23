<?php
// Include the database configuration file
include('config.php');

// Get the project ID from the URL
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assign Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: #eee !important;
        }

        .wrapper {
            margin-top: 80px;
            margin-bottom: 80px;
        }

        .form-assign-employees {
            max-width: 500px;
            padding: 15px 35px 45px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .form-assign-employees .form-heading {
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control {
            font-size: 16px;
            padding: 10px;
            margin-bottom: 20px;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .required {
            color: red;
        }

        .search-bar {
            margin-bottom: 15px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
    <script>
      function filterEmployees() {
          const searchInput = document.getElementById('searchInput').value.toLowerCase();
          const checkboxes = document.querySelectorAll('.employee-checkbox');

          checkboxes.forEach(checkbox => {
              const label = checkbox.nextElementSibling.textContent.toLowerCase();
              checkbox.parentElement.style.display = label.includes(searchInput) ? '' : 'none';
          });
      }

      function confirmAssignment() {
          const checkboxes = document.querySelectorAll('.employee-checkbox:checked');
          if (checkboxes.length === 0) {
              alert("Please select at least one employee to assign.");
              return false; // Prevent form submission
          }
          return confirm("Are you sure you want to assign the selected employee/s to this project?");
      }
    </script>
</head>
<body>
    <div class="wrapper">
        <form class="form-assign-employees" action="process_assignment.php" method="POST" onsubmit="return confirmAssignment()">
          <h2 class="form-heading">Assign Employees</h2>

          <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">

          <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search employees..." onkeyup="filterEmployees()">
          <div>
            <?php
            // Fetch employees from the employees table, sorted by employee_name
            $result = mysqli_query($conn, "SELECT id, employee_name FROM employees ORDER BY employee_name ASC");

            // Loop through each employee and create a checkbox
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='form-check'>";
                echo "<input class='form-check-input employee-checkbox' type='checkbox' name='employees[]' value='" . $row['id'] . "' id='employee_" . $row['id'] . "'>";
                echo "<label class='form-check-label' for='employee_" . $row['id'] . "'>" . $row['employee_name'] . "</label>";
                echo "</div>";
            }

            // Close the connection
            mysqli_close($conn);
            ?>
          </div>

          <button class="btn btn-lg btn-success btn-block" type="submit" name="assign_employees">Assign Selected Employees</button>
          <br>
          <button type="button" class="btn btn-lg btn-primary btn-block" onclick="window.location='admin_projects.php'" style="margin-top: 10px;">Skip</button>
        </form>        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
