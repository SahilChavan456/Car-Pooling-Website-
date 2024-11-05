<?php
// Include necessary files
require_once('functions.php');

// Check if the user is already logged in
if(loggedin()) {
    header("Location: index.php");
    exit;
} elseif(isset($_POST['action'])) {
    $conn = connectdb();

    // Handle registration action
    if($_POST['action'] == 'register') {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $number = $conn->real_escape_string($_POST['contactno']);
        $gender = $conn->real_escape_string($_POST['sex']);
        $desc = $conn->real_escape_string($_POST['description']);
        $sex = $gender == "female" ? "F" : "M";

        // Check if email already exists
        $query = "SELECT random, hash FROM users WHERE email='$email'";
        $result = $conn->query($query);

        if($result->num_rows != 0) {
            header("Location: register.php?exists=1");
            exit;
        } else {
            $random = randomNum(5);
            $hash = crypt($_POST['password'], $random);
            $sql = "INSERT INTO `users` (`name`, `random`, `hash`, `email`, `gender`, `contactno`, `description`)
                    VALUES ('$name', '$random', '$hash', '$email', '$sex', '$number', '$desc')";

            if($conn->query($sql) === TRUE) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

// Include header and CSS files
include("header.php");

?>
<link href="css/register.css" rel="stylesheet">

<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form method="post" action="register.php" class="register">
                <input type="hidden" name="action" value="register"/>

                <div class="register__field">
                    <input type="text" name="name" class="register__input" placeholder="Name" required/>
                </div>

                <div class="register__field">
                    <input type="password" name="password" class="register__input" placeholder="Password" required/>
                </div>

                <div class="register__field">
                    <input type="email" name="email" class="register__input" placeholder="Email" required/>
                </div>

                <div class="register__field">
                    <span class="add-on">+91</span>
                    <input class="register__input" type="number" name="contactno" placeholder="Contact Number" required>
                </div>

                <div class="register__field">
                    <div>Gender:</div>
                    <label class="radio">
                        <input type="radio" name="sex" value="male">Male
                    </label>
                    <label class="radio">
                        <input type="radio" name="sex" value="female">Female
                    </label>
                </div>

                <div class="register__field">
                    <textarea rows="3" name="description" class="register__input" placeholder="Your description Onlyt for car owner"></textarea>
                </div>

                <button class="button register__submit" type="submit">
                    <span class="button__text">Register Now</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
            </form>
        </div>
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape3"></span>      
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>
        </div>
    </div>
</div>
