<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();


if (isset($_POST['id']) && isset($_POST['newgradename'])) {
    $id = htmlspecialchars($_POST['id']) ?? null;
    $newgradename = htmlspecialchars($_POST['newgradename']) ?? null;
    if (strlen($newgradename) > 4 || strlen($newgradename) < 2) {
        $_SESSION['message'] = "<div id='message'>НАЗВАНИЕ ДОЛЖНО БЫТЬ ОТ 2-УХ ДО 3-ЁХ СИМВОЛОВ ВКЛЮЧИТЕЛЬНО</div>";
        redirect('/regedit/grades/');
    } else {
        $gradename = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$id'")->fetch_assoc()['gradename'];
        if ($conn->query("UPDATE `grades` SET `gradename`='$newgradename' WHERE `id` = '$id'") === True) {
            $_SESSION['message'] = "<div id='message'>Название изменено с $gradename на $newgradename</div>";
            redirect('/regedit/grades/');
        } else {
            $_SESSION['message'] = "<div id='message'>ох уж эти ошибки))))</div>";
            redirect('/regedit/grades/');
        }
    }
} else if (isset($_POST['addgrade'])) {
    $addgradename = htmlspecialchars($_POST['addgrade']) ?? null;
    if (strlen($addgradename) > 4 || strlen($addgradename) < 2) {
        $_SESSION['message'] = "<div id='message'>НАЗВАНИЕ ДОЛЖНО БЫТЬ ОТ 2-УХ ДО 3-ЁХ СИМВОЛОВ ВКЛЮЧИТЕЛЬНО</div>";
        redirect('/regedit/grades/');
    } else if ($conn->query("INSERT INTO `grades`(`gradename`) VALUES ('$addgradename')") === True) {
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
