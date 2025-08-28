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
        <a href='/regedit/teachers/'>Редактировать учителей</a>
        <a href='/regedit/students/'>Редактировать учеников</a>
        <a href='/regedit/purposes/'>Редактировать причины</a>
        <a href='/regedit/grades/'>Редактировать классы</a>
        <a class="active" href='/regedit/perms/'>Права</a>
        <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>
    </div>





    <!-- <div id="flexbox"> -->
        <?php
        $teachers = $conn->query("SELECT * FROM `teachers` ORDER BY `name`");

        foreach ($teachers as $teacher) {
            $id = $teacher['id'];
            if (in_array($id, ADMINID)){ 
                continue; 
            }
            $name = $teacher['name'];
            $login = $teacher['login'];
            $graderows = $conn->query("SELECT `grade`,`litera` FROM `grades` WHERE `id` = '" . $teacher['gradeid'] . "'")->fetch_assoc();
            $grade = $graderows['grade'] ?? '';
            $litera = $graderows['litera'] ?? '';
            echo "
                <div class='defaultbox' style='white-space: nowrap;'>
                    <form method='POST' action='/regedit/teachers/teachers.php'>
                        <input type='hidden' name='id' value='$id' require>
                        $name <a href='/regedit/teachers/teachers.php?deleteid=$id'style='float:right;'><i class='bx bx-x'></i></a><br>
                        <!-- <input type='text' name='newname' value='$name' class='change' require><br> --!>
                        $login<br>
                        $grade$litera
                    </form>
                </div>
                ";
        }

        ?>
    <!-- </div> -->

    <?php
    userinfo();
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>

</body>

</html>