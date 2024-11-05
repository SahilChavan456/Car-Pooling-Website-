<?php
include("header.php");

// Establish a database connection
include('functions.php');

// Check if the user is not logged in and redirect to the login page
if (!loggedin()) {
    header("Location: login.php");
    exit; // Terminate further execution
}

$conn = connectdb(); // Assuming connectdb() establishes a MySQLi connection

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Car Pool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="css/datetimepicker.css">

    <!-- fav and touch icons -->
    <link rel="shortcut icon" href="http://twitter.github.com/bootstrap/assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">

    <style>
        /* Set text color to white */
        h2,
        span {
            color: #fff !important;
        }

        body {
            background-image: url('img/new.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        #main-content {
            background-color: rgba(255, 255, 255, 0.9); /* White with some transparency */
            padding: 20px;
            border-radius: 10px; /* Add some rounded corners */
        }
    </style>

</head>

<body onload="load()">
    <!-- Part 1: Wrap all page content here -->

    <!-- Begin page content -->
    <div class="container">
        <?php include('menu.php'); ?>
        <div class="row-fluid" id="main-content">
            <div class="span1"></div>
            <div class="span5">
                <h2 align="center" style="color: white;"><small>Search for a preferred ride</small></h2>

                <hr>
                <br />
                <form method="post" action="getride.php">
                    <input type="hidden" name="action" value="search" />
                    <?php if (isset($_POST['action'])) : ?>
                        <input type="text" name="from" value="<?php echo isset($_POST['from']) ? $_POST['from'] : ''; ?>" data-provide="typeahead" class="typeahead" placeholder="Source" required/><br/>
                        <input type="text" name="to" value="<?php echo isset($_POST['to']) ? $_POST['to'] : ''; ?>" data-provide="typeahead" class="typeahead" placeholder="Destination" required/><br/>
                        <div id="uptimepicker" class="input-append date">
                            <input type="text" value="<?php echo isset($_POST['uptime']) ? $_POST['uptime'] : ''; ?>" name="uptime"></input>
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                        </div> <br/>
                        <div id="downtimepicker" class="input-append date">
                            <input type="text" value="<?php echo isset($_POST['downtime']) ? $_POST['downtime'] : ''; ?>" name="downtime"></input>
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                        </div>
                    <?php else : ?>
                        <input type="text" name="from" data-provide="typeahead" class="typeahead" placeholder="Source" required/><br/>
                        <input type="text" name="to" data-provide="typeahead" class="typeahead" placeholder="Destination" required/><br/>
                        <div id="uptimepicker" class="input-append date">
                            <input type="text" placeholder="uptime" name="Uptime"></input>
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                        </div> <br/>
                        <div id="downtimepicker" class="input-append date">
                            <input type="text" placeholder="Downtime" name="downtime"></input>
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                        </div>
                        <div></div>
                    <?php endif; ?>

                    <label class="checkbox inline">
                        <input type="checkbox" id="inlineCheckbox2" name="car" value="Car"> Car
                    </label>

                    <label class="checkbox inline">
                        <input type="checkbox" id="inlineCheckbox1" name="bike" value="Bike"> Bike
                    </label>

                    <br/>
                    <br/>
                    <input class="btn" type="submit" name="submit" value="Search"/>
                </form>
            </div>
            <div class="span5">
                <h2 align="center"><small>Search Results</small></h2> <hr/>
                <?php if (isset($_POST['action'])) :
                    $from = isset($_POST['from']) ? $_POST['from'] : '';
                    $to = isset($_POST['to']) ? $_POST['to'] : '';
                    $uptime = isset($_POST['uptime']) ? $_POST['uptime'] : '';
                    $downtime = isset($_POST['downtime']) ? $_POST['downtime'] : '';
                    $bike = isset($_POST['bike']) ? 1 : 0;
                    $car = isset($_POST['car']) ? 1 : 0;
    
                    // Perform database queries and display search results accordingly
                    $query = "SELECT r1.uid 
                        FROM route r1 
                        INNER JOIN route r2 ON r1.place='".$from."' 
                        AND r2.place='".$to."' 
                        AND r1.serialno < r2.serialno 
                        AND r1.uid=r2.uid";
    
                    // Add condition for vehicle type (if car checkbox is checked)
                    if ($car == 1) {
                        $query .= " INNER JOIN offers o ON r1.uid = o.id AND o.vehicle = 'Car'";
                    }
    
                    $result = mysqli_query($conn, $query);
    
    
                    if(mysqli_num_rows($result) == 0) {
                        echo("<p align='center'>No Upcoming car pools match your request :( </p>\n");
                    } else {
                        echo '<table id="upcominglist" class="table table-hover">
                            <thead><tr><th>Id</th> <th>Vehicle Type</th> <th> From </th> <th> To </th> <th> Starting Time</th><th>More Details</th></tr></thead>
                            <tbody>';
    
                        while($row = mysqli_fetch_array($result)) {
                            $query2 = "SELECT `id`,`vehicle`,`from`,`to`,`uptime` 
                                FROM offers 
                                WHERE id='".$row['uid']."' 
                                AND `uptime` >='".$uptime."' 
                                AND `uptime` <='".$downtime."'";
    
                            $res = mysqli_query($conn, $query2);
    
                            if($res) {
                                while($result2 = mysqli_fetch_array($res)) {
                                    echo "<tr><td>".$result2['id']."</td><td>".$result2['vehicle']."</td><td>".$result2['from']."</td><td>".$result2['to']."</td><td>".$result2['uptime']."</td>";
                                    // Debugging code to check button id
                                    echo "<td><button class='details-button' onclick='redirectToRidePage(".$result2['id'].")'>More Details (ID: ".$result2['id'].")</button></td></tr>";
                                }
                            }
                        }
                        echo '</tbody></table>';
                    }
                endif;
                ?>
            </div>
        </div>
    </div>
    
    <!-- javascript files -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/datetimepicker.js"></script>
    <script type="text/javascript">
        $('#uptimepicker').datetimepicker({
            format: 'yyyy-MM-dd hh:mm:ss',
        });
        $('#downtimepicker').datetimepicker({
            format: 'yyyy-MM-dd hh:mm:ss',
        });
    
        // City typeahead
        <?php 
        $query = "SELECT city_name from cities";
        $result = mysqli_query($conn, $query);
        echo "var city = [];";
        while ($row = mysqli_fetch_array($result)) {
            echo 'city.push("' . $row["city_name"] . '");';
        }
        ?>
        $(".typeahead").typeahead({source : city})
    
        // Redirect function
        function redirectToRidePage(rideId) {
            window.location.href = "ride.php?id=" + rideId;
        }
    </script>
    </body>
    
    </html>