<?php    
    // All project functions should be placed here
    function dbConnect() {
        $host = "localhost"; // Server hostname
        $username = "root";  // Database username
        $password = "";      // Database password
        $database = "dct-ccs-finals"; // Database name
    
        // Create a new connection
        $conn = new mysqli($host, $username, $password, $database);
    }
?>