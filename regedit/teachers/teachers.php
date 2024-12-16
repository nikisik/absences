<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['gradeid'])) {
    if (empty($_POST['login']) || empty($_POST['password']) || empty($_POST['name']) || empty($_POST['gradeid'])) {
        $_SESSION['message'] = 'ЧТО-ТО ПУСТО БОЖЕ МОЙ КАКОЙ СТРЕСС КАКОЙ СТРЕСС';
        redirect('/regedit/teachers/');
    }
    $login = htmlspecialchars($_POST['login']);
    $password = hash('sha256', $_POST['password']);
    $name = htmlspecialchars($_POST['name']);
    $gradeid = htmlspecialchars($_POST['gradeid']);
    if (!$conn->query("SELECT `id` FROM `teachers` WHERE `login` = '$login'")->fetch_assoc()['id']) {
        if ($conn->query("INSERT INTO `teachers`(`login`, `password`, `name`, `gradeid`) VALUES ('$login','$password','$name','$gradeid')") === true) {
            $_SESSION['message'] = 'Учитель добавлен';
            redirect('/regedit/teachers/');
        } else {
            $_SESSION['message'] = 'ощибка какаята';
            redirect('/regedit/teachers/');
        }
    } else {
        $_SESSION['message'] = 'ошибка, либо такой логин уже есть, либо что-то еще, задумайтесь.....';
        redirect('/regedit/teachers/');
    }
} else if (isset($_GET['deleteid'])) {
    $id = htmlspecialchars($_GET['deleteid']);
    if (in_array($id, ADMINID)) {
        $_SESSION['message'] = 'нельзя удалить админа';
        redirect('/regedit/teachers/');
    } else if ($conn->query("DELETE FROM `teachers` WHERE `id` = '$id'") === True) {
        $_SESSION['message'] = 'учитель удален';
        redirect('/regedit/teachers/');
    } else {
        $_SESSION['message'] = 'ошибко';
        redirect('/regedit/teachers/');
    }
}
