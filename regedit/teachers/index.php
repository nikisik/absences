<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();

$nameicon = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 640 470"><path d="M544 144C552.8 144 560 151.2 560 160L560 480C560 488.8 552.8 496 544 496L96 496C87.2 496 80 488.8 80 480L80 160C80 151.2 87.2 144 96 144L544 144zM96 96C60.7 96 32 124.7 32 160L32 480C32 515.3 60.7 544 96 544L544 544C579.3 544 608 515.3 608 480L608 160C608 124.7 579.3 96 544 96L96 96zM240 312C270.9 312 296 286.9 296 256C296 225.1 270.9 200 240 200C209.1 200 184 225.1 184 256C184 286.9 209.1 312 240 312zM208 352C163.8 352 128 387.8 128 432C128 440.8 135.2 448 144 448L336 448C344.8 448 352 440.8 352 432C352 387.8 316.2 352 272 352L208 352zM408 208C394.7 208 384 218.7 384 232C384 245.3 394.7 256 408 256L488 256C501.3 256 512 245.3 512 232C512 218.7 501.3 208 488 208L408 208zM408 304C394.7 304 384 314.7 384 328C384 341.3 394.7 352 408 352L488 352C501.3 352 512 341.3 512 328C512 314.7 501.3 304 488 304L408 304z"/></svg>';
$keyicon =     '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 600 400"><path d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0 160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-40 40 0c13.3 0 24-10.7 24-24l0-40 40 0c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>';
$plusicon =    '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 448 512"><path d="M256 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 160-160 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l160 0 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32l0-160 160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-160 0 0-160z"/></svg>';
$smallplusicon = ' +';
$exiticon =    '<svg xmlns="http://www.w3.org/2000/svg" style="fill:red;" height="12" width="8.25" viewBox="0 0 352 512"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"/></svg>';

$allgrades = array();
foreach($conn->query("SELECT `id`,`grade`,`litera` FROM `grades` ORDER BY `grade`,`litera`") as $graderow){
  // $allgrades = $allgrades + array($graderow['id'] => array('grade' => $graderow['grade'],'litera' => $graderow['litera']));
  array_push($allgrades, array('id' => $graderow['id'], 'grade' => $graderow['grade'],'litera' => $graderow['litera']));
}

$allteachers = $conn->query("SELECT * FROM `teachers` ORDER BY `name`");

$mainperms = array();// {  [0] => {[0] => 'teacherid' [1] => 'gradeid'}  }
foreach ($conn->query("SELECT `teacherid`,`gradeid` from `perms` WHERE `main` = 1") as $permrow) {
  array_push($mainperms, array($permrow['teacherid'], $permrow['gradeid']));
}

$secperms = array();// {  [0] => {[0] => 'teacherid' [1] => 'gradeid'}  }
foreach ($conn->query("SELECT `teacherid`,`gradeid` from `perms` WHERE `main` = 0") as $permrow) {
  array_push($secperms, array($permrow['teacherid'], $permrow['gradeid']));
}

// зато НЕ сделал 38 запросов в БД (!!!!!!!!!!!!!!!!!)
function getMainANDSecGrades(int|string $teacherid,array $allgrades,array $mainperms,array $secperms,string $plusicon,string $smallplusicon): array {
  $PosOfMainPerm = array_search($teacherid,array_column($mainperms,0));
  if (empty($PosOfMainPerm)) {
    $maingradename = $plusicon;
  } else{
    $MainPerm = $mainperms[$PosOfMainPerm];
    $gradeid = $MainPerm[1];
    $MainGrade = $allgrades[array_search($gradeid,array_column($allgrades,'id'))];
    $MainArrSlice = array_slice($MainGrade,1);
    $maingradename = implode($MainArrSlice);
  }
      
  $secgradenames = [];
  $PosOfSecPerms = findAllMatchingKeys($teacherid,array_column($secperms,0));
  foreach ($PosOfSecPerms as $PosOfPerm) {
      $SecPerm = $secperms[$PosOfPerm];
      $gradeid = $SecPerm[1];
      $SecGrade = $allgrades[array_search($gradeid,array_column($allgrades,'id'))];
      array_push($secgradenames,implode(array_slice($SecGrade,1)));
  } 
  if (empty($PosOfPerm)) {
      $secgradenames = $smallplusicon;
  } else {
    $secgradenames = implode(' ',$secgradenames);
  }

  return array($maingradename,$secgradenames);
}

?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать учителей</title>
    <link href='/assets/teachers.css' rel='stylesheet'>
    <link href='/assets/topbar.css' rel='stylesheet'>


</head>

<body>

  <?= makeTopbar(PAGES, '/regedit/teachers/', isadmin());?>

  
  <div style='float:right;'>      <?//right side box ?>
    <div class="defaultbox" >    <?//ADD TEACHER ?>
      <form method="POST" action="./teachers.php">
        <h3>Добавить учителя:</h3>
        <input class="addteacher" type="text" name="fullname" placeholder="ФИО" required><br>
        <input class="addteacher" type="text" name="login" placeholder="Логин" required><br>
        <input class="addteacher" type="text" name="password" placeholder="Пароль" required><br>


        <?php // <select> -> DISOWNED grades | IN ADD TEACHER
          echo "<select name='gradeid' id='newteachergrade' required>";
          echo "<option value='0'> — </option>";
          foreach ($allgrades as $grade) {
            $gradeid = $grade['id'];
            if ( array_search($gradeid,array_column($mainperms,1)) !== false ){
              continue;
            }
            $gradename = $grade['grade'].$grade['litera'];
            echo "<option value='$gradeid'>$gradename</option>";
          }
          echo "</select><br>"; // <select> -> DISOWNED grades
        ?> 


        <button type="submit">Сделать запись</button>
      </form>
    </div><?//ADD TEACHER ?>
      <?php 
        if (isset($_SESSION['message'])) {
          echo '<div class="message">' . $_SESSION['message'] . '</div>';
          unset($_SESSION['message']);
        }
      ?>
  </div>


    <div class="flexbox">

      <?php
        foreach ($allteachers as $teacher) {
          $id = $teacher['id'];
          if (in_array($id, ADMINID)){ 
              continue; 
          }
          $name = $teacher['name'];
          $login = $teacher['login'];

          echo "
          <div class='teacherBox'>
            <form method='POST' action='./teachers.php'>";

            
            //КАРТОЧКА И ИМЯ
            echo " 
            <div class='lineContainer'>
              $nameicon <div id='teacherName$id' class='lineContainer'>$name</div> 
              <input class='changeParamInput' id='changeNameInput$id' value='$name'>
            </div>
            
            <script>
              const teacherName$id = document.getElementById('teacherName$id');
              const changeNameInput$id = document.getElementById('changeNameInput$id');
              teacherName$id.addEventListener('dblclick', () => {
                teacherName$id.style.display = 'none';
                changeNameInput$id.style.display = 'inline-block';
                changeNameInput$id.focus();
              }); 
                
              changeNameInput$id.addEventListener('blur', () => {
                teacherName$id.style.display = 'inline-block';
                changeNameInput$id.style.display = 'none';
              });
            </script>";
                
            //КРEСТИК
            echo "
            <a href='/regedit/teachers/teachers.php?deleteid=$id' class='deleteButton'>$exiticon</a>";


            //КЛЮЧИК И ЛОГИН
            echo "
            <div>
              $keyicon <div id='teacherLogin$id' class='lineContainer'>$login</div>
              <input class='changeParamInput' id='changeLoginInput$id' value='$login'>
            </div>
            
            <script>
              const teacherLogin$id = document.getElementById('teacherLogin$id');
              const changeLoginInput$id = document.getElementById('changeLoginInput$id');
              teacherLogin$id.addEventListener('dblclick', () => {
                teacherLogin$id.style.display = 'none';
                changeLoginInput$id.style.display = 'inline-block';
                changeLoginInput$id.focus();
              }); 
                
              changeLoginInput$id.addEventListener('blur', () => {
                teacherLogin$id.style.display = 'inline-block';
                changeLoginInput$id.style.display = 'none';
              });
            </script>";
            
            // // ЭЩКЕРЕ ЗАПИСЬ vvvvvvvvvv ЗАЩИТА ОТ ПОНИМАНИЯ КЕМ ЛИБО
            // $maingradename = (empty(array_search($id,array_column($mainperms,0))) && array_search($id,array_column($mainperms,0)) !== 0) ? $plusicon      : implode(array_slice($allgrades[array_search($mainperms[array_search($id,array_column($mainperms,0))][1],array_column($allgrades,'id'))],1));
            // $secgradenames = (empty(array_search($id,array_column($secperms ,0))) && array_search($id,array_column($secperms ,0)) !== 0) ? $smallplusicon : implode(array_slice($allgrades[array_search($secperms [array_search($id,array_column($secperms ,0))][1],array_column($allgrades,'id'))],1));
            // // ЭЩКЕРЕ СТРОКА ^^^^^^^^^^^^^^^ ШИФРОВАНИЕ ^^^^^
            $MainANDSecGrades = getMainANDSecGrades($id,$allgrades,$mainperms,$secperms,$plusicon,$smallplusicon);
            $maingradename = $MainANDSecGrades[0];
            $secgradenames = $MainANDSecGrades[1];  

            echo "<b>";
            if (empty($maingradename) && 0) {
              
                      // $grades = $conn->query("SELECT `id`,`grade`,`litera` FROM `grades` ORDER BY `grade`,`litera`");
                      foreach ($allgrades as $grade) {
                          echo "<select required name='gradeid' style='max-width:40px;max-height:16px;margin:0;display:none;' id='select$id'>";
                          foreach ($grades as $grade) {
                              $gradeid = $grade['id'];
                              if ($conn->query("SELECT `id` FROM `perms` WHERE `gradeid` = '$gradeid' AND `main` = 1")->num_rows > 0) {
                                  continue;
                              }
                              $gradename = $grade['grade'].$grade['litera'];
                              echo "<option value='$gradeid'><a href='/regedit/teachers/teachers.php?teacherid=$id?'>$gradename</a></option>";
                          }
                          echo "<option value='0'> — </option>";
                          echo "</select>";
                      }
                      
                      echo " 
                      <div id='addmain$id' style='cursor:pointer; height:16px;'>".$plusicon."</div>
                      <script>
                          const addmain$id = document.getElementById('addmain$id');
                          const select$id = document.getElementById('select$id');
                          addmain$id.addEventListener('click', () => {
                              addmain$id.style.display = 'none';
                              select$id.style.display = 'block';
                          }) 
                      </script>
                      ";
                    } else {
                      echo $maingradename . $secgradenames;
                    }
                    echo "</b>";
                  echo "</form>";
                echo "</div>";
                // <!--
                    // <form method='POST' action='/regedit/teachers/teachers.php'>
                    //     <input type='hidden' name='id' value='$id' require> 
                    //     <input type='text' name='newname' value='$name' class='change' require><br> 
                    // </form>
                // -->
        }

        $teacherBoxWidth = mb_strlen($conn->query("SELECT `name` FROM `teachers` ORDER BY LENGTH(`name`) DESC LIMIT 1;")->fetch_assoc()['name'])*7 + 66 . 'px'; //maxNameLength * px per sym + buttonsWidth
        echo "
        <script>
          const boxes = document.getElementsByClassName('teacherBox');
          for (var box of boxes) {
            box.style.width = '$teacherBoxWidth';
          }
        </script>";

        ?>
    </div>

    <?php
    userinfo();
    // if (isset($_SESSION['message'])) {
    //     echo '<div style="float:right;">' . $_SESSION['message'] . '</div>';
    //     unset($_SESSION['message']);
    // }
    // var_dump($mainperms);

    // $id = '';
    // $id = 1034;

    // var_dump($mainperms);echo '<br>';echo '<br>';
    // var_dump($allgrades);echo '<br>';
    // echo $id.'<br>';
    // echo 'PosOfPerm: ';var_dump( 
    //   array_search($id,array_column($mainperms,0))  );echo '<br>';
    // echo 'Perm: ';var_dump(
    //   $mainperms[array_search($id,array_column($mainperms,0))]   );echo '<br>';
    // echo 'gradeid: '.            
    //   $mainperms[array_search($id,array_column($mainperms,0))][1]   .'<br>';
    // echo 'NumOfGrade: ';var_dump( 
    //   array_search($mainperms[array_search($id,array_column($mainperms,0))][1],array_column($allgrades,'id'))  );echo '<br>';
    // echo 'grade: ';var_dump(
    //   $allgrades[ array_search($mainperms[array_search($id,array_column($mainperms,0))][1],array_column($allgrades,'id')) ]   );echo '<br>';
    // echo 'ArrSlice: ';var_dump(
    //   array_slice($allgrades[array_search($mainperms[array_search($id,array_column($mainperms,0))][1],array_column($allgrades,'id'))],1)   );echo '<br>';
    // echo 'gradename: '.
    // implode(array_slice($allgrades[array_search($mainperms[array_search($id,array_column($mainperms,0))][1],array_column($allgrades,'id'))],1))   .'<br>';
    // echo
    // empty(array_search($id,array_column($mainperms,0))) ? '' : implode(array_slice($allgrades[array_search($mainperms[array_search($id,array_column($mainperms,0))][1],array_column($allgrades,'id'))],1));
    // var_dump($secperms);echo '<br>';echo '<br>';
    // var_dump(array_search($id,array_column($secperms,0)));echo '<br>';echo '<br>';
    // var_dump($allgrades);echo '<br>';
    // echo $id.'<br>';
    // echo 'PosOfPerm: ';var_dump( 
    //   findAllMatchingKeys($id,array_column($secperms,0))  );echo '<br>';
    // echo 'Perm: ';var_dump(
    //   $secperms[array_search($id,array_column($secperms,0))]   );echo '<br>';
    // echo 'gradeid: '.
    //   $secperms[array_search($id,array_column($secperms,0))][1]   .'<br>';
    // echo 'NumOfGrade: ';var_dump( 
    //   array_search($secperms[array_search($id,array_column($secperms,0))][1],array_column($allgrades,'id'))  );echo '<br>';
    // echo 'grade: ';var_dump(
    //   $allgrades[ array_search($secperms[array_search($id,array_column($secperms,0))][1],array_column($allgrades,'id')) ]   );echo '<br>';
    // echo 'ArrSlice: ';var_dump(
    //   array_slice($allgrades[array_search($secperms[array_search($id,array_column($secperms,0))][1],array_column($allgrades,'id'))],1)   );echo '<br>';
    // echo 'gradename: '.
    // implode(array_slice($allgrades[array_search($secperms[array_search($id,array_column($secperms,0))][1],array_column($allgrades,'id'))],1))   .'<br>';
    // echo
    // (empty(array_search($id,array_column($secperms,0))) && array_search($id,array_column($secperms,0)) !== 0) ? 'a' : implode(array_slice($allgrades[array_search($secperms[array_search($id,array_column($secperms,0))][1],array_column($allgrades,'id'))],1));
    



    // function getMainANDSecGrades(int|string $teacherid,array $allgrades,array $mainperms,array $secperms,string $plusicon,string $smallplusicon): array {
    //   $PosOfMainPerm = array_search($teacherid,array_column($mainperms,0));
    //   if (empty($PosOfMainPerm)) {
    //     $maingradename = $plusicon;
    //   } else{
    //     $MainPerm = $mainperms[$PosOfMainPerm];
    //     $gradeid = $MainPerm[1];
    //     $MainGrade = $allgrades[array_search($gradeid,array_column($allgrades,'id'))];
    //     $MainArrSlice = array_slice($MainGrade,1);
    //     $maingradename = implode($MainArrSlice);
    //   }
      
    //   $secgradenames = [];
    //   $PosOfSecPerms = findAllMatchingKeys($teacherid,array_column($secperms,0));
    //   foreach ($PosOfSecPerms as $PosOfPerm) {
    //       $SecPerm = $secperms[$PosOfPerm];
    //       $gradeid = $SecPerm[1];
    //       $SecGrade = $allgrades[array_search($gradeid,array_column($allgrades,'id'))];
    //       array_push($secgradenames,implode(array_slice($SecGrade,1)));
    //   } 
    //   if (empty($PosOfPerm)) {
    //       $secgradenames = $smallplusicon;
    //   } else {
    //     $secgradenames = implode(' ',$secgradenames);
    //   }

    //   return array($maingradename,$secgradenames);
    // }
    // // var_dump(getMainANDSecGrades($id,$allgrades,$mainperms,$secperms,$plusicon,$smallplusicon));





      // $PosOfMainPerm = array_search($id,array_column($mainperms,0));
      // if (empty($PosOfMainPerm)) {
      //   $maingradename = $plusicon;
      // } else{
      //   $MainPerm = $mainperms[$PosOfMainPerm];
      //   $gradeid = $MainPerm[1];
      //   $MainGrade = $allgrades[array_search($gradeid,array_column($allgrades,'id'))];
      //   $maingradename = implode(array_slice($MainGrade,1));
      // }
      // $secgradenames = [];
      // $PosOfSecPerms = findAllMatchingKeys($id,array_column($secperms,0));
      // foreach ($PosOfSecPerms as $PosOfPerm) {
      //     $SecPerm = $secperms[$PosOfPerm];
      //     $gradeid = $SecPerm[1];
      //     $SecGrade = $allgrades[array_search($gradeid,array_column($allgrades,'id'))];
      //     array_push($secgradenames,implode(array_slice($SecGrade,1)));
      // } 
      // if (empty($PosOfPerm)) {
      //     $secgradenames = $smallplusicon;
      // } else {
      //   $secgradenames = implode(' ',$secgradenames);
      // }

      // var_dump(array($maingradename,$secgradenames));
    ?>

</body>

</html>