<?php

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
function save_user_info($name, $email, $pass_word) {
    global $redis;
    $user_info = array(
        'username' => $name,
        'email' => $email,
        'password' => $pass_word
    );
    $redis->hmset($name, $user_info);
}

// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "signup";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get inputs from the sign-up form
$name = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

save_user_info($name, $email, $pass_word);

// Prepare and execute a MySQL query to insert the inputs into the database
$stmt = $conn->prepare("INSERT INTO userdetails (username, email,password ) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $pass_word);
$stmt->execute();

// Close the database connection
$stmt->close();
$conn->close();

// Redirect the user to a thank-you page
header("Location: http://localhost/Last/login.html");
exit();

?>