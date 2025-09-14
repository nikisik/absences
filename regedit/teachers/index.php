<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();


// if (!isset($_SESSION['teacherid'])) {
//     $_SESSION['teacherid'] = 0;
// }
// if ($_SESSION['teacherid'] == 0) {
//     redirect('../');
// }
$addressicon = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 640 470"><path d="M544 144C552.8 144 560 151.2 560 160L560 480C560 488.8 552.8 496 544 496L96 496C87.2 496 80 488.8 80 480L80 160C80 151.2 87.2 144 96 144L544 144zM96 96C60.7 96 32 124.7 32 160L32 480C32 515.3 60.7 544 96 544L544 544C579.3 544 608 515.3 608 480L608 160C608 124.7 579.3 96 544 96L96 96zM240 312C270.9 312 296 286.9 296 256C296 225.1 270.9 200 240 200C209.1 200 184 225.1 184 256C184 286.9 209.1 312 240 312zM208 352C163.8 352 128 387.8 128 432C128 440.8 135.2 448 144 448L336 448C344.8 448 352 440.8 352 432C352 387.8 316.2 352 272 352L208 352zM408 208C394.7 208 384 218.7 384 232C384 245.3 394.7 256 408 256L488 256C501.3 256 512 245.3 512 232C512 218.7 501.3 208 488 208L408 208zM408 304C394.7 304 384 314.7 384 328C384 341.3 394.7 352 408 352L488 352C501.3 352 512 341.3 512 328C512 314.7 501.3 304 488 304L408 304z"/></svg>';
$keyicon =     '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 600 400"><path d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0 160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-40 40 0c13.3 0 24-10.7 24-24l0-40 40 0c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>';


?>



<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create DB record</title>
    <link href='/assets/teachers.css' rel='stylesheet'>
    <link href='/assets/topbar.css' rel='stylesheet'>


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

  <?= makeTopbar(PAGES, '/regedit/teachers/', isadmin());?>


    <div style='float:right;'class="defaultbox">
        <form method="POST" action="./teachers.php">
            <h3 style='margin-bottom:0;'>Добавить учителя:</h3>
            <input type="text" name="name" placeholder="ФИО" required><br>
            <input type="text" name="login" placeholder="Логин" required><br>
            <input type="text" name="password" placeholder="Пароль" required><br>
            <!-- <input type="text" name="gradeid" placeholder="gradeid" required><br> -->
            <?php
            $grades = $conn->query("SELECT `id`,`grade`,`litera` FROM `grades` ORDER BY `grade`,`litera`");
            echo "<select name='gradeid'>";
            foreach ($grades as $grade) {
                $gradeid = $grade['id'];
                if ($conn->query("SELECT `id` FROM `perms` WHERE `gradeid` = '$gradeid' AND `main` = 1")->num_rows > 0) {
                    continue;
                }
                $gradename = $grade['grade'].$grade['litera'];
                echo "<option value='$gradeid'>$gradename</option>";
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
            $id = $teacher['id'];
            if (in_array($id, ADMINID)){ 
                continue; 
            }
            $name = $teacher['name'];
            $login = $teacher['login'];

            $gradeid = $conn->query("SELECT `gradeid` FROM `perms` WHERE `main` = 1 AND `teacherid` = '$id'")->fetch_assoc()['gradeid'] ?? '';
            $gradename = $conn->query("SELECT CONCAT(`grade`,`litera`) AS `gradename` FROM `grades` WHERE `id` = '$gradeid'")->fetch_assoc()['gradename'];

            $gradeid = $conn->query("SELECT `gradeid` FROM `perms` WHERE `main` = 0 AND `teacherid` = '$id'")->fetch_assoc()['gradeid'] ?? '';
            $secgrades = '';
            foreach ($conn->query("SELECT CONCAT(`grade`,`litera`) AS `gradename` FROM `grades` WHERE `id` = '$gradeid'")->fetch_all() as $sec){
                $secgrades = $secgrades.' '.$sec[0];
            }
            // $grade = $graderows['grade'] ?? '';
            // $litera = $graderows['litera'] ?? '';
            // $gradename = $grade.$litera;

            echo "
                <div class='defaultbox' style='white-space: nowrap;'>
                    <form method='POST' action='/regedit/teachers/teachers.php'>
                        <input type='hidden' name='id' value='$id' require>
                        $addressicon $name <a href='/regedit/teachers/teachers.php?deleteid=$id'style='float:right;'><i class='bx bx-x'></i></a><br>
                        <!-- <input type='text' name='newname' value='$name' class='change' require><br> --!>
                        $keyicon $login<br>
                        <b>$gradename</b> $secgrades
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