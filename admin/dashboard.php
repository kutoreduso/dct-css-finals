
<?php

$pageTitle = "Dashboard";
require_once '../functions.php'; 
include 'partials/header.php'; // Include header here
include 'partials/side-bar.php'; // Include sidebar here


// Now you can safely call countAllStudents()

$total_subjects = countAllSubjects();
$total_students = countAllStudents();  // This function should now work
$passedandfailedsubject = calculateTotalPassedAndFailedStudents();
?>

    
<!-- Template Files here -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <h1 class="h2">Dashboard</h1>        
    
    <div class="row mt-5">
        <div class="col-12 col-xl-3">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white border-primary">Number of Subjects:</div>
                <div class="card-body text-primary">
                    <h5 class="card-title"><?php echo $total_subjects; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white border-primary">Number of Students:</div>
                <div class="card-body text-success">
                    <h5 class="card-title"><?php echo $total_students; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-danger mb-3">
                <div class="card-header bg-danger text-white border-danger">Number of Failed Students:</div>
                <div class="card-body text-danger">
                    <h5 class="card-title"><?php echo $passedandfailedsubject['failed']; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white border-success">Number of Passed Students:</div>
                <div class="card-body text-success">
                    <h5 class="card-title"><?php echo $passedandfailedsubject['passed']; ?></h5>
                </div>
            </div>
        </div>
    </div>    
</main>
<!-- Template Files here -->

<?php 
    include 'partials/footer.php';  // Corrected path
?>
