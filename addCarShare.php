<?php
require_once('functions.php');
$conn = connectdb();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate form inputs
    $userid = isset($_POST["uid"]) ? $_POST["uid"] : '';
    $seats = isset($_POST["seats"]) ? $_POST["seats"] : '';

    // Validate form inputs
    if (empty($userid) || empty($seats)) {
        die("ERROR: Please fill in all required fields.");
    }

    // Check if the database connection is successful
    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Fetch carpool details from the database based on uid
    $query = "SELECT * FROM offers WHERE uid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows == 0) {
        die("ERROR: Carpool details not found!");
    }

    $row = $result->fetch_assoc();
    $sender = $row["uid"]; // Assuming the column name is uid in the offers table
    $timestamp = date("Y-m-d H:i:s");

    // Insert notification for user requesting to join the carpool
    $query = 'INSERT INTO notifications (sender, receiver, type, uid, timestamp) VALUES(?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($query);
    $type = 4; // Assuming type 4 corresponds to a request
    $stmt->bind_param("iiiss", $userid, $userid, $type, $userid, $timestamp);
    $stmt->execute();
    if ($stmt->affected_rows == 0) {
        die("ERROR: Failed to insert notification!");
    }

    // Send notification for approval to car owner
    $type = 1; // Assuming type 1 corresponds to a notification for approval
    $stmt->bind_param("iiiss", $userid, $sender, $type, $userid, $timestamp);
    $stmt->execute();
    if ($stmt->affected_rows == 0) {
        die("ERROR: Failed to send notification to car owner!");
    }

    // Redirect to index.php with success message
    header("Location: index.php?success=1");
    exit();
}
?>
