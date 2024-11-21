<?php    


    // All project functions should be placed here
    function dbConnect() {
        $host = "localhost"; // Server hostname
        $username = "root";  // Database username
        $password = "";      // Database password
        $database = "dct-ccs-finals"; // Database name
    
        // Create a new connection
        $conn = new mysqli($host, $username, $password, $database);
        
        // Check for connection error
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        return $conn; // Return the connection object
    }
    
    
    // Example of using the function to get a database connection
    $conn = dbConnect();
    

    function guard() {
        if (empty($_SESSION['email']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
            header("Location: index.php"); 
            exit;
        }
    }
    function loginUser($email, $password) {
        $errors = [];
        $notification = null;
    
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
    
        // Call the function to check credentials
        if (checkLoginCredentials($email, $password)) {
            // If credentials are correct, set session variables and redirect to dashboard
            $_SESSION['email'] = $email;
            $_SESSION['current_page'] = 'dashboard.php';
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $notification = "<li>Invalid email or password.</li>";
            return $notification;
        }
    }