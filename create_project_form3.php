<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: #eee !important;
        }
        .wrapper {
            margin-top: 80px;
            margin-bottom: 80px;
        }
        .form-create-project {
            max-width: 500px;
            padding: 15px 35px 45px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid rgba(0,0,0,0.1);
        }
        .form-heading {
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
        .error {
            color: red;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
    <script>
      function validateDates() {
          const startDate = new Date(document.getElementById('start_date').value);
          const dueDate = new Date(document.getElementById('due_date').value);
          const errorMsg = document.getElementById('dateError');

          if (startDate && dueDate && dueDate < startDate) {
              errorMsg.textContent = 'Due date cannot be earlier than start date.';
              return false;
          } else {
              errorMsg.textContent = '';
          }
          return true;
      }

      function confirmCreate() {
          return confirm("Are you sure you want to create this project?");
      }
    </script>
  </head>
  <body>
    <div class="wrapper">
        <form class="form-create-project" action="create_project.php" method="POST" onsubmit="return validateDates() && confirmCreate()">
          <h2 class="form-heading">Create Project</h2>

          <label for="project_name">Project Name<span class="required">*</span></label>
          <input type="text" class="form-control" name="project_name" placeholder="" required autofocus>

          <label for="description">Project Description</label>
          <textarea class="form-control" name="description" placeholder=""></textarea>
          
          <label for="start_date">Start Date<span class="required">*</span></label>
          <input type="date" class="form-control" name="start_date" id="start_date" required>
          
          <label for="due_date">Due Date<span class="required">*</span></label>
          <input type="date" class="form-control" name="due_date" id="due_date" required>

          <div id="dateError" class="error"></div>

          <label for="budget">Budget (PHP)</label>
          <input type="number" class="form-control" name="budget" placeholder="ex. 1000" step="0.01">

          <button class="btn btn-lg btn-success btn-block" type="submit" name="create_project">Create Project</button>
          <br>
          <button type="button" class="btn btn-lg btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#assignEmployeesModal">Assign Employees</button>
          <br>
          <a href="admin_projects.php">Back</a>
        </form>        
      </div>

      <!-- Modal for Assigning Employees -->
      <div class="modal fade" id="assignEmployeesModal" tabindex="-1" aria-labelledby="assignEmployeesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="assignEmployeesModalLabel">Assign Employees</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="assignEmployeesForm" action="process_assignment.php" method="POST">
                <input type="hidden" name="project_id" id="modalProjectId">

                <div id="employeeList">
                  <!-- Employee checkboxes will be populated here via PHP -->
                  <?php
                    $result = mysqli_query($conn, "SELECT id, employee_name FROM employees ORDER BY employee_name ASC");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='form-check'>";
                        echo "<input class='form-check-input employee-checkbox' type='checkbox' name='employees[]' value='" . $row['id'] . "' id='employee_" . $row['id'] . "'>";
                        echo "<label class='form-check-label' for='employee_" . $row['id'] . "'>" . $row['employee_name'] . "</label>";
                        echo "</div>";
                    }
                  ?>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" form="assignEmployeesForm" class="btn btn-primary">Assign Selected</button>
            </div>
          </div>
        </div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
