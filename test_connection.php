<?php
// Include the dbinfo.php file to access the database connection details
require_once('dbinfo.php');

// Attempt to establish a connection to the database
$connection = mysqli_connect($host, $user, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully to the database.";

    // Example query: Select data from a table
    $sql = "SELECT * FROM users";
    $result = mysqli_query($connection, $sql);

    // Check if the query was successful
    if ($result) {
        // Process the query result
        // For example, fetch and display the data
        while ($row = mysqli_fetch_assoc($result)) {
            // Display the data
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }

        // Free the result set
        mysqli_free_result($result);
    } else {
        echo "Error executing the query: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
}
?>
