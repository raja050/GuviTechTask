<?php

// connect to Redis server
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

function save_user_info($username, $password) {
    global $redis;
    $user_info = array(
        'username' => $username,
        'password' => $password
    );
    $redis->hmset($username, $user_info);
}

// establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "signup";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// get the username and password from the form
$name = $_POST['username'];
$pass_word = $_POST['password'];

save_user_info($name, $pass_word);


// prepare a query to check if the username and password are correct
$stmt = mysqli_prepare($conn, "SELECT * FROM userdetails WHERE username=? AND password=?");
mysqli_stmt_bind_param($stmt, "ss", $name, $pass_word);
mysqli_stmt_execute($stmt);

// fetch the result of the query
$result = mysqli_stmt_get_result($stmt);

// check if the result has any rows
if (mysqli_num_rows($result) > 0) {
    echo "success";
} else {
    echo "Invalid username or password";
}

// close the database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);


?>
