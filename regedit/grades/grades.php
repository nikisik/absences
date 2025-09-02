<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();


if (isset($_POST['id']) && isset($_POST['newgrade']) && isset($_POST['newlitera'])) {
    $id = htmlspecialchars($_POST['id']);
    $newgrade = htmlspecialchars($_POST['newgrade']);
    $newlitera = htmlspecialchars($_POST['newlitera']);

    if (empty($_POST['id']) || empty($_POST['newgrade']) || empty($_POST['newlitera'])){
        $_SESSION['message'] = "<div id='message'>класс или буква не могут быть пустыми</div>";
        redirect('/regedit/grades/');
    }

    if ($newgrade > 11 || $newgrade < 1 || mb_strlen($newlitera) > 1) {
        $_SESSION['message'] = "<div id='message'>класс не может быть больше 11 или меншьше 1; буква должна быть одна, дано ".mb_strlen($newlitera)."</div>";
        redirect('/regedit/grades/');
    } 

    $gradename = $conn->query("SELECT CONCAT(`grade`,`litera`) AS `gradename` FROM `grades` WHERE `id` = '$id'")->fetch_assoc()['gradename'];
    if ($conn->query("UPDATE `grades` SET `grade`='$newgrade',`litera` = '$newlitera' WHERE `id` = '$id'") === True) {
        $_SESSION['message'] = "<div id='message'>Название изменено с $gradename на $newgrade$newlitera</div>";
        redirect('/regedit/grades/');
    } else {
        $_SESSION['message'] = "<div id='message'>ох уж эти ошибки))))</div>";
        redirect('/regedit/grades/');
    } 
}

if (isset($_POST['addgrade'])) {
    $addgradename = htmlspecialchars($_POST['addgrade']);
    $addgrade = (int)mb_substr($addgradename, 0,-1);
    $addlitera = mb_substr($addgradename, -1,1);
    if (empty($addgrade) || empty($addlitera)){
        $_SESSION['message'] = "<div id='message'>класс или буква не могут быть пустыми</div>";
        redirect('/regedit/grades/');
    }

    if ($addgrade > 11 || $addgrade < 1 || strlen($addlitera) > 1) {
        $_SESSION['message'] = "<div id='message'>класс не может быть больше 11 или меншьше 1</div>";
        redirect('/regedit/grades/');
    }

    if ($conn->query("SELECT `id` FROM `grades` WHERE `grade` = '$addgrade' AND `litera` = '$addlitera'") === True) {
        $_SESSION['message'] = "<div id='message'>такой класс УЖЕ есть</div>";
        redirect('/regedit/grades/');
    }

    if ($conn->query("INSERT INTO `grades`(`grade`,`litera`) VALUES ('$addgrade','$addlitera')") === True) {
        $_SESSION['message'] = "<div id='message'>Класс $addgrade$addlitera был добавлен</div>";
        $_SESSION['addedgrade'] = true;
        redirect('/regedit/grades/');
    } else {
        $_SESSION['message'] = "<div id='message'>ох уж эти ошибки))))</div>";
        redirect('/regedit/grades/');
    }
} 

if (isset($_POST['deleteid'])) {
    $deleteid = htmlspecialchars($_POST['deleteid']) ?? null;
    if (isset($conn->query("SELECT `id` FROM `students` WHERE `gradeid` = '$deleteid'")->fetch_assoc()['id']) || isset($conn->query("SELECT `id` FROM `passes` WHERE `gradeid` = '$deleteid'")->fetch_assoc()['id'])) {
        $_SESSION['message'] = "<div id='message'>Есть ученик состоящий в этом классе. Прежде чем удалить этот <br>класс вы должны удалить всех учеников этого класса</div>";
        redirect('/regedit/grades/');
    }
    if ($conn->query("DELETE FROM `grades` WHERE `id` = '$deleteid'") === True) {
        $gradename = $conn->query("SELECT CONCAT(`grade`,`litera`) AS `gradename` FROM `grades` WHERE `id` = '$deleteid' ORDER BY `grade`,`litera`")->fetch_assoc()['gradename'];
        $_SESSION['message'] = "<div id='message'>Класс $gradename был удален</div>";
        redirect('/regedit/grades/');
    }
}

if (isset($_GET['rightshift']) || isset($_GET['leftshift'])) {

    if (($_GET['rightshift'] ?? null) == 1){
        $rawgrades = $conn->query("SELECT `id`,`grade` FROM `grades` ORDER BY `grade` DESC")->fetch_all();
        $grades = array();
        foreach ($rawgrades as $rawgrade) {
            $id = $rawgrade[0];
            $grade = (int)$rawgrade[1] + 1;
            if($conn->query("UPDATE `grades` SET `grade`='$grade' WHERE `id` = '$id'") === false){
                $_SESSION['message'] = "<div id='message'>ошибка</div>";
                redirect('/regedit/grades/');
            }
        }
        $_SESSION['message'] = "<div id='message'>Классы были сдвинуты вперед</div>";
        redirect('/regedit/grades/');
    }

    if (($_GET['leftshift'] ?? null) == 1){
        $rawgrades = $conn->query("SELECT `id`,`grade` FROM `grades` ORDER BY `grade` ASC")->fetch_all();
        $grades = array();
        foreach ($rawgrades as $rawgrade) {
            $id = $rawgrade[0];
            $grade = (int)$rawgrade[1] - 1;
            if ($grade == 0) {
                $_SESSION['message'] = "<div id='message'>некуда больше</div>";
                redirect('/regedit/grades/');
            }
            if($conn->query("UPDATE `grades` SET `grade`='$grade' WHERE `id` = '$id'") === false){
                $_SESSION['message'] = "<div id='message'>ошибка</div>";
                redirect('/regedit/grades/');
            }
        }
        $_SESSION['message'] = "<div id='message'>Классы были сдвинуты назад</div>";
        redirect('/regedit/grades/');
    }
}
