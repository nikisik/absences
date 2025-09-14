<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();
// if (!isadmin()) {
//     redirect('/src/actions/logout.php');
// }
if (isset($_GET['filter'])) {
    $_SESSION['filter'] = htmlspecialchars($_GET['filter'] ?? null);
}
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
    <link href='/assets/filter.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>



<body>
    <?= makeTopbar(PAGES, '/regedit/students/', isadmin());?>

    <form method="POST" action="/regedit/students/students.php" style='height:400px;float:right;' class="defaultbox">
        <input type="text" name="name" placeholder="Имя ученика" require autofocus><br>
        <?php

        $rows = $conn->query("SELECT `id`, CONCAT(`grade`,`litera`) AS `gradename` FROM `grades` ORDER BY `grade`,`litera`");
        echo "<select name='gradeid'>";

        foreach ($rows as $row) {
            $gradename = $row['gradename'];
            // if ($gradename == '00') {
            //     continue;
            // }
            $id = $row['id'];
            echo "<option value='$id' ";
            if (($_SESSION['filter'] ?? null) == $gradename) {
                echo "selected";
            }
            echo ">$gradename</option>";
        }
        echo "</select><br>";
        ?>
        <button type="submit">Сделать запись</button>
        <br>
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
        //filter bar(не могу понять какого черта оно отрисовывается не в табличке но ладно)
        $filter = $_SESSION['filter'] ?? null;
        $gradenames = $conn->query("SELECT CONCAT(`grade`,`litera`) AS `gradename` FROM `grades` ORDER BY `grade`,`litera`")->fetch_all();
        echo '<div class="filter">';
        foreach ($gradenames as $gradename) {
            $gradename = $gradename[0];
            echo "<a style='padding:2px;' " . ($filter == $gradename ? 'class="active"' : '') . "href='./?filter=$gradename'>" . (($gradename != '00') ? $gradename : 'Все') . "</a>";
        }
        echo "</div>";




        if (isset($filter)) {
            $gradeid = $conn->query("SELECT `id` FROM `grades` WHERE CONCAT(`grade`,`litera`) = '$filter'")->fetch_assoc()['id'];
            $rows = $conn->query("SELECT * FROM `students` WHERE `gradeid` = '$gradeid' ORDER BY `gradeid`,`name`");
        } else {
            $rows = $conn->query("SELECT * FROM `students` ORDER BY `gradeid`,`name`");
        }




        foreach ($rows as $row) {
            $studentname = $row['name'];
            $studentid = $row['id'];

            echo "<div class='defaultbox'>";
            echo "<form action='/regedit/students/students.php' method='POST'>";
            echo $studentname . " <a href='/regedit/students/students.php?deleteid=$studentid'style='float:right;'><i class='bx bx-x'></i></a>";
            echo "<input type='hidden' name='id' value='$studentid'><br>";
            echo "<input type='text' name='newname' class='change'style='margin:5px 2px; height: 30px;width:140px;' value='$studentname'><br>";
            echo "<input type='submit' class='change' style='height: 30px;margin: 5px 2px; width:140px;' value='Изменить имя'> ";
            echo "</form></div>";
        }
        ?>
    </div>


</body>

</html>