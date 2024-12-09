<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();


// if (!isset($_SESSION['teacherid'])) {
//     $_SESSION['teacherid'] = 0;
// }
// if ($_SESSION['teacherid'] == 0) {
//     redirect('../');
// }

?>



<!DOCTYPE html>
<html lang="ru">

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
        <a class="active" href='/regedit/teachers/'>Добавить учителя</a>
        <a href='/regedit/students/'>Добавить ученика</a>
        <a href='/regedit/purposes/'>Редактировать причины</a>
        <a href='/regedit/grades/'>Редактировать классы</a>
        <!-- <a href='/regedit/editstudents/'>Редактировать учеников</a> -->
        <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>
    </div>


    <div id="flexbox">
        <form method="POST" action="./teachers.php">
            <input type="text" name="login" placeholder="login" require><br>
            <input type="text" name="password" placeholder="password" require><br>
            <input type="text" name="name" placeholder="name" require><br>
            <!-- <input type="text" name="gradeid" placeholder="gradeid" require><br>-->
            <?php
            $rows = $conn->query("SELECT `id`, `gradename` FROM `grades` ORDER BY `gradename`");
            echo "<select name='gradeid'>";
            foreach ($rows as $row) {
                $gradename = $row['gradename'];
                if ($gradename == '00') {
                    continue;
                }
                $id = $row['id'];
                echo "<option value='$id'>$gradename</option>";
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
            ?>
        </form>
    </div>
</body>

</html>