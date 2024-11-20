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
?>
