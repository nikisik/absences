<?php

const DB_HOST = '192.168.1.123';
const DB_PORT = '3306';
const DB_NAME = 'absences';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
// require_once 'dbpassword.php';





const ADMINID = array(999);
const DEBUGMODE = true;

if (!DEBUGMODE) {
    error_reporting(0);
}


global $conn;
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8mb4");
// else {
//     echo 'connected succesfully';
// }
// function findUser($email)
// {
//     global $conn;
//     $result = $conn->query("SELECT `id` FROM `users` WHERE `email`='$email';")->fetch_assoc();
//     if ($result !== null) {
//         echo 'true';
//         return true;
//     } else {
//         echo 'false';
//         return false;
//     }
// }
// findUser('q@q.q');



// var_dump(findUser('q@q.qw'));

// var_dump($conn->query("SELECT `password` FROM `users` WHERE `id`=6;")->fetch_assoc()['password']);
