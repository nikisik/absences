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
    <title><?php echo 'Список отсутствующих на ' . date('d.m.Y'); ?></title>
    <link rel="stylesheet" href="../../assets/home.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body>
    <!-- <svg width='1300' height='1300'> -->

    <table style='padding:10px;' id='missing'>
        <?php
        $date = date('Y.m.d');

        $NumberOfPasses = $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
        if ($NumberOfPasses == 0) {
            echo '<!--';
        }


        // echo '<text y="20" font-size="15" text-anchor="left" fill="black">';
        // echo 'Список отсутствующих на ' . $date;
        // echo '</text>';

        $rows = $conn->query("SELECT * FROM `passes` WHERE `date` = '$date' ORDER BY `gradeid`;");
        echo '
        <tr>
        <th>Класс</th>
        <th>Имя</th>
        <th>Причина</th>
        </tr>';


        foreach ($rows as $row) {
            // $teacherid = $row['teacherid'];
            // $purposeid = $row['purposeid'];
            // $gradeid = $conn->query("SELECT `gradeid` FROM `teachers` WHERE `id` = '$teacherid';")->fetch_assoc()['gradeid'];

            // $gradename = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$gradeid';")->fetch_assoc()['gradename'];
            // $purposename = $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid';")->fetch_assoc()['name'];
            // $name = $conn->query("SELECT `name` FROM `students` WHERE `teacherid` = '$teacherid';")->fetch_assoc()['name'];



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

    // $NumberOfPasses = $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
    if ($NumberOfPasses == 0) {
        echo '-->';
    } else {
        echo "
    <script>
        print()
    </script>";
    }
    echo "Количесво пропусков за сегодня: " . $conn->query("SELECT COUNT(`id`) FROM `passes` WHERE `date` = '$date';")->fetch_assoc()['COUNT(`id`)'];
    ?>


</body>


</html>