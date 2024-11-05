
<?php
require_once('functions.php');

// Check if the user is logged in
if (!loggedin()) {
    header("Location: login.php");
    exit;
}

include('header.php');
$conn = connectdb();

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

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background-image: url('img/new.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .main-content {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
        }

        table {
            background-color: white;
            border-radius: 10px;
        }

        th {
            background-color: #f8f9fa;
        }

        td {
            color: #212529;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        if (isset($_GET['share'])) {
            echo("<div class=\"alert alert-info\">\nYour Ride was successfully added! You can edit it from your profile\n</div>");
        } else if (isset($_GET['nerror'])) {
            echo("<div class=\"alert alert-error\">\nPlease enter all the details asked before you can continue!\n</div>");
        }
        ?>

        <?php include('menu.php'); ?>

        <div class="row-fluid main-content">
            <div class="span1"></div>
            <div class="span10">
                <?php
                $userId = getUserid();
                $query = 'SELECT * FROM notifications WHERE receiver = ? ORDER BY timestamp DESC';
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Car Pool Details</th>
                            <th>Notification Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            $type = $row["type"];
                            echo '<tr>';
                            echo '<td>' . $row["timestamp"] . '</td>';
                            if ($type == "1") {
                                // Request from sender to approve his car pool request
                                $slno = $row["slno"];
                                echo '<td>' . $row["uid"] . '</td>';
                                echo '<td>Approve Request</td>';
                                $status = $row["status"];
                                if ($status == "Approved") {
                                    echo '<td><button class="btn" disabled>Approved</button></td>';
                                } else if ($status == "Declined") {
                                    echo '<td><button class="btn" disabled>Declined</button></td>';
                                } else {
                                    $funcAgrsApp = "ApproveRequest(" . $slno . ",1)";
                                    $funcAgrsDec = "ApproveRequest(" . $slno . ",0)";
                                    echo '<td><button class="btn" onclick="' . $funcAgrsApp . '">Approve</button> 
                                        <button onclick="' . $funcAgrsDec . '" class="btn">Decline</button></td>';
                                }
                                echo '</tr>';
                            } elseif ($type == "2") {
                                echo '<td>' . $row["uid"] . '</td>';
                                echo '<td>Feedback</td>';
                                $status = $row["status"];
                                if ($status != "") {
                                    echo '<td>' . $status . '/5</td>';
                                } else {
                                    echo '<td> 
                                        <div class="btn-group">
                                            <button id="fartDropDown" class="btn dropdown-toggle" data-toggle="dropdown">Rating <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li><a href="#">4</a></li>
                                                <li><a href="#">5</a></li>
                                            </ul>
                                        </div>
                                        <button class="btn" onclick="RateRequest(' . $row["slno"] . ')">Submit</button></td>';
                                }
                                echo '</tr>';
                            } elseif ($type == "3") {
                                echo '<td>' . $row["uid"] . '</td>';
                                echo '<td>Request Status</td>';
                                if ($row["status"] == "Approved") {
                                    echo '<td>Approved, Enjoy the Ride</td>';
                                } elseif ($row["status"] == "Declined") {
                                    echo '<td>Declined, :-(</td>';
                                } else {
                                    echo '<td>Alert: Some error occurred</td>';
                                }
                                echo '</tr>';

                            } elseif ($type == "4") {
                                echo '<td>' . $row["uid"] . '</td>';
                                echo '<td>Request Status</td>';
                                echo '<td>Still pending with the rider, Please Come again later</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="span1"></div>
        </div>
    </div>

    <!-- JavaScript files -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
<script>
    $(".dropdown-menu li a").click(function() {
        var selText = $(this).text();
        $('#fartDropDown').html(selText);
    });

    function RateRequest(slno) {
        var rating = $('#fartDropDown').html();
        $.post("updateDBNotifications.php", {
                type: "2",
                serialNo: slno,
                rating: rating
            })
            .done(function(data) {
                location.reload();
            });
    }

    function ApproveRequest(slno, stat) {
        var status = (stat == "0") ? "Declined" : "Approved";
        $.post("updateDBNotifications.php", {
                type: "1",
                serialNo: slno,
                stat: status
            })
            .done(function(data) {
                location.reload();
            });
    }

    $('.dropdown-toggle').dropdown();
</script>
</body>

</html>
