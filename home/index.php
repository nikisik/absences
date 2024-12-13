<?php

// require_once __DIR__ . '/../src/helpers.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';

// if (!checkAuth()) {
//   redirect('../');
// }
userpage();
if (isset($_GET['filter'])) {
  $_SESSION['filter'] = htmlspecialchars($_GET['filter'] ?? null);
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
  <link rel="stylesheet" href="/assets/home.css">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body style='margin:0;'>


  <div class="topnav">
    <a class="active" href="">Пропуски</a>
    <!-- <a href="./editstudents/">Редактировать учеников</a> -->
    <?php
    if (isadmin()) {
      echo "
      
      <a href='./statistic/'>Статистика</a>
      <a href='/regedit/teachers/'>Добавить учителя</a>
      <a href='/regedit/students/'>Редактировать учеников</a>
      <a href='/regedit/purposes/'>Редактировать причины</a>
      <a href='/regedit/grades/'>Редактировать классы</a>
      
      ";
      // echo "<a href='/regedit/editstudents/'>Редактировать учеников</a>";
    }
    ?>


    <a href="/src/actions/logout.php" id="logoutbtn">Выйти из аккаунта</a>

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






    // line 166 select purposes
    $purposes = $conn->query("SELECT * FROM `purpose`");
    $allpurposes = array();
    foreach ($conn->query("SELECT `id`,`name` FROM `purpose` ORDER BY `id`") as $allpurposesrow) {
      $allpurposes = $allpurposes + array($allpurposesrow['id'] => $allpurposesrow['name']);
    }


    if (!isadmin()) {
      $gradeid = $conn->query("SELECT gradeid FROM teachers WHERE id = '$teacherid'")->fetch_assoc()['gradeid'];
      $rows = $conn->query("SELECT * FROM `grades` WHERE `id` = '$gradeid' ORDER BY `gradename`");
    } else if (isadmin() && isset($_SESSION['filter'])) { //фильтрация включена
      $filter = $_SESSION['filter'];
      $gradeid = $conn->query("SELECT `id` FROM `grades` WHERE `gradename` = '$filter'")->fetch_assoc()['id'] ?? null;
      $rows = $conn->query("SELECT * FROM `grades` WHERE `id` = '$gradeid'");
      // unset($_GET['filter']);
      if ($filter == '00') {   //фильтр на все классы
        $rows = $conn->query("SELECT * FROM `grades` ORDER BY `gradename`");
      }
    } else if (isadmin() && !isset($_SESSION['filter'])) {  //фильтра нет, выводим все классы
      $rows = $conn->query("SELECT * FROM `grades` ORDER BY `gradename`");
    }

    if (isadmin()) { //filter bar(не могу понять какого черта оно отрисовывается не в табличке но ладно)
      $gradenames = $conn->query("SELECT `gradename` FROM `grades` ORDER BY `gradename`");
      echo '<div class="topnav">';
      foreach ($gradenames as $gradename) {
        $gradename = $gradename['gradename'];
        echo "<a style='padding:5px;' " . (($filter ?? null) == $gradename ? 'class="active"' : '') . "href='./?filter=$gradename'>" . (($gradename != '00') ? $gradename : 'Все') . "</a>";
      }
      echo "</div>";
    }


    $date = date('Y.m.d');
    foreach ($rows as $row) {
      $gradeid = $row['id'];
      $gradename = $row['gradename'];
      $students = $conn->query("SELECT * FROM `students` WHERE `gradeid` = $gradeid ORDER BY `name`");
      foreach ($students as $student) {
        $studentid = $student['id'];
        $studentname = $student['name'];



        $purposeid = $conn->query("SELECT `purposeid` FROM `passes` WHERE `studentid` = '$studentid' AND `date` = '$date'")->fetch_assoc()['purposeid'] ?? null;
        $purpose = $purposeid ? $allpurposes[$purposeid] : '';





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
          // line 101 select purposes
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