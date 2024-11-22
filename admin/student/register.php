
<?php 
$pageTitle = "Student Register "; 
session_start();


require_once '../../functions.php';
include('../partials/header.php');


$errors = [];
$student_data = [];

if (!isset($_SESSION['student_data'])) {
    $_SESSION['student_data'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $student_id = trim($_POST['student_id'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    
    $student_data = compact('student_id', 'first_name', 'last_name');

    // Validate input
    $errors = validateStudentData($student_data);

    if (empty($errors)) {
        // Check for duplicate student ID
        if (checkDuplicateStudentData($student_id)) {
            $errors[] = "Error: Student ID already exists.";
        } else {
            // Attempt to add the student data
            if (addStudentData($student_id, $first_name, $last_name)) {
                // Redirect on successful addition
                header("Location: register.php?success=1");
                exit;
            } else {
                $errors[] = "Error: Could not add the student. Please try again.";
            }
        }
    }

    // If there are errors, store them in the session to display on the form
    $_SESSION['errors'] = $errors;
}

// Example usage of selectStudents to fetch students from the database
$students = selectStudents(); // This will call the function defined in functions.php

// Display the list of students
if (!empty($students)) {
    foreach ($students as $student) {
        echo "Student ID: " . $student['student_id'] . "<br>";
        echo "First Name: " . $student['first_name'] . "<br>";
        echo "Last Name: " . $student['last_name'] . "<br><br>";
    }
} else {
    echo "No students found.";
}
?>


<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include('../partials/side-bar.php'); ?>
        <!-- Main Content Section -->
        <div class="col-lg-10 col-md-9 mt-5">
            <h2>Register a New Student</h2>
            <br>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Register Student</li>
                </ol>
            </nav>
            <hr>
            <br>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Errors</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" 
                           placeholder="Enter Student ID" 
                           value="<?php echo isset($student_data['student_id']) ? htmlspecialchars($student_data['student_id']) : ''; ?>">
                </div>
                <div class="form-group mt-3">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           placeholder="Enter First Name" 
                           value="<?php echo isset($student_data['first_name']) ? htmlspecialchars($student_data['first_name']) : ''; ?>">
                </div>
                <div class="form-group mt-3">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           placeholder="Enter Last Name" 
                           value="<?php echo isset($student_data['last_name']) ? htmlspecialchars($student_data['last_name']) : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add Student</button>
            </form>
            <hr>

            <h3 class="mt-5">Student List</h3>
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $students = selectStudents();
                        if(!empty($students)):?>
                            <?php foreach($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                    <td>
                                        <a href="edit.php?student_id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-info btn-sm">Edit</a>
                                        <a href="delete.php?student_id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-danger btn-sm">Delete</a>
                                        <a href="attach-subject.php?student_id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-warning btn-sm">Attach Subject</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No student records found.</td>
                    </tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php 
   include '../partials/footer.php';  // Correct path to footer.php

?>