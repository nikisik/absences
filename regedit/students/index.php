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

    <form method="POST" action="/regedit/students/students.php" style='height:400px;float:right;'>
        <input type="text" name="name" placeholder="Имя ученика" require autofocus><br>
        <?php

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


    <div id="flexbox">
        <?
        $rows = $conn->query("SELECT * FROM `students` ORDER BY `gradeid`,`name`");
        foreach ($rows as $row) {
            $studentname = $row['name'];
            $studentid = $row['id'];

            echo "<div style='margin: 5px; border: 1px solid rgba(154, 23, 216, .4);border-radius:5px;width:170px;'>";
            echo "<form class='' action='./editstudents.php' method='POST' >";
            echo $studentname;
            echo "<input type='hidden' name='id' value='$studentid'><br>";
            echo "<input type='text' name='newname' style='margin:5px 2px; height: 30px;width:140px;' value='$studentname'><br>";
            echo "<button type='submit' style='height: 30px;margin: 5px 2px; width:140px;'> Изменить имя </button>";
            echo "</form></div>";
        }
        ?>
    </div>


</body>

</html>