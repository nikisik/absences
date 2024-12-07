<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';

// if (!checkAuth() || !isadmin()) {
//     redirect('../../src/action/logout.php');
// }
adminpage();


// $teacherid = $_SESSION['teacherid'];
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
    <title><?php echo 'Список отсутствующих на ' . htmlspecialchars($_GET['date']); ?></title>
    <link rel="stylesheet" href="../../assets/home.css">
    <!-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> -->
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body>


    <table style='padding:10px;' id='missing'>
        <?php
        $date = isset($_GET['date']) ? date("Y.m.d", strtotime(htmlspecialchars($_GET['date']))) : date('Y.m.d');

        $NumberOfPasses = $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
        if ($NumberOfPasses == 0) {
            echo '<!--';
        }


        // echo '<text y="20" font-size="15" text-anchor="left" fill="black">';
        // echo 'Список отсутствующих на ' . $date;
        // echo '</text>';

        echo '
        <tr>
        <th>Класс</th>
        <th>Имя</th>
        <th>Причина</th>
        </tr>';

        $allpasses = $conn->query("SELECT * FROM `passes` WHERE `date` = '$date' ORDER BY `gradeid`;");
        $gradesWithAbsences = $conn->query("SELECT DISTINCT `gradeid` FROM `passes` WHERE `date` = '$date';")->fetch_assoc();
        $graderows = $conn->query("SELECT * FROM `grades`;");
        foreach ($graderows as $graderow) {
            $gradeid = $graderow['id'];
            if (!in_array($gradeid, ($gradesWithAbsences ?? array()))) {
                echo 'skipped ' . $gradeid;
                continue;
            }



            $gradeid = $row['gradeid'];
            $studentid = $row['studentid'];
            $purposeid = $row['purposeid'];
            $gradename = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$gradeid';")->fetch_assoc()['gradename'];
            $name = $conn->query("SELECT `name` FROM `students` WHERE `id` = '$studentid';")->fetch_assoc()['name'];
            $purposename = $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid';")->fetch_assoc()['name'];



            echo '<tr>';
            echo '<td>' . $gradename . '</td>';
            echo '<td>' . $name . '</td>';
            echo '<td>' . $purposename . '</td>';
            echo '</tr>';


            // var_dump($teacherid);
            // var_dump($purposeid);
            // var_dump($gradeid);
            // var_dump($gradename);
            // var_dump($purposename);
            // var_dump($name);
        }


        ?>
    </table>
    <?php


    if ($NumberOfPasses == 0) {
        echo '-->';
    } else {
        echo "
    <script>
        print()
    </script>";
    }
    // echo "Количесво пропусков за сегодня: " . $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
    echo "Количесво пропусков за сегодня: " . $NumberOfPasses;
    ?>


</body>


</html>