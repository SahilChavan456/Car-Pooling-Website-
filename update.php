<?php
include('functions.php');
$conn = connectdb();

if (isset($_POST['action']) && $_POST['action'] == 'shareride') {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $uptime = $_POST['uptime'];
    $cost = $_POST['cost'];
    $desc = $_POST['description'];
    $number = $_POST['number'];

    // Set the vehicle type to car or bike based on the form submission
    $vehicle = ($_POST['vehicle'] == 'car' || $_POST['vehicle'] == 'bike') ? $_POST['vehicle'] : 'car';

    // Get the user ID of the currently logged-in user
    $uid = getUserId();

    // Insert into the 'offers' table with uid included
    $insert_query = "INSERT INTO offers (`uid`, `from`, `to`, uptime, people, price, vehicle, description) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($insert_query)) {
        $stmt->bind_param("isssssss", $uid, $from, $to, $uptime, $number, $cost, $vehicle, $desc);

        if ($stmt->execute()) {
            $cid = $stmt->insert_id;
            $stmt->close();
            
            // Insert into the 'route' table
            $stmt_route = $conn->prepare("INSERT INTO route (uid, place, serialno) VALUES (?, ?, ?)");
            $serialno = 1;

            // Bind parameters for the 'route' table
            $stmt_route->bind_param("isi", $cid, $from, $serialno);
            $stmt_route->execute();

            // Insert intermediate places
            for ($i = 1; $i <= $number; $i++) {
                $id = "dynamic" . $i;
                if (isset($_POST[$id])) {
                    $data = $_POST[$id];
                    $serialno++;
                    $stmt_route->bind_param("isi", $cid, $data, $serialno);
                    $stmt_route->execute();
                } else {
                    // Handle missing data gracefully or log a warning
                    echo "Warning: Missing data for field $id";
                }
            }

            // Insert the destination
            $serialno++;
            $stmt_route->bind_param("isi", $cid, $to, $serialno);
            $stmt_route->execute();
            $stmt_route->close();

            // Redirect after successful insertion
            header("Location: index.php?share=1");
            exit;
        } else {
            echo "Error executing statement: " . $stmt->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>
