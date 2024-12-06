<?php

// require_once __DIR__ . '/../src/helpers.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';

// if (!checkAuth()) {
//   redirect('../');
// }
userpage();
if (isset($_GET['filter'])) {
  $_SESSION['filter'] = $_GET['filter'] ?? null;
}

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
  <title>Home</title>
  <link rel="stylesheet" href="../assets/home.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body style='margin:0;'>


  <div class="topnav">
    <a class="active" href="">Пропуски</a>
    <!-- <a href="./editstudents/">Редактировать учеников</a> -->
    <?php
    if (isadmin()) {
      echo "<a href='./statistic/'>Статистика</a>";


      echo "<a href='/../regedit/students.php'>Добавить ученика</a>";
      echo "<a href='/../regedit/teachers.php'>Добавить учителя</a>";
      echo "<a href='/../regedit/purposes.php'>Редактировать причины</a>";
      echo "<a href='/../regedit/grades.php'>Редактировать классы</a>";
    }
    ?>

    <!--<a href="#about">About</a> -->
    <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>
    <!-- <a href="/account.php" id="logoutbtn"><i class='bx bxs-user-circle bx-sm' style="position: absolute; transform: translateX(-25%);"></i>. .</a> -->
  </div>

  <table style='padding:10px;' id='missing'>
    <tr>
      <th>Класс</th>
      <th>Имя</th>
      <th>отсутствует</th>
      <th>причина</th>
      <th>отметить</th>
    </tr>




    <?php




    // $rows = $conn->query("SELECT `id`, `name`, `teacherid`, `gradeid` FROM `students` WHERE `teacherid` = '$teacherid' ORDER BY `name`");
    // if (isadmin()) {
    //   $rows = $conn->query("SELECT `id`, `name`, `teacherid`, `gradeid` FROM `students` ORDER BY `gradeid`,`name`");
    // }
    // foreach ($rows as $row) {
    //   $gradeid = $row['gradeid'];
    //   $gradename = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$gradeid';")->fetch_assoc()['gradename'];
    //   $studentid = $row['id'];
    //   $studentname = $row['name'];
    //   $date = date('Y.m.d');
    //   $ismissingtoday = isset($conn->query("SELECT `date` FROM `missings` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['date']);
    //   if ($ismissingtoday) {
    //     $missing = 'Н';
    //     $purposeid = $conn->query("SELECT `purposeid` FROM `missings` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['purposeid'];
    //     $purpose = $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid'")->fetch_assoc()['name'];
    //   } else {
    //     $missing = ''; // +++++++
    //     $purpose = '';
    //   }




    $purposes = $conn->query("SELECT * FROM `purpose`");



    if (!isadmin() && $teacherid > 0) {
      $rows = $conn->query("SELECT * FROM `grades` WHERE `teacherid` = '$teacherid' ORDER BY `gradename`");
    } else if (isadmin() && isset($_SESSION['filter'])) { //фильтрация включена
      $filter = htmlspecialchars($_SESSION['filter']);
      $gradeid = $conn->query("SELECT `id` FROM `grades` WHERE `gradename` = '$filter'")->fetch_assoc()['id'];
      $rows = $conn->query("SELECT * FROM `grades` WHERE `id` = '$gradeid'");
      // unset($_GET['filter']);
      if ($filter == '00') {   //все классы
        $rows = $conn->query("SELECT * FROM `grades` ORDER BY `gradename`");
      }
    } else if (isadmin() && !isset($_SESSION['filter'])) {  //все классы
      $rows = $conn->query("SELECT * FROM `grades` ORDER BY `gradename`");
    }

    if (isadmin()) { //filter
      $gradenames = $conn->query("SELECT `gradename` FROM `grades` WHERE 1");
      foreach ($gradenames as $gradename) {
        $gradename = $gradename['gradename'];
        echo "<a style='margin-right: 20px' href='./?filter=$gradename'>" . (($gradename != '00') ? $gradename : 'все*') . "</a>";
      }
    }


    $date = date('Y.m.d');
    foreach ($rows as $row) {
      $gradeid = $row['id'];
      $gradename = $row['gradename'];
      $students = $conn->query("SELECT * FROM `students` WHERE `gradeid` = $gradeid ORDER BY `name`");
      foreach ($students as $student) {
        $studentid = $student['id'];
        $studentname = $student['name'];


        // $isabsencetoday = isset($conn->query("SELECT `date` FROM `passes` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['date']);
        // if ($isabsencetoday) {
        //   $purposeid = $conn->query("SELECT `purposeid` FROM `passes` WHERE `studentid` = '$studentid'")->fetch_assoc()['purposeid'];
        //   $purpose = $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid'")->fetch_assoc()['name'];
        //   // var_dump($purpose);
        // } else {
        //   $purpose = '';
        // }
        $purposeid = $conn->query("SELECT `purposeid` FROM `passes` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['purposeid'] ?? null;
        $purpose = $purposeid ? $conn->query("SELECT `name` FROM `purpose` WHERE `id` = '$purposeid'")->fetch_assoc()['name'] : '';





        echo "<tr>";
        echo "<td>" . $gradename . "</td>";
        echo "<td>" . $studentname . "</td>";
        echo "<td>" . (isset($purposeid) ? 'Н' : '') . "</td>";
        echo "<td style='min-width: 200px;'>" . $purpose . "</td>";

        echo "<td>";
        echo "<form action='./editabsences.php' method='POST' style='margin: 0;' id='$studentid'>";
        if (!isset($purposeid)) {
          echo "<input type='hidden' name='studentid' value='$studentid'>";
          echo "<select name='purposeid'>";
          // line 79 select purposes
          foreach ($purposes as $purpose) {
            $purposeid = $purpose['id'];
            $purposename = $purpose['name'];
            echo "<option value='$purposeid'>$purposename</option>";
          }
          echo "</select>";
          echo "<button class='addpassbtn' type='submit'>отметить</button>";
        } elseif (isset($purposeid)) {
          echo "<input type='hidden' name='deleteid' value='$studentid'>";
          echo "<button class='cancelbtn' type='submit'>отменить пропуск</button>";
        }
        echo "</form>";
        echo "</td>";

        echo "</tr>";
      }
    }

    ?>
  </table>
  <!-- https://www.youtube.com/watch?v=dCDkERwRRyY&list=PLBv_z0FixgmoDMQdkk-STk3hOc4KSP_AZ&index=6&ab_channel=Obsolete -->
  <?php
  userinfo();


  ?>
</body>

</html>