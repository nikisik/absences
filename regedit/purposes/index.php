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
    <link href='/assets/regedit.css' rel='stylesheet'>
    <link href='/assets/topbar.css' rel='stylesheet'>
</head>

<body>
    <?= makeTopbar(PAGES, '/regedit/purposes/', isadmin());?>

    <div style='height:400px;float:right;'> <!-- Добавить причину -->
        <div class='defaultbox' style='float:right;'>
            <form action="/regedit/purposes/purposes.php" method="POST">
                <h3>Добавить причину</h3>
                <input type="text" name='addpurpose' placeholder="e.g.: конкурсы,болезни" style="margin: 0;">
                <br>
                <input type="submit" value="Добавить">
            </form>
        </div>
    </div> <!-- Добавить причину -->

    <!-- <h2 style="width: 100%;">Изменить причины</h2> -->
    <div id="flexbox">
        <?php


        $rows = $conn->query("SELECT * FROM `purpose` ORDER BY `id`");
        foreach ($rows as  $row) {
            $gradename = $row['name'];
            $id = $row['id'];
            $isusedanywhere = isset($conn->query("SELECT `id` FROM `passes` WHERE `purposeid` = '$id'")->fetch_assoc()['id']);
            echo "
            <div class='defaultbox' style='width:190px;'>
                <form action='/regedit/purposes/purposes.php' method='POST'>
                <input type='hidden' value='$id' name='id'>
                    $gradename
                    <!--$isusedanywhere--!>
                    <input id='urodskiipadding' type='text' name='newpurposename' style='color:#777;' class='change' value='$gradename'>
                    <input type='submit' value='Изменить причину' class='change' title='Изменить причину.'>
                </form>

                <form action='/regedit/purposes/purposes.php' method='POST'>
                    <input type='hidden' value='$id' name='deleteid'>
                    <input type='submit' value='Удалить причину' class='change' id='deletebutton' title='Удалить причину. Возможно только тогда, когда нет ни одного пропуска с этой причиной'>
                </form>
            </div>";
        }

        ?>

    </div>

    <?php

    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    userinfo();

    ?>

</body>