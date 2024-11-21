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
    
    // Function to check login credentials against the database
    function checkLoginCredentials($email, $password) {
        // Establish database connection
        $conn = dbConnect();
    
        // Prepare the SQL query to find the user by email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            die("Failed to prepare query: " . $conn->error);
        }
    
        $stmt->bind_param("s", $email);
        $stmt->execute();
    
        // Get the result
        $result = $stmt->get_result();
    
        // Check if a user with this email exists
        if ($result->num_rows > 0) {
            // Fetch the user data
            $user = $result->fetch_assoc();
    
            // Check if the password matches the hashed password stored in the database
            if (password_verify($password, $user['password'])) {
                return true;
            }
        }
    
        return false;
    }
    
    // Function to display errors
    function displayErrors($errors) {
        $output = "<ul>";
        foreach ($errors as $error) {
            $output .= "<li>" . htmlspecialchars($error) . "</li>";
        }
        $output .= "</ul>";
        return $output;
    }
?>
