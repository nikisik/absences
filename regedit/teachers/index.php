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


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- <button id="back" style="position:absolute; " onclick="window.location='../home/';">Назад</button> -->
    <div class="topnav">
        <a href="/home/">Пропуски</a>
        <a href='/home/statistic/'>Статистика</a>
        <a class="active" href='/regedit/teachers/'>Редактировать учителей</a>
        <a href='/regedit/students/'>Редактировать учеников</a>
        <a href='/regedit/purposes/'>Редактировать причины</a>
        <a href='/regedit/grades/'>Редактировать классы</a>
        <!-- <a href='/regedit/editstudents/'>Редактировать учеников</a> -->
        <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>
    </div>


    <div style='height:400px;float:right;'>
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

        </form>
    </div>


    <div id="flexbox">
        <?php
        $teachers = $conn->query("SELECT * FROM `teachers` ORDER BY `name`");

        foreach ($teachers as $teacher) {
            $name = $teacher['name'];
            $login = $teacher['login'];
            $id = $teacher['id'];
            $gradename =  $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '" . $teacher['gradeid'] . "'")->fetch_assoc()['gradename'] ?? '00';
            echo "
                <div class='defaultbox' style='white-space: nowrap;'>
                    <form method='POST' action='/regedit/teachers/teachers.php'>
                        <input type='hidden' name='id' value='$id' require>
                        $name <a href='/regedit/teachers/teachers.php?deleteid=$id'style='float:right;'><i class='bx bx-x'></i></a><br>
                        <!-- <input type='text' name='newname' value='$name' class='change' require><br> --!>
                        $login<br>
                        $gradename
                    </form>
                </div>
                ";
        }

        ?>
    </div>

    <?php
    userinfo();
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>

</body>

</html>