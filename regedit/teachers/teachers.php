<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();

$login = htmlspecialchars($_POST['login']);
$password = hash('sha256', $_POST['password']);
$name = htmlspecialchars($_POST['name']);
$gradeid = htmlspecialchars($_POST['gradeid']);

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

// var_dump($login);
// echo '<br><br>';
// var_dump(in_array($login, array_merge_recursive($conn->query("SELECT `login` FROM `teachers`")->fetch_all())));
// echo '<br><br>';
// var_dump($conn->query("SELECT `login` FROM `teachers`")->fetch_all());
// echo '<br><br>';
// var_dump(array_merge_recursive($conn->query("SELECT `login` FROM `teachers`")->fetch_all()));
// echo '<br><br>';


if (isadmin() && !$conn->query("SELECT `id` FROM `teachers` WHERE `login` = '$login'")->fetch_assoc()) {
    if ($conn->query("INSERT INTO `teachers`(`login`, `password`, `name`, `gradeid`) VALUES ('$login','$password','$name','$gradeid')") === true) {
        $_SESSION['message'] = 'Запись успешно внесена в БД';
        redirect('/regedit/teachers/');
    } else {
        $_SESSION['message'] = 'ощибка какаята';
        redirect('/regedit/teachers/');
    }
} else {
    $_SESSION['message'] = '<div style="max-width: 254px;">ошибка, либо такой логин уже есть, либо вы не админ, но такого быть не может, так что... задумайтесь.....</div>';
    redirect('./');
}
