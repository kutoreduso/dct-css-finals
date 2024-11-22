<?php    

// All project functions should be placed here
function dbConnect() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "dct-ccs-finals";
    
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . " (Error Code: " . $conn->connect_errno . ")");
    }
    
    return $conn;
}

// Function to validate the login credentials
function checkLoginCredentials($email, $password) {
    // Assuming you have a way to retrieve the $users data (e.g., from a database)
    if (md5($password) === $users['password']) {
        return $users; // Return user data if the password matches
    }
    return false; // Invalid password or no user found with that email
}

function loginUser($email, $password) {
    $errors = [];
    
    // Sanitize inputs
    $email = trim($email);
    $password = trim($password);
    
    // Validate the form fields
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    
    if (!empty($errors)) {
        return displayErrors($errors); // Return error messages
    }
    
    // Check credentials
    $user = checkLoginCredentials($email, $password);
    if ($user) {
        // If credentials are correct, set session variables and redirect to dashboard
        $_SESSION['email'] = $user['email'];
        $_SESSION['current_page'] = 'dashboard.php';
        header("Location: admin/dashboard.php");
        exit;
    } else {
        return "<li>Invalid email or password.</li>";
    }
}

function checkUserSessionIsActive() {
    $dashboardPage = 'admin/dashboard.php';
    $indexPage = 'index.php';
    if (isset($_SESSION['email']) && basename($_SERVER['PHP_SELF']) == $indexPage) {
        header("Location: $dashboardPage");
        exit;
    }
}

function checkUserSessionIsActiveDashboard() {
    $dashboardPage = 'dashboard.php';
    $currentPage = basename($_SERVER['PHP_SELF']);
    
    if (isset($_SESSION['email']) && $currentPage === 'index.php') {
        header("Location: $dashboardPage");
        exit;
    }
}

function countAllStudents() {
    // Establish a new database connection
    $conn = dbConnect();
    // SQL query to count all records in the 'students' table
    $sql = "SELECT COUNT(*) AS total FROM students";  // Replace 'students' with your actual table name
    
    // Execute the query
    $result = mysqli_query($conn, $sql);
    
    // Check if the query was successful
    if ($result) {
        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);
        
        // Return the total count of students
        return $row['total'];  // 'total' is the alias for COUNT(*)
    } else {
        // Return 0 if the query failed or there are no records
        return 0;
    }
}

function countAllSubjects() {
    $conn = dbConnect();  // Make sure this connects to your database
    
    // SQL query to count all subjects in the "subjects" table
    $sql = "SELECT COUNT(*) AS total FROM subjects";  // Replace 'subjects' with the correct table name
    
    // Execute the query
    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result) {
        // Fetch the result as an associative array
        $row = $result->fetch_assoc();
    
        // Return the total count of subjects
        return $row['total'];  // 'total' is the alias for COUNT(*)
    } else {
        // If the query failed, return 0
        return 0;
    }
}

function calculateTotalPassedAndFailedStudents() {
    $conn = dbConnect();  // Establish a connection to the database
    
    // Check if the column exists first (optional)
    $result = $conn->query("DESCRIBE students");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];  // Collect column names
    }
    
    if (!in_array('result', $columns)) {
        // If the 'result' column does not exist, return 0 for both passed and failed
        return ['passed' => 0, 'failed' => 0];
    }
    
    // Now perform the query for passed/failed students
    $sql = "SELECT 
                SUM(CASE WHEN result = 'passed' THEN 1 ELSE 0 END) AS passed,
                SUM(CASE WHEN result = 'failed' THEN 1 ELSE 0 END) AS failed
            FROM students";  // Replace 'students' and 'result' with the correct table and column names
    
    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result) {
        $row = $result->fetch_assoc();
        return [
            'passed' => $row['passed'],
            'failed' => $row['failed']
        ];
    } else {
        // Return 0 if the query fails
        return ['passed' => 0, 'failed' => 0];
    }
}

function selectStudents() {
    // Establish a connection to the database (assumes dbConnect() is already defined)
    $conn = dbConnect();

    // SQL query to select all students
    $sql = "SELECT * FROM students"; // Adjust this if necessary

    // Execute the query
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result) {
        // Fetch all students as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Return an empty array or handle the error if no students were found
        return [];
    }
}
function validateStudentData($data) {
    $errors = [];

    // Check if student ID is provided and valid
    if (empty($data['student_id'])) {
        $errors[] = 'Student ID is required.';
    }

    // Check if first name is provided
    if (empty($data['first_name'])) {
        $errors[] = 'First name is required.';
    }

    // Check if last name is provided
    if (empty($data['last_name'])) {
        $errors[] = 'Last name is required.';
    }

    // Additional validation logic can be added here

    return $errors;
}
function checkDuplicateStudentData($student_id) {
    // Establish a connection to the database (dbConnect() should be defined earlier)
    $conn = dbConnect();
    
    // Prepare the SQL query to check for an existing student ID
    $sql = "SELECT COUNT(*) FROM students WHERE student_id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind the student ID parameter to the prepared statement
    $stmt->bind_param('s', $student_id);  // 's' for string
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $stmt->bind_result($count);
    $stmt->fetch();
    
    // Close the statement and connection
    $stmt->close();
    $conn->close();
    
    // Return true if a student with this ID already exists, false otherwise
    return $coun
    t > 0;
}
function addStudentData($student_id, $first_name, $last_name) {
    // Establish a connection to the database
    $conn = dbConnect();  // Ensure dbConnect() is properly defined elsewhere

    // Prepare the SQL query to insert the student data
    $sql = "INSERT INTO students (student_id, first_name, last_name) VALUES (?, ?, ?)";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters to the prepared statement
    $stmt->bind_param("sss", $student_id, $first_name, $last_name);  // 's' for string
    
    // Execute the statement
    $result = $stmt->execute();
    
    // Close the statement and connection
    $stmt->close();
    $conn->close();
    
    // Return the result of the insert operation (true on success, false on failure)
    return $result;
}

?>
