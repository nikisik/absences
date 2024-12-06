<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();

$login = $_POST['login'];
$password = hash('sha256', $_POST['password']);
$name = $_POST['name'];
$gradeid = $_POST['gradeid'];

if (empty($login)) {
    $_SESSION['message'] = 'пустое имя';
    redirect('../teachers.php');
    die();
}
if (empty($_POST['password'])) {
    $_SESSION['message'] = 'пустой пароль';
    redirect('../teachers.php');
    die();
}
if (empty($gradeid)) {
    $_SESSION['message'] = 'пустой класс';
    // redirect('/../teachers.php');
    var_dump($gradeid);
    var_dump($_POST['gradeid']);
    die();
}


if (isadmin()) {
    if ($conn->query("INSERT INTO `teachers`(`login`, `password`, `name`, `gradeid`) VALUES ('$login','$password','$name','$gradeid')") === true) {
        $_SESSION['message'] = 'Запись успешно внесена в БД';
        redirect('../teachers.php');
    } else {
        $_SESSION['message'] = 'ощибка какаята';
        redirect('../teachers.php');
    }
} else {
    redirect('/../../src/actions/logout.php');
}
