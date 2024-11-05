<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('functions.php');

if(loggedin()) {
    header("Location: index.php");
    exit;
} elseif(isset($_POST['action'])) {
    $conn = connectdb();

    if($_POST['action'] == 'login') {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        if(trim($_POST['email']) == "" || trim($_POST['password']) == "") {
            header("Location: login.php?nerror=1");
            exit;
        } else {
            $query = "SELECT random, hash FROM users WHERE email='$email'";
            $result = $conn->query($query);

            if($result->num_rows == 1) {
                $fields = $result->fetch_assoc();
                $currhash = crypt($password, $fields['random']);

                if($currhash == $fields['hash']) {
                    $_SESSION['username'] = $email;
                    header("Location: index.php");
                    exit;
                } else {
                    header("Location: login.php?error=1");
                    exit;
                }
            } else {
                header("Location: login.php?error=1");
                exit;
            }
        }
    } elseif($_POST['action'] == 'ldaplogin') {
        // Your LDAP login logic here
    }
}
?>

<?php include("header.php"); ?>
<link href="css/logincss.css" rel="stylesheet">

<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form method="post" action="login.php" class="login">
                <input type="hidden" name="action" value="login" />
                
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" name="email" class="login__input" placeholder="User name / Email">
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" name="password" class="login__input" placeholder="Password">
                </div>
                <button class="button login__submit" type="submit">
                    <span class="button__text">Log In Now</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
            </form>

            <div class="register">
                <h3>Don't have an account?</h3>
                <a href="register.php" class="button register__submit">
                    <span class="button__text">Register</span>
                    <i class="button__icon fas fa-user-plus"></i>
                </a>
            </div>
        </div>
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape3"></span>      
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>
        </div>        
    </div>
</div>
