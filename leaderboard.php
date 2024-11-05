<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/datetimepicker.css">
=

    <!-- Custom CSS for background image, table box, and scroll bar -->
    <style>
        /* Set the background image on the body */
        body {
            background-image: url('img/new.jpg'); /* Replace 'path/to/your/image.jpg' with the path to your image */
            background-size: cover; /* Cover the entire page */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Avoid repeating the image */
            margin: 0;
            padding: 0;
        }

        /* Styling the table box */
        .table-box {
            background-color: white; /* Box background color */
            padding: 20px; /* Padding inside the box */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow */
            max-height: 250px; /* Set the height to accommodate 5 rows (each row typically 50px) */
            overflow-y: auto; /* Enable vertical scrolling */
            margin-bottom: 20px; /* Bottom margin */
        }

        /* Table styles */
        #userlist tr {
            height: 50px; /* Height of each row to fit within the box height */
        }

        /* Style for blurred content (if any) */
        .blurred-content {
            filter: blur(0); /* No blur effect */
        }
    </style>
</head>

<body>
    <!-- Include header -->
    <?php
    include("header.php");
    include("functions.php");

    $conn = connectdb(); // Establish a database connection
    ?>

    <!-- Main container -->
    <div class="container">
        <!-- Display success or error messages -->
        <?php
        if (isset($_GET['share'])) {
            echo '<div class="alert alert-info">Your Ride was successfully added! You can edit it from your profile.</div>';
        } elseif (isset($_GET['nerror'])) {
            echo '<div class="alert alert-error">Please enter all the details asked before you can continue!</div>';
        }
        ?>

        <!-- Include the menu -->
        <?php include('menu.php'); ?>

        <!-- Main content (blurred) -->
        <div class="row-fluid" id="main-content">
            <div class="span3"></div>
            <div class="span6">
                <!-- Content that should be blurred goes here -->
            </div>
            <div class="span3"></div>
        </div>

        <!-- Leaderboard section (not blurred) -->
        <div class="row-fluid" id="leaderboard">
            <div class="span3"></div>
            <div class="span6">
                <!-- Wrap the table in a box -->
                <div class="table-box">
                    <?php
                    // Query and display leaderboard
                    $query = "SELECT * FROM users ORDER BY credits DESC";
                    $result = $conn->query($query);

                    if ($result) {
                        echo '<table id="userlist" class="table table-hover">
                            <thead><tr><th>Id</th><th>Name</th><th>Credits</th></tr></thead>
                            <tbody>';
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr><td>' . $row['uid'] . '</td><td>' . $row['name'] . '</td><td>' . $row['credits'] . '</td></tr>';
                        }
                        echo '</tbody></table>';
                        $result->free();
                    } else {
                        echo "Error: " . $conn->error;
                    }
                    ?>
                </div> <!-- End of table box -->
            </div>
            <div class="span3"></div>
        </div>
    </div>



    <!-- JavaScript files -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/datetimepicker.js"></script>
    <script>
        $('td:nth-child(1), th:nth-child(1)').hide();
        $('#userlist').find('tr').click(function() {
            var row = $(this).find('td:first').text();
            window.location.href = "profile.php?uid=" + row;
        });
    </script>
</body>

</html>
