<?php
/*
 * Header for user pages
 * http://localhost/carpool/ride.php?id=3
 */
// Move the session_start() call to the top of the file
session_start();

include("header.php");
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
</head>

<body onload="load()">
    <!-- Part 1: Wrap all page content here -->
    <div class="container">
        <body onload="load()" style="background-image: url('img/new.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

            <?php
            if (isset($_GET['changed'])) {
                echo '<div class="alert alert-info">Account details changed successfully!</div>';
            } elseif (isset($_GET['nerror'])) {
                echo '<div class="alert alert-error">Please enter all the details asked before you can continue!</div>';
            }
            ?>

            <?php include('menu.php'); ?>

            <div class="row-fluid" id="main-content">
                <div class="span2"></div>
                <div class="span5" style="background-color: rgba(255, 255, 255, 0.9); padding: 20px; border-radius: 10px;">
                    <h2 align="center"><small>Ride Information</small></h2>
                    <hr>
                    <br/>
                    <form method="post" action="addCarShare.php">
                        <?php 
                        // Establish a database connection
                        include('functions.php');
                        $conn = connectdb();

                        if ($conn === false) {
                            // Handle connection error
                            die("ERROR: Could not connect. " . mysqli_connect_error());
                        }

                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $query = "SELECT * FROM offers WHERE id=".$id;
                            $result = mysqli_query($conn, $query);
                            if (!$result || mysqli_num_rows($result) == 0) {
                                echo("<p align='center'>Looks like you have entered an URL you shouldn't have! Please go back to the previous page</p>");
                            } else {
                                $row = mysqli_fetch_array($result);
                                $uid = $row['uid'];
                                $from = $row['from'];
                                $to = $row['to'];
                                $uptime = $row['uptime'];
                                $vacancy = $row['people'];
                                $price = $row['price'];
                                $ferry = $row['vehicle'];
                                $desc = $row['description'];
                                $cid = $row['id'];
                                ?>
                                <p>
                                    Rider: <a href="<?php echo "profile.php?id=".$uid;?>"><?php echo getName($uid); ?></a> <br/>
                                    Starting Time of Ride: <?php echo $uptime ?> <br/>
                                    Rider's Source: <?php echo $from; ?> <br/>
                                    Rider's Destination: <?php echo $to; ?> <br/>
                                    Available vacancy: <?php echo $vacancy; ?> <br/>
                                    Price per person: INR <?php echo $price; ?> <br/>
                                    Type of vehicle: <?php echo $ferry; ?> <br/>
                                    Brief Description of the car pool: <?php echo $desc; ?> <br/>
                                </p>
                                <br/>
                                <?php 
                                $time = date("Y-m-d H:i:s");
                                if ($uptime > $time) { ?>
                                    <h2><small>Request for this car</small></h2>
                                    <input type="hidden" id="formfrom" name="from" />
                                    <input type="hidden" id="formto" name="to" />
                                    <input type="hidden" name="uid" value="<?php echo getUserid(); ?>" />
                                    <input type="hidden" name="uid" value="<?php echo $uid; ?>" />
                                    
                                    <!-- New field for price negotiation -->
                                    <label for="negotiation">Price Negotiation (INR):</label>
                                    <input type="number" id="negotiation" name="negotiation" min="0" step="any" placeholder="Enter your offer" required />
                                    
                                    <div class="btn-group">
                                        <button id="from" class="btn dropdown-toggle" data-toggle="dropdown">From <span class="caret"></span></button>
                                        <ul class="dropdown-menu from">
                                            <?php
                                            $q = "SELECT place from route WHERE uid=".$uid;
                                            $re = mysqli_query($conn, $q);
                                            while ($row = mysqli_fetch_array($re)) {
                                                echo "<li><a href='#'>".$row['place']."</a></li>";
                                            }
                                            ?>
                                        </ul>
                                    </div><!-- /btn-group -->
                                    <div class="btn-group">
                                        <button id="to" class="btn dropdown-toggle" data-toggle="dropdown">To <span class="caret"></span></button>
                                        <ul class="dropdown-menu to">
                                            <?php
                                            $q = "SELECT place from route WHERE uid=".$uid;
                                            $re = mysqli_query($conn, $q);
                                            while ($row = mysqli_fetch_array($re)) {
                                                echo "<li><a href='#'>".$row['place']."</a></li>";
                                            }
                                            ?>
                                        </ul>
                                    </div><!-- /btn-group -->
                                    <br/>
                                    Number of seats to be booked: 
                                    <select name="seats">
                                    <?php 
                                        for ($i=1; $i<=$vacancy; $i++) {
                                            echo "<option>".$i."</option>";
                                        }
                                        ?>
                                    </select>
                                    <input class="btn" type="submit" name="submit" value="Request"/>
                                <?php } else {
                                    echo"<h3><small> The carpool has been archived, you can get to know more about this carpool by contacting the rider </small></h3>";
                                }
                            }
                        } else {
                            echo("<p align='center'>Looks like you have entered an URL you shouldn't have! Please go back to the previous page</p>");
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
