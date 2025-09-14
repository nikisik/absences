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
    <link href='/assets/topbar.css' rel='stylesheet'>
    <link href='/assets/filter.css' rel='stylesheet'>
</head>

<body style='margin:0;'>


    <?= makeTopbar(PAGES, '/home/statistic/', isadmin());?>




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
        $purposes = $conn->query("SELECT * FROM `purpose` ORDER BY `id`");
        $allpurposes = array();
        foreach ($conn->query("SELECT `id`,`name` FROM `purpose` ORDER BY `id`") as $allpurposesrow) {
            $allpurposes = $allpurposes + array($allpurposesrow['id'] => $allpurposesrow['name']);
        }

        $grades = $conn->query("SELECT `id`,`grade`,`litera` FROM `grades` ORDER BY `grade`,`litera`");
        foreach ($grades as $graderows) {
            $gradeid = $graderows['id'];
            $litera = $graderows['litera'] ?? '';
            $grade = $graderows['grade'] ?? '';
            $gradename = $grade.$litera;
            $rows = $conn->query("SELECT * FROM `students` WHERE `gradeid` = '$gradeid' ORDER BY `name`");
            $teacherid = $conn->query("SELECT `teacherid` FROM `perms` WHERE `main` = 1 AND `gradeid` = '$gradeid'")->fetch_assoc()['teacherid'] ?? 0;
            $teachername = $conn->query("SELECT `name` FROM `teachers` WHERE `id` = '$teacherid';")->fetch_assoc()['name'] ?? '—'; //теперь этот запрос делается 1 раз вместо ~25
            foreach ($rows as $row) {


                $studentid = $row['id'];
                $studentname = $row['name'];
                $date = date('Y.m.d');
                $allabsences = count($conn->query("SELECT `id` FROM `passes` WHERE `studentid` = '$studentid';")->fetch_all() ?? array());
                // $absebcespurposeids = $conn->query("SELECT `purposeid` FROM `passes` WHERE `studentid` = '$studentid';")->fetch_all() ?? array(); //хахахах оно нигде не использовалось


                echo "<tr>";


                echo "<td>" . $gradename . "</td>";
                echo "<td>" . $studentname . "</td>";
                echo "<td>" . ($allabsences ? $allabsences : '') . "</td>"; // Всего пропусков

                // ПРИЧИНЫ //
                $passes = $conn->query("SELECT `purposeid`,`date` FROM `passes` WHERE `studentid` = '$studentid' ORDER BY `date` DESC");


                echo "<td><select class='statisticselect' " . (empty($passes->fetch_assoc()) ? "style='pointer-events: none;' name='unclickableselect'" : "") . ">";

                foreach ($passes as $pass) {
                    $purposeid =  $pass['purposeid'];
                    $purposename = $allpurposes[$purposeid];
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
            <b>Список на сегодня</b>
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

    <div id="Date/Pass" <? echo "style='height:" . $NumberOfDates * 70 . "px;'"; ?>></div>
    <script>
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            // Set Data
            var data = google.visualization.arrayToDataTable([
                ['Дата', 'Пропуски', {

                    role: 'annotation'
                }],
                <?php
                //$dates = $conn->query("SELECT DISTINCT `date` FROM `passes` ORDER BY `date`;"); ЭТО ТЕПЕРЬ ПРОИСХОДИТ ВЫШЕ
                foreach ($dates as $date) {
                    $date = $date['date'];
                    $passes = $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
                    $date = date_format(date_create_from_format('Y.m.d', $date), 'd.m.Y');
                    echo "['$date', $passes, $passes],";
                }
                ?>
            ]);

            // Set Options
            const options = {
                title: 'Дата/Пропуски'
            };

            // Draw
            const chart = new google.visualization.BarChart(document.getElementById('Date/Pass'));
            chart.draw(data, options);

        }
    </script>

    <!-- вот тут построим аагромний диаграммас (причина/кол-во; дата/причина) https://developers.google.com/chart/interactive/docs/gallery/areachart?hl=ru -->
    <!-- и сделал же -->

    <div id="Date/Purpose" style="width: 100%; height: 500px;"></div> <!--ДАТА/ПРИЧИНА -->
    <script type="text/javascript">
        // google.charts.load('current', {
        //     'packages': ['corechart']
        // });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Date',
                    <?php
                    foreach ($purposes as $purpose) {
                        $name = $purpose['name'];
                        echo "'$name',";
                    } ?>

                ],

                <?php
                foreach ($dates as $date) {
                    $date = $date['date'];
                    echo "['" . date_format(date_create_from_format('Y.m.d', $date), 'd.m.Y') . "',";

                    foreach ($purposes as $purpose) {
                        $purposeid = $purpose['id'];
                        $quantity = $conn->query("SELECT `id` FROM `passes` WHERE `date` = '$date' AND `purposeid` = '$purposeid'")->num_rows;
                        echo "$quantity,";
                    }

                    echo " ],\n";
                }

                ?>

            ]);

            var options = {
                title: 'Дата/Причины',
                hAxis: {
                    title: '',
                    titleTextStyle: {
                        color: '#333'
                    }
                },
                vAxis: {
                    minValue: 0
                },
                isStacked: 1,
                legend: {
                    position: 'top',
                    maxLines: 3
                },
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('Date/Purpose'));
            chart.draw(data, options);
        }
    </script>


    <div id="PurposesToday" style="width: 900px; height: 500px;"></div><!-- ПРИЧИНЫ СЕГОДНЯ -->
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Причина', 'Количество'],
                <?php
                foreach ($purposes as $purpose) {
                    $purposename = $purpose['name'];
                    $purposeid = $purpose['id'];
                    $date = date('Y.m.d');
                    $quantity = $conn->query("SELECT `id` FROM `passes` WHERE `purposeid` = '$purposeid' AND `date` = '$date'")->num_rows;
                    echo "['$purposename',$quantity],";
                }
                ?>
            ]);

            var options = {
                title: 'Причины пропусков за сегодня',
                pieHole: 0.4,
            };

            var chart = new google.visualization.PieChart(document.getElementById('PurposesToday'));
            chart.draw(data, options);
        }
    </script>


    <div id="Grade/Purposes" style="width: 100%; height: 500px;"></div>
    <script type="text/javascript">
        // google.charts.load('current', {
        //     'packages': ['corechart']
        // });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Grade',
                    <?php
                    foreach ($purposes as $purpose) {
                        $name = $purpose['name'];
                        echo "'$name',";
                    } ?>

                ],

                <?php
                foreach ($grades as $grade) {
                    
                    $gradename = $grade['grade'] . $grade['litera'];
                    // if ($gradename == '00') {
                    //     continue;
                    // }
                    $gradeid = $grade['id'];
                    echo "['$gradename',";
                    foreach ($purposes as $purpose) {
                        $purposeid = $purpose['id'];
                        $quantity = $conn->query("SELECT `id` FROM `passes` WHERE `date` = '$date' AND `gradeid` = '$gradeid' AND `purposeid` = '$purposeid'")->num_rows;
                        echo "$quantity,";
                    }

                    echo " ],\n";
                }

                ?>

            ]);

            var options = {
                title: 'Класс/Причины',
                hAxis: {
                    title: '',
                    titleTextStyle: {
                        color: '#333'
                    }
                },
                vAxis: {
                    minValue: 0
                },
                isStacked: 1,
                legend: {
                    position: 'top',
                    maxLines: 3
                },
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('Grade/Purposes'));
            chart.draw(data, options);
        }
    </script>

    <?php
    if (!$existrec) {
        echo "-->";
    }
    ?>

</body>

</html>