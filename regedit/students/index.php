<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();
// if (!isadmin()) {
//     redirect('/src/actions/logout.php');
// }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create DB record</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='/assets/regedit.css' rel='stylesheet'>

</head>



<body>
    <!-- <button id="back" style="position:absolute; " onclick="window.location='../home/';">Назад</button> -->
    <div class="topnav">
        <a href="/home/">Пропуски</a>
        <a href='/home/statistic/'>Статистика</a>
        <a href='/regedit/teachers/'>Добавить учителя</a>
        <a class="active" href='/regedit/students/'>Добавить ученика</a>
        <a href='/regedit/purposes/'>Редактировать причины</a>
        <a href='/regedit/grades/'>Редактировать классы</a>
        <!-- <a href='/regedit/editstudents/'>Редактировать учеников</a> -->
        <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>
    </div>

    <div id="flexbox">
        <form method="POST" action="/regedit/students/students.php">
            <input type="text" name="name" placeholder="Имя ученика" require autofocus><br>
            <?php


            // $rows = $conn->query("SELECT `id`, `name`,`gradeid` FROM `teachers` ORDER BY `gradeid` DESC");
            // echo "<select name='teacherid'>";
            // foreach ($rows as $row) {
            //     $teachername = $row['name'];
            //     $teacherid = $row['id'];
            //     $gradeid = $row['gradeid'];
            //     // var_dump($row['gradeid']);
            //     // var_dump($_SESSION['getgrade']);
            //     echo "<option value='$teacherid' ";
            //     if ($teacherid == ($_SESSION['getteacherid'] ?? 0)) {
            //         echo 'selected';
            //     }
            //     echo ">$teachername</option>";
            // }
            // echo "</select><br>";




            $rows = $conn->query("SELECT `id`, `gradename` FROM `grades` ORDER BY `gradename`");
            echo "<select name='gradeid'>";

            foreach ($rows as $row) {
                $gradename = $row['gradename'];
                if ($gradename == '00') {
                    continue;
                }
                $id = $row['id'];
                echo "<option value='$id' ";
                if (($_SESSION['getgrade'] ?? null) == $gradename) {
                    echo "selected";
                }
                echo ">$gradename</option>";
            }
            echo "</select><br>";
            ?>
            <button type="submit">Сделать запись</button>

            <?php //DEBUG

            userinfo();
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }

            // var_dump();

            ?>
        </form>
    </div>
</body>

</html>