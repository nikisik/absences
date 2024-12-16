<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';


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
    <link rel="stylesheet" href="/assets/home.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body style='margin:0;'>


    <div class="topnav">
        <a href="/home/">Пропуски</a>
        <a class="active" href='/home/statistic/'>Статистика</a>
        <a href='/regedit/teachers/'>Редактировать учителей</a>
        <a href='/regedit/students/'>Редактировать учеников</a>
        <a href='/regedit/purposes/'>Редактировать причины</a>
        <a href='/regedit/grades/'>Редактировать классы</a>
        <!-- <a href='/regedit/editstudents/'>Редактировать учеников</a> -->



        <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>

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

        // $rows = $conn->query("SELECT * FROM `students` ORDER BY `gradeid`,`name`");



        foreach ($conn->query("SELECT * FROM `grades` ORDER BY `gradename`") as $grade) {
            $gradeid = $grade['id'];
            $gradename = $grade['gradename'];
            $rows = $conn->query("SELECT * FROM `students` WHERE `gradeid` = '$gradeid' ORDER BY `name`");
            $teachername = $conn->query("SELECT `name` FROM `teachers` WHERE `gradeid` = '$gradeid';")->fetch_assoc()['name'] ?? 'Учителя для этого класса нет, добавьте'; //теперь этот запрос делается 1 раз вместо ~25
            foreach ($rows as $row) {


                $studentid = $row['id'];
                $studentname = $row['name'];
                $date = date('Y.m.d');
                $allabsebces = count($conn->query("SELECT `id` FROM `passes` WHERE `studentid` = '$studentid';")->fetch_all() ?? array());
                // $absebcespurposeids = $conn->query("SELECT `purposeid` FROM `passes` WHERE `studentid` = '$studentid';")->fetch_all() ?? array(); //хахахах оно нигде не использовалось


                echo "<tr>";


                echo "<td>" . $gradename . "</td>";
                echo "<td>" . $studentname . "</td>";
                echo "<td>" . ($allabsebces ? $allabsebces : '') . "</td>"; // Всего пропусков

                // ПРИЧИНЫ //
                $passes = $conn->query("SELECT `purposeid`,`date` FROM `passes` WHERE `studentid` = '$studentid' ORDER BY `date` DESC;");


                echo "<td><select class='statisticselect' " . (empty($passes->fetch_assoc()) ? "style='pointer-events: none;' name='unclickableselect'" : "") . ">";

                foreach ($passes as $pass) {
                    $purposeid =  $pass['purposeid'];
                    $purposename = $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid';")->fetch_assoc()['name'];
                    $passdate = date_format(date_create_from_format("Y.m.d", $pass['date']), "d.m.Y"); //еле справился, мне нравится
                    echo "<option> $purposename, $passdate </option>";
                }
                echo "</select></td>";




                echo "<td>"; //teacher's name

                echo ($teachername);

                echo "</td>";

                echo "</tr>";
                // echo "<form method=\"POST\" action=\"./addmissings.php\">";



                // echo $row['id'];
                // echo $row['name'];
                // echo $row['teacherid'];
                // echo $row['gradeid'];
            }
        }
        ?>
    </table>

    <!-- ВОТ тут очень надо сделать модальное окно с формой чтоб можно было выставлять $_GET['date'], например 'print.php?date=06.12.2024' -->
    <!-- или не надо -->

    <div style="margin: 15px;">
        <b>Тыкните на кнопочку с датой чтобы распечатать пропуски за конкретное число:</b>

        <div id="dates">
            <?php
            $dates = $conn->query("SELECT DISTINCT `date` FROM `passes` ORDER BY `date`;");
            foreach ($dates as $date) {
                $date = date_format(date_create_from_format('Y.m.d', $date['date']), 'd.m.Y');
                echo "<a href='/home/statistic/print/?date=$date' class='date' title='Распечатать пропуски за $date'>$date</a>";
            }
            ?>
        </div>

        <button onclick="location.href = '/home/statistic/print/';" id="printbutton" title="Страница для распечатки">
            <b>Список за сегодня</b>
        </button>

    </div>





    <?php
    $NumberOfPasses = $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '" . date('Y.m.d') . "';")->fetch_assoc()['COUNT(`id`)'];
    echo 'Пропусков за сегодня: ' . $NumberOfPasses;
    $NumberOfDates = $dates->num_rows;
    // global $existrec;
    $existrec = null !== $conn->query("SELECT `id` FROM `passes` LIMIT 1")->fetch_assoc();
    // var_dump($existrec);
    if (!$existrec) {
        echo "<!-- \n";
    }
    ?>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <div id="myChart" <? echo "style='height:" . $NumberOfDates * 70 . "px;'"; ?>></div>

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
                //$dates = $conn->query("SELECT DISTINCT `date` FROM `passes` ORDER BY `date`;"); ЭТО ТЕПЕРЬ ПРОИСХОДИТ ВЫШЕ
                foreach ($dates as $date) {
                    $date = $date['date'];
                    $passes = $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
                    $date = date_format(date_create_from_format('Y.m.d', $date), 'd.m.Y');
                    echo "['$date', $passes],";
                }
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

    <!-- вот тут построим аагромний диаграммас (причина/кол-во; дата/причина) https://developers.google.com/chart/interactive/docs/gallery/areachart?hl=ru -->

    <?php
    if (!$existrec) {
        echo "-->";
    }
    ?>

</body>

</html>