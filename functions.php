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
        $conn = dbConnect();
    
        // Prepare the SQL query to fetch the user by email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
    
        // Bind the email parameter to the SQL query
        $stmt->bind_param("s", $email);
        $stmt->execute();
    
        // Get the result
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $users = $result->fetch_assoc();
    
            // Debugging: Print stored password and hash comparison
            
    
            if (md5($password) === $users['password']) {
                return $users; // Return user data if the password matches
            } else {

                return false; // Invalid password
            }
        } else {

            return false; // No user found with that email
        }
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