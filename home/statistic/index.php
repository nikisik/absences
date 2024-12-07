<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';

// if (!checkAuth() || !isadmin()) {
//     redirect('../../src/action/logout.php');
// }
adminpage();


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
    <title>Статистика</title>
    <link rel="stylesheet" href="../../assets/home.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body style='margin:0;'>


    <div class="topnav">
        <a href="/../">Пропуски</a>
        <!-- <a href="../editstudents/">Редактировать учеников</a> -->
        <?php
        if (isadmin()) {
            echo "<a class='active' href=''>Статистика</a>";

            echo "<a href='/../../regedit/students.php'>Добавить ученика</a>";
            echo "<a href='/../../regedit/teachers.php'>Добавить учителя</a>";
            echo "<a href='/../regedit/purposes.php'>Редактировать причины</a>";
            echo "<a href='/../regedit/grades.php'>Редактировать классы</a>";
        }
        ?>

        <!--<a href="#about">About</a> -->
        <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>
        <!-- <a href="/account.php" id="logoutbtn"><i class='bx bxs-user-circle bx-sm' style="position: absolute; transform: translateX(-25%);"></i>. .</a> -->
    </div>




    <table style='padding:10px;'>
        <!-- id='missing' -->
        <tr>
            <th>Класс</th>
            <th>Имя</th>
            <th>Всего пропусков</th>
            <th>Причины за всё время</th>
            <th>Учитель</th>
        </tr>
        <?php

        if (isadmin()) {
            $rows = $conn->query("SELECT * FROM `students` ORDER BY `gradeid`,`name`");
        } else {
            $rows = $conn->query("SELECT * FROM `students` WHERE `teacherid` = '$teacherid' ORDER BY `name`");
        }

        foreach ($rows as $row) {

            $gradeid = $row['gradeid'];
            $gradename = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$gradeid';")->fetch_assoc()['gradename'];
            $studentid = $row['id'];
            $studentname = $row['name'];
            $date = date('Y.m.d');
            $allabsebces = count($conn->query("SELECT `id` FROM `passes` WHERE `studentid` = '$studentid';")->fetch_all() ?? array());
            $absebcespurposeids = $conn->query("SELECT `purposeid` FROM `passes` WHERE `studentid` = '$studentid';")->fetch_all() ?? array();

            // $ismissingtoday = isset($conn->query("SELECT `date` FROM `missings` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['date']);
            // if ($ismissingtoday) {
            //     $missing = 'Н';
            //     $purposeid = $conn->query("SELECT `purposeid` FROM `missings` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['purposeid'];
            //     $purpose = $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid'")->fetch_assoc()['name'];
            // } else {
            //     $missing = ''; // +++++++
            //     $purpose = '';
            // }

            // var_dump($ismissingtoday);
            // var_dump($missing);
            // echo "<br>$studentname: ";
            // var_dump($conn->query("SELECT `id` FROM `missings` WHERE `studentid` = '$studentid';")->fetch_all() ?? array());

            echo "<tr>";


            echo "<td>" . $gradename . "</td>";
            echo "<td>" . $studentname . "</td>";
            echo "<td>" . ($allabsebces == 0 ? '' : $allabsebces) . "</td>"; // Всего пропусков

            // ПРИЧИНЫ //
            $passes = $conn->query("SELECT `purposeid`,`date` FROM `passes` WHERE `studentid` = '$studentid' ORDER BY `date` DESC;");

            // КАКОЙ Я КЛЁВЫЙ
            // echo "<td><select ";
            // if (empty($passes->fetch_assoc())) {
            //     echo "style= 'pointer-events: none;'";
            // }
            // echo ">";

            echo "<td><select class='statisticselect' " . (empty($passes->fetch_assoc()) ? "style='pointer-events: none;' name='list'" : "") . ">";

            foreach ($passes as $pass) {
                $purposeid =  $pass['purposeid'];
                $purposename = $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid';")->fetch_assoc()['name'];
                $passdate = $pass['date'];
                echo "<option> $purposename, $passdate </option>";
            }
            echo "</select></td>";




            echo "<td>";

            echo $conn->query("SELECT `name` FROM `teachers` WHERE `gradeid` = '$gradeid';")->fetch_assoc()['name'];


            echo "</td>";

            echo "</tr>";
            // echo "<form method=\"POST\" action=\"./addmissings.php\">";



            // echo $row['id'];
            // echo $row['name'];
            // echo $row['teacherid'];
            // echo $row['gradeid'];
        }
        ?>
    </table>

    <button onclick="location.href = 'print.php';" style="
    /* position: absolute; */
    margin-top: 10px;
    width: 100%;
    height: 50px;
    border: none;
    outline: none;
    color: rgb(0,0,0);
    border-radius: 5px;
    font-size: 17px;
    text-align: center;
    border: 2px solid rgba(0,0,0, 0.2);
    background: rgba(157, 0, 172, .3);;
    cursor: pointer;">
        Перейти на страницу для распечатки
    </button>




    <?php
    // global $dates;
    // $dates = $conn->query("SELECT DISTINCT `date` FROM `passes` ORDER BY `date`;");

    // if (empty($dates->fetch_assoc())) {
    //     echo "<!-- \n";
    // }
    // var_dump($dates->fetch_assoc());
    global $existrec;
    $existrec = null !== $conn->query("SELECT `id` FROM `passes` LIMIT 1")->fetch_assoc();
    // var_dump($existrec);
    if (!$existrec) {
        echo "<!-- \n";
    }
    ?>


    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <div id="myChart"></div>






    <script>
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            // Set Data
            const data = google.visualization.arrayToDataTable([
                ['Дата', 'Пропуски'],
                <?php
                // $dates = $conn->query("SELECT DISTINCT `date` FROM `passes` ORDER BY `date`;");
                // global $dates;


                // if (!empty($dates->fetch_assoc())) {
                $dates = $conn->query("SELECT DISTINCT `date` FROM `passes` ORDER BY `date`;");
                foreach ($dates as $date) {
                    $date = $date['date'];
                    $passes = $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
                    echo "['$date', $passes],";
                }
                // }


                // else {
                //     echo "['', 0]";
                // }
                ?>
            ]);

            // Set Options
            const options = {
                title: 'Дата/Пропуски'
            };

            // Draw
            const chart = new google.visualization.BarChart(document.getElementById('myChart'));
            chart.draw(data, options);

        }
    </script>

    <?php

    // $dates = $conn->query("SELECT DISTINCT `date` FROM `passes` ORDER BY `date`;");
    if (!$existrec) {
        echo "-->";
    }
    // var_dump($dates->fetch_assoc());
    // var_dump($dates);
    ?>



    <?php
    // global $dates;
    // var_dump(empty($dates));

    ?>
</body>

</html>