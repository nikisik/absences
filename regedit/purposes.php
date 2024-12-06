<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create DB record</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body>

    <?php

    $rows = $conn->query("SELECT * FROM `purpose`");
    foreach ($rows as $row) {
        echo '';
        echo ($row['name']) . '<br>';
    }


    ?>




</body>