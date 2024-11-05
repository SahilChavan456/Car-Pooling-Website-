<?php
/*
 * Common functions used throughout Codejudge
 */
session_start();

// Function to connect to the database using MySQLi
function connectdb() {
    include('dbinfo.php');
    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to generate a random number.
function randomNum($length){
  $rangeMin = pow(36, $length-1);
  $rangeMax = pow(36, $length)-1;
  $base10Rand = mt_rand($rangeMin, $rangeMax);
  $newRand = base_convert($base10Rand, 10, 36);
  return $newRand;
}

// Function to check if any user is logged in
function loggedin() {
    return isset($_SESSION['username']);
}

// Function to get the user ID
function getUserId() {
  if (isset($_SESSION['username'])) {
      $emailid = $_SESSION['username'];
      $conn = connectdb();
      $query = "SELECT uid FROM users WHERE email = '$emailid'";
      $result = $conn->query($query);
      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          return $row['uid'];
      } else {
          return false;
      }
  } else {
      return false;
  }
}

// Function to get the user's name
function name() {
    $emailid = $_SESSION['username'];
    $conn = connectdb();
    $query = "SELECT name FROM users WHERE email = '$emailid'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['name'];
    } else {
        return false;
    }
}

// Function to get the name based on user ID
function getName($uid) {
    $conn = connectdb();
    $query = "SELECT name FROM users WHERE uid = $uid";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['name'];
    } else {
        return false;
    }
}
?>
