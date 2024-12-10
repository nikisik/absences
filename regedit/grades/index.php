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
        <a href='/regedit/students/'>Добавить ученика</a>
        <a href='/regedit/purposes/'>Редактировать причины</a>
        <a class="active" href='/regedit/grades/'>Редактировать классы</a>
        <!-- <a href='/regedit/editstudents/'>Редактировать учеников</a> -->
        <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>
    </div>

    <div style='height:400px;float:right;'>
        <div class='defaultbox' style='float:right;'>
            <form action="/regedit/grades/grades.php" method="POST">
                <h3>Добавить класс</h3>
                <input type="text" name='addgrade' placeholder=" ex.: 1А, max. 3 symb." style="margin: 0;">
                <br>
                <input type="submit">
            </form>
        </div>
    </div>
    <h3 style="width: 100%;">Изменить классы</h3><br>
    <div id="flexbox">
        <?php
        $rows = $conn->query("SELECT * FROM `grades` ORDER BY `gradename`");
        foreach ($rows as  $row) {
            $gradename = $row['gradename'];
            if ($gradename == '00') {
                continue;
            }
            $id = $row['id'];
            echo "
            <div class='defaultbox'>
                <form action='/regedit/grades/grades.php' method='POST'>
                <input type='hidden' value='$id'>
                    $gradename<br>
                    <input type='text' name='newgradename' style='width:50px; margin:0; height:25px; color:#555;' value='$gradename'><br>
                    <input type='submit' value='Изм.'style='width:50px; margin:5px 0px 0px; height:25px;' title='Изменить название класса'>
                </form>

                <form action='/regedit/grades/grades.php' method='POST'>
                    <input type='hidden' value='$id'>
                    <input type='submit' value='Удал.'style='width:50px; margin:5px 0px 0px; height:25px;' title='Удалить класс. Возможно только тогда, когда ни одному ученику не присовен id этого класса'>
                </form>
            </div>";
        }

        ?>
    </div>








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