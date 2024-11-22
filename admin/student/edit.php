<?php 
$pageTitle = "Edit Student"; 
session_start();

require_once '../../functions.php'; // Make sure this includes your database connection logic
include('../partials/header.php');

// Initialize variables
$errors = [];
$studenttoedit = null;

// Connect to the database
$conn = connectDB(); // Replace this with your actual database connection function

// Check if `student_id` is passed in the URL
if (isset($_REQUEST['student_id'])) {
    $student_id = $_REQUEST['student_id'];

    // Fetch the student record from the database
    $query = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $studenttoedit = $result->fetch_assoc();
    } else {
        $errors[] = "Student record not found in the database.";
    }
}

// Handle form submission to update the student record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $updatedData = [
        'student_id' => $_POST['student_id'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name']
    ];

    // Validate first name and last name
    if (empty($_POST['first_name'])) {
        $errors[] = "First name is required.";
    }
    if (empty($_POST['last_name'])) {
        $errors[] = "Last name is required.";
    }

    // Update the record in the database if there are no errors
    if (empty($errors)) {
        $query = "UPDATE students SET first_name = ?, last_name = ? WHERE student_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $_POST['first_name'], $_POST['last_name'], $_POST['student_id']);

        if ($stmt->execute()) {
            // Redirect to the register page after successful update
            header("Location: register.php");
            exit;
        } else {
            $errors[] = "Failed to update the student record. Please try again.";
        }
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
            <?php include('../partials/side-bar.php'); ?>


        <!-- Main Content Section -->
        <div class="col-lg-10 col-md-9 mt-5">
            <div class="card p-4 container mt-5 shadow-sm">
                <h2 class="mb-4">Edit Student</h2>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
                    </ol>
                </nav>

                <!-- Error Messages -->
                <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger">
                        <strong>System Errors</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Edit Student Form -->
                <?php if ($studenttoedit): ?>
                    <form action="edit.php?student_id=<?= urlencode($studenttoedit['student_id']) ?>" method="post">
                        <div class="form-group mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="student_id" name="student_id" value="<?= htmlspecialchars($studenttoedit['student_id']) ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($studenttoedit['first_name']) ?>" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($studenttoedit['last_name']) ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Student</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Student record not found. Please try again.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
    include '../partials/footer.php';  // Correct path to footer.php
?>