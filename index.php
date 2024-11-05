<?php
/*
 * The landing page that lists all the problem
 */
require_once('functions.php');
if(!loggedin()) {
  header("Location: login.php");
  exit; // Terminate script execution
} else {
  connectdb();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home Page</title>
    <!-- Add your other meta tags and scripts here -->
    
    <!-- Include necessary stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- CSS styles to add background image and black transparent background around section-text -->
    <style>
        /* Set a background image for the entire body */
        body {
            background-image: url('img/new.jpg'); /* Replace with your image URL */
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Optional: keep the background fixed when scrolling */
        }

        /* Add a black transparent background around the section-text */
        .section-text {
            background-color: rgba(0, 0, 0, 0.6); /* 60% opacity black */
            padding: 20px; /* Add padding for spacing */
            border-radius: 5px; /* Optional: rounded corners */
            color: white; /* Optional: change text color to white */
            margin-bottom: 20px; /* Add margin between section-text elements */
        }

        /* Customize the appearance of the buttons */
        .cta-button {
            margin-right: 10px;
            color: white;
            background-color: #007BFF; /* Bootstrap primary color */
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 20px; /* Increase text size */
        }

        /* Hover effect for buttons */
        .cta-button:hover {
            background-color: #0056b3; /* Darker shade of primary color */
        }

        /* Box for "Ready to Join?" text, signup, and login links */
        .join-box {
            background-color: rgba(0, 0, 0, 0.6); /* 60% opacity black */
            padding: 20px; /* Add padding for spacing */
            border-radius: 5px; /* Optional: rounded corners */
            color: white; /* Optional: change text color to white */
            margin: 0 auto; /* Center the box */
            max-width: 90%; /* Cover maximum area within left and right margins */
            text-align: center; /* Center-align text */
            font-size: 22px; /* Increase text size */
        }
    </style>
</head>

<body>
    <!-- Include header -->
    <?php include("header.php"); ?>
    
    <div class="container">
        <!-- Main content goes here -->
        <?php include('menu.php'); ?>

        <div class="row-fluid" id="main-content">
            <div class="span3"></div>
            <div class="span6">
                <!-- Section with photo and text -->
                <section id="features" class="section-with-photo">
                    <div class="section-text">
                        <h2>Why Choose Us?</h2>
                        <p>We believe in providing a seamless carpooling experience...</p>
                        <ul>
                            <li><strong>Reliable Service:</strong> Count on us for punctual and dependable rides.</li>
                            <li><strong>Affordable Travel:</strong> Save money on transportation costs.</li>
                            <li><strong>Community Connection:</strong> Join a community of like-minded individuals.</li>
                        </ul>
                    </div>
                    <div class="section-photo">
                        <!-- Add your photo here -->
                    </div>
                </section>

                <!-- Additional sections -->
                <section id="how-it-works" class="section-with-photo">
                    <div class="section-text">
                        <h2>How It Works</h2>
                        <p>Joining our carpooling service is quick and simple...</p>
                        <ol>
                            <li><strong>Sign Up:</strong> Create a free account.</li>
                            <li><strong>Choose Your Role:</strong> Offer or find rides.</li>
                            <li><strong>Connect and Go:</strong> Plan your rides and start carpooling!</li>
                        </ol>
                    </div>
                    <div class="section-photo">
                        <!-- Add your photo here -->
                    </div>
                </section>

                <!-- Sign-up section -->
                <section id="signup">
                    <div class="join-box">
                        <h2>Ready to Join?</h2>
                        <p>Start saving money and reducing your carbon footprint today.</p>
                        <a href="register.php" class="cta-button">Sign Up Now</a>
                        <a href="login.php" class="cta-button">Login Now</a>
                    </div>
                </section>
            </div>
            <div class="span3"></div>
        </div>
    </div>
    
    <!-- Include footer -->


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
