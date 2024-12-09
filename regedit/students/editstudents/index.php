<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();
// adminpage();
// if (!checkAuth()) {
//     redirect('/');
// }

$teacherid = $_SESSION['teacherid'];
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
?>


<!DOCTYPE html>
<html lang="ru" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать учеников</title>
    <link rel="stylesheet" href="../../assets/home.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body style='margin:0;max-width:100vd;'>


    <div class="topnav">
        <a href="../">Пропуски</a>
        <a href='../statistic/'>Статистика</a>
        <a class="active" href="">Редактировать учеников</a>
        <a href='/../../regedit/students.php'>Добавить ученика</a>
        <a href='/../../regedit/teachers.php'>Добавить учителя</a>


        <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>
    </div>


    <div class='flexbox'>
        <?php
        $rows = $conn->query("SELECT `id`, `name`, `teacherid`, `gradeid` FROM `students` WHERE `teacherid` = $teacherid ORDER BY `name`");
        if (isadmin()) {
            $rows = $conn->query("SELECT `id`, `name`, `teacherid`, `gradeid` FROM `students` ORDER BY `gradeid`,`name`");
        }
        foreach ($rows as $row) {

            $gradeid = $row['gradeid'];
            $gradename = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$gradeid';")->fetch_assoc()['gradename'];
            $studentid = $row['id'];
            $studentname = $row['name'];
            $date = date('Y.m.d');
            $ismissingtoday = isset($conn->query("SELECT `date` FROM `missings` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['date']);
            if ($ismissingtoday) {
                $missing = 'Н';
                $purposeid = $conn->query("SELECT `purposeid` FROM `missings` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['purposeid'];
                $purpose = $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid'")->fetch_assoc()['name'];
            } else {
                $missing = ''; // +++++++
                $purpose = '';
            }

            echo "<div style='margin: 10px; border-color: 1px solid #d3d3d3;'>";
            echo "<form class='' action='./editstudents.php' method='POST' >";
            echo $studentname;
            echo "<input type='hidden' name='studentid' value='$studentid'><br>";
            echo "<input type='text' name='newname' value='$studentname'><br>";
            echo "<button type='submit'> Изменить имя </button>";
            echo "</form></div>";
        }

        ?>
    </div>
    <!-- </table> -->
    <?= userinfo(); ?>
</body>

</html>