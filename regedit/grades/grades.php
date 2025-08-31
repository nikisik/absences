<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();


if (isset($_POST['id']) && isset($_POST['newgrade']) && isset($_POST['newlitera'])) {
    $id = htmlspecialchars($_POST['id']);
    $newgrade = htmlspecialchars($_POST['newgrade']);
    $newlitera = htmlspecialchars($_POST['newlitera']);

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

if (isset($_POST['addgrade']) && isset($_POST['addlitera'])) {
    $addgrade = htmlspecialchars($_POST['addgrade']);
    $addlitera = htmlspecialchars($_POST['addlitera']);

    if ($addgrade > 11 || $addgrade < 1 || strlen($addlitera) > 1) {
        $_SESSION['message'] = "<div id='message'>класс не может быть больше 11 или меншьше 1; буква должна быть одна</div>";
        redirect('/regedit/grades/');
    }

    if ($conn->query("SELECT `id` FROM `grades` WHERE `grade` = '$addgrade' AND `litera` = '$addlitera'") === True) {
        $_SESSION['message'] = "<div id='message'>такой класс УЖЕ есть</div>";
        redirect('/regedit/grades/');
    }

    if ($conn->query("INSERT INTO `grades`(`gradename`) VALUES ('$addgradename')") === True) {
        $_SESSION['message'] = "<div id='message'>Класс $addgradename был добавлен</div>";
        $_SESSION['addedgrade'] = true;
        redirect('/regedit/grades/');
    } else {
        $_SESSION['message'] = "<div id='message'>ох уж эти ошибки))))</div>";
        redirect('/regedit/grades/');
    }
} else if (isset($_POST['deleteid'])) {
    $deleteid = htmlspecialchars($_POST['deleteid']) ?? null;
    if (isset($conn->query("SELECT `id` FROM `students` WHERE `gradeid` = '$deleteid'")->fetch_assoc()['id']) || isset($conn->query("SELECT `id` FROM `passes` WHERE `gradeid` = '$deleteid'")->fetch_assoc()['id'])) {
        $_SESSION['message'] = "<div id='message'>Есть ученик состоящий в этом классе. Прежде чем удалить этот <br>класс вы должны удалить всех учеников этого класса</div>";
        redirect('/regedit/grades/');
    }
    if ($conn->query("DELETE FROM `grades` WHERE `id` = '$deleteid'") === True) {
        $gradename = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$deleteid'")->fetch_assoc()['gradename'];
        $_SESSION['message'] = "<div id='message'>Класс $gradename был удален</div>";
        redirect('/regedit/grades/');
    }
}
