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
    <link href='/assets/topbar.css' rel='stylesheet'>
</head>



<body>
    <?= makeTopbar(PAGES, '/regedit/grades/', isadmin());?>

    <div style='float:right;'>
        <div class='defaultbox'>
            <form action="/regedit/grades/grades.php" method="POST">
                <h3>Добавить класс</h3>
                <input type="text" name='addgrade' placeholder=" e.g.: 1А, max. 3 symb." style="margin: 0;" <?php echo ($_SESSION['addedgrade'] ?? false) ? 'autofocus' : '';
                                                                                                            unset($_SESSION['addedgrade']); ?>>
                <br>
                <input type="submit" value="Добавить">
            </form>
        </div>
        <div class='defaultbox'>
            <h3 style='margin:0px;'>Сдвиг по классам</h3>
            <a href='grades.php?leftshift=1' style='text-decoration:none;color:cadetblue;'><==</a><a style='float:right;text-decoration:none;color:cadetblue;' href='grades.php?rightshift=1'>==></a><br>
        </div>
    </div>
    <!-- <h2 style="width: 100%;">Изменить классы</h2> -->
    <div id="flexbox">
        <?php
        $rows = $conn->query("SELECT `id`,`grade`,`litera` FROM `grades` ORDER BY `grade`,`litera`");
        foreach ($rows as  $row) {
            $grade = $row['grade'];
            $litera = $row['litera'];
            // if ($gradename == '00') {
            //     continue;
            // }
            $id = $row['id'];
            $quantity = $conn->query("SELECT `id` FROM `students` WHERE `gradeid` = '$id'")->num_rows;
            echo "
            <div class='defaultbox'>
                <form action='/regedit/grades/grades.php' method='POST'>
                <input type='hidden' value='$id' name='id'>
                    <b title='Класс'>$grade$litera</b> : <label title='Количество учеников'>$quantity</label><br>
                    <input type='text' name='newgrade' style='width:19px; margin:0; height:25px; color:#555;' value='$grade' title='Новый номер класса'>
                    <input type='text' name='newlitera' style='width:19px; margin:0; height:25px; color:#555;' value='$litera' title='Новыая литера класса'><br>
                    <input type='submit' value='Изм.'style='width:50px; margin:5px 0px 0px; height:25px;' title='Изменить название класса'>
                </form>

                <form action='/regedit/grades/grades.php' method='POST'>
                    <input type='hidden' value='$id' name='deleteid'>
                    <input type='submit' value='Удал.'style='width:50px; margin:5px 0px 0px; height:25px;'id='deletebutton' title='Удалить класс. Возможно только тогда, когда в классе нет ни одного ученика'>
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
    </form>
    </div>
</body>

</html>