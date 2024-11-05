<?php
/*
 * The landing page that lists all the problem
 */
require_once('functions.php');

if (!loggedin()) {
    header("Location: login.php");
    exit; // Terminate script execution
} else {
    include('header.php');
    $conn = connectdb(); // Assign the return value of connectdb() to $conn
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Notifications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <style>
        /* Background image */
        body {
            background-image: url('img/new.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            overflow: hidden; /* Prevent background image from showing through */
        }

        /* Style for the main content area */
        .main-content {
            background-color: rgba(255, 255, 255, 0.5); /* White with some transparency */
            padding: 20px;
            border-radius: 10px; /* Add some rounded corners */
            backdrop-filter: blur(5px); /* Apply blur effect */
        }

        /* Style for the table container */
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px; /* Add some rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
        }

        /* Style for table headers */
        th {
            background-color: #f8f9fa; /* Light gray */
        }

        /* Style for table cells */
        td {
            color: #212529; /* Dark gray */
        }
    </style>
</head>

<body>
    <!-- Part 1: Wrap all page content here -->

    <!-- Begin page content -->
    <div class="container">
        <?php
        if (isset($_GET['share'])) {
            echo("<div class=\"alert alert-info\">\nYour Ride was successfully added! You can edit it from your profile\n</div>");
        } else if (isset($_GET['nerror'])) {
            echo("<div class=\"alert alert-error\">\nPlease enter all the details asked before you can continue!\n</div>");
        }
        ?>

        <?php include('menu.php'); ?>

        <div class="row-fluid" id="main-content">
            <div class="span5"></div>
            <div class="span5">
                <div class="table-container">
                    <?php
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $query = "SELECT * FROM users WHERE uid=?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $fetch = $result->fetch_assoc();
                            $name = $fetch['name'];
                            $email = $fetch['email'];
                            $gender = $fetch['gender'];
                            $contact = $fetch['contactno'];
                            $desc = $fetch['description'];
                            $credits = $fetch['credits'];
                            $badge = "Newbie in town";
                            $rankQuery = "SELECT uid FROM users ORDER BY credits DESC";
                            $result = $conn->query($rankQuery);
                            $numRows = $result->num_rows;
                            $top = $numRows / 3;
                            $middle = $top * 2;
                            $i = 1;
                            while ($row = $result->fetch_assoc()) {
                                if ($row['uid'] == $id) {
                                    if ($i <= $top) {
                                        $badge = "Trusted Car Pooler";
                                    } else if ($i <= $middle) {
                                        $badge = "Budding Car Pooler";
                                    }
                                }
                                $i++;
                            }
                            // Display user details
                            echo "<p>Name: &nbsp; <strong>$name</strong></p>";
                            echo "<p>Email Id: &nbsp; <strong>$email</strong></p>";
                            echo "<p>Gender: &nbsp; <strong>$gender</strong></p>";
                            echo "<p>Contact: &nbsp; <strong>$contact</strong></p>";
                            echo "<p>Description: &nbsp; <strong>$desc</strong></p>";
                            echo "<p>Carbon Credits: &nbsp;<strong>$credits</strong></p>";
                            echo "<p>Badge:<span class='label label-success'> &nbsp;$badge</span></p>";
                        } else {
                            echo "User not found.";
                        }
                    } else {
                        // If no user ID is provided, fetch details of logged in user
                        $email = $_SESSION['username'];
                        $uid = getUserid();
                        $query = "SELECT * FROM users WHERE email=?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $fetch = $result->fetch_assoc();
                            $name = $fetch['name'];
                            $gender = $fetch['gender'];
                            $contact = $fetch['contactno'];
                            $desc = $fetch['description'];
                            $credits = $fetch['credits'];
                            $badge = "Newbie in town";
                            $rankQuery = "SELECT * FROM users ORDER BY credits DESC";
                            $result = $conn->query($rankQuery);
                            $numRows = $result->num_rows;
                            $top = $numRows / 3;
                            $middle = $top * 2;
                            $i = 1;
                            while ($row = $result->fetch_assoc()) {
                                if ($row['uid'] == $uid) {
                                    if ($i <= $top) {
                                        $badge = "Trusted Car Pooler";
                                    } else if ($i <= $middle) {
                                        $badge = "Budding Car Pooler";
                                    }
                                }
                                $i++;
                            }
                            // Display user details
                            echo "<p>Name: &nbsp; <strong>$name</strong></p>";
                            echo "<p>Email Id: &nbsp; <strong>$email</strong></p>";
                            echo "<p>Gender: &nbsp; <strong>$gender</strong></p>";
                            echo "<p>Contact: &nbsp; <strong>$contact</strong></p>";
                            echo "<p>Description: &nbsp; <strong>$desc</strong></p>";
                            echo "<p>Carbon Credits: &nbsp;<strong>$credits</strong></p>";
                            echo "<p>Badge:<span class='label label-success'> &nbsp;$badge</span></p>";
                        } else {
                            echo "User not found.";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="span2"></div>
        </div>

    </div>

    <div id="push"></div>
    </div> <!-- /wrap -->

    <!-- javascript files
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
