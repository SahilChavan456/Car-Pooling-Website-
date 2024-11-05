<?php
require_once('functions.php');

// Check if the user is logged in
if (!loggedin()) {
    header("Location: login.php");
    exit;
}

// Connect to the database
$conn = connectdb();

// Check if the request type is set
if (isset($_POST["type"])) {
    $type = $_POST["type"];
    
    // Handle notification type 1
    if ($type == "1") {
        $slno = $_POST["serialNo"];
        $stat = $_POST["stat"];
        
        // Update notification status
        $query = 'UPDATE notifications SET status="' . $stat . '" WHERE slno="' . $slno . '"';
        $result = mysqli_query($conn, $query);

        // If the request is approved, update offers and user credits
        if ($stat == "Approved") {
            $query = 'SELECT * FROM notifications WHERE slno="' . $slno . '"';
            $notification_result = mysqli_query($conn, $query);
            $notification_row = mysqli_fetch_assoc($notification_result);
            $uid = $notification_row["uid"];
            $sender = $notification_row["sender"];
            $receiver = $notification_row["receiver"];

            // Update offers table
            $query = 'UPDATE offers SET status="Approved" WHERE id="' . $uid . '"';
            $update_offer_result = mysqli_query($conn, $query);

            // Update user credits
            // Example: Assuming each approved request adds 10 credits to the sender and receiver
            $credits_increment = 10;
            $query = 'UPDATE users SET credits = credits + ' . $credits_increment . ' WHERE uid IN ("' . $sender . '", "' . $receiver . '")';
            $update_credits_result = mysqli_query($conn, $query);
        }
    }
    // Handle notification type 2
    elseif ($type == "2") {
        $slno = $_POST["serialNo"];
        $comment = $_POST["rating"];
        
        // Insert comment into the database
        $query = 'INSERT INTO comments(sender, comment, uid) VALUES ("' . $receiver . '", "' . $comment . '", "' . $uid . '")';
        $result = mysqli_query($conn, $query);

        // Delete the notification after handling it
        $query = 'DELETE FROM notifications WHERE slno="' . $slno . '"';
        $result = mysqli_query($conn, $query);
    }
}
?>
