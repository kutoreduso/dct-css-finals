<?php
session_start();
$pageTitle = "Student Register "; 
require_once '../../functions.php';
include('../partials/header.php');

$studenttodelete = null;
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    
    // Connect to the database and fetch the student data
    $conn = dbConnect();
    $sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $studenttodelete = $result->fetch_assoc();
    } else {
        $studenttodelete = null;
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: register.php");  // Redirect to register page if no student_id is provided
    exit;
}

// If the form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Call the delete function
    if (deleteStudentById($student_id)) {
        // Redirect to the register page if the deletion was successful
        header("Location: register.php");
        exit;
    } else {
        // Error message in case of failure
        $error_message = "Failed to delete the student. Please try again.";
    }
}

?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include('../partials/side-bar.php'); ?>

        <!-- Main Content Section -->
        <div class="col-lg-10 col-md-9 mt-5">
            <h2 class="fw-bold">Delete a Student</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
                </ol>
            </nav>
            <div class="card mt-3">
                <div class="card-body">
                    <?php if ($studenttodelete): ?>
                        <h5>Are you sure you want to delete the following student record?</h5>
                        <ul>
                            <li><strong>Student ID:</strong> <?= htmlspecialchars($studenttodelete['student_id']) ?></li>
                            <li><strong>First Name:</strong> <?= htmlspecialchars($studenttodelete['first_name']) ?></li>
                            <li><strong>Last Name:</strong> <?= htmlspecialchars($studenttodelete['last_name']) ?></li>
                        </ul>
                        <form method="POST">
                            <input type="hidden" name="student_id" value="<?= htmlspecialchars($studenttodelete['student_id']) ?>">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='register.php';">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Student Record</button>
                        </form>
                    <?php else: ?>
                        <p class="text-danger">Student not found.</p>
                        <a href="register.php" class="btn btn-primary">Back to Student List</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
