<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();


?>


<!DOCTYPE html>
<html lang="ru" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo 'Список отсутствующих на ' . htmlspecialchars($_GET['date'] ?? date('d.m.Y')); ?></title>
    <link rel="stylesheet" href="/assets/home.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body>


    <?php #СКРЫВАПЕМ МАКЕТ ТАБЛИЦЫ ЕСЛИ ПРОПУСКОВ НЕТ
    $date = isset($_GET['date']) ? date("Y.m.d", strtotime(htmlspecialchars($_GET['date']))) : date('Y.m.d');

    $NumberOfPasses = $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
    if ($NumberOfPasses == 0) {
        echo '<!--';
    } #СКРЫВАПЕМ МАКЕТ ТАБЛИЦЫ ЕСЛИ ПРОПУСКОВ НЕТ
    ?>





    <?php
    echo "
    <table style='padding:10px;' id='missing'>
        <tr>
            <th>Класс</th>
            <th>Имя</th>
            <th>Причина</th>
        </tr>";






    $allpurposes = array();
    foreach ($conn->query("SELECT `id`,`name` FROM `purpose` ORDER BY `id`") as $allpurposesrow) {
        $allpurposes = $allpurposes + array($allpurposesrow['id'] => $allpurposesrow['name']);
    }

    foreach ($conn->query("SELECT * FROM `grades` ORDER BY `grade`,`litera`") as $grade) {
        $gradeid = $grade['id'];
        
        $litera = $grade['litera'] ?? ''; //пока так, уж не помню почему
        $grade = $grade['grade'] ?? '';
        $gradename = $grade.$litera;
        
        $passes = $conn->query("SELECT * FROM `passes` WHERE `gradeid` = '$gradeid' AND `date` = '$date'");
        foreach ($passes as $pass) {
            $studentid = $pass['studentid'];
            $name = $conn->query("SELECT `name` FROM `students` WHERE `id` = $studentid")->fetch_assoc()['name'] ?? 'ученик удалён';

            $purpose = $allpurposes[$pass["purposeid"]] ?? 'причина удалена';

            echo "
            <tr>
                <th>$gradename</th>
                <th>$name</th>
                <th>$purpose</th>
            </tr>";
        }
    }


    echo '</table>';
    ?>





    <?php
    if ($NumberOfPasses == 0) { #СКРЫВАПЕМ МАКЕТ ТАБЛИЦЫ ЕСЛИ ПРОПУСКОВ НЕТ
        echo '-->';
    }  #СКРЫВАПЕМ МАКЕТ ТАБЛИЦЫ ЕСЛИ ПРОПУСКОВ НЕТ
    else {
        echo "
        <script>
            print();
            //setTimeout(function my(){location.href = '/home/statistic/';}, 1000);
        </script>"; #print, всё понятно??????
    }
    echo "Количесво пропусков за сегодня: " . $NumberOfPasses;
    ?>


</body>


</html>