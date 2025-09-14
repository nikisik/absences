<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['fullname']) && isset($_POST['gradeid'])) {// ДОБАВЛЯЕМ

    if (empty($_POST['login']) || empty($_POST['password']) || empty($_POST['fullname']) || (empty($_POST['gradeid']) && $_POST['gradeid'] != 0) ) { //ПУСТОЕ ЧТО-ТО
        $_SESSION['message'] = 'что-то пустое, я вас подозреваю';
        redirect('/regedit/teachers/');
        // getout();
    }

    $login = htmlspecialchars($_POST['login']);
    $password = hash('sha256', $_POST['password']);
    $name = htmlspecialchars($_POST['fullname']);
    if (filter_var($_POST['gradeid'], FILTER_VALIDATE_INT) === false) {
        $_SESSION['message'] = "дано не число для id класса";
        redirect('/regedit/teachers/');
    }
    $gradeid = (int)htmlspecialchars($_POST['gradeid']);


    if (mb_strlen($name) > 50 || mb_strlen($login) > 50 || filter_var($gradeid, FILTER_VALIDATE_INT) === false) {
        $_SESSION['message'] = "длина имени или логина недопустимы(слишком много)";
        redirect('/regedit/teachers/');
    }

    if ($conn->query("SELECT `id` FROM `perms` WHERE `gradeid` = '$gradeid' AND `main` = 1")->num_rows > 0) { // если класс занят
        $_SESSION['message'] = "Этот класс уже принадлежит кому-то";
        redirect('/regedit/teachers/');
    }

    if ($conn->query("SELECT `id` FROM `teachers` WHERE `login` = '$login'")->num_rows == 1) { // логин занят
        $_SESSION['message'] = "Логин \"$login\" занят";
        redirect('/regedit/teachers/');
    }

    $teachercreated = $conn->query("INSERT INTO `teachers`( `login`, `password`, `name`) VALUES ('$login','$password','$name');");
    $newteacherid = $conn->query("SELECT `id` FROM `teachers` ORDER BY `id` DESC LIMIT 1")->fetch_assoc()['id'];
    
    $permsinserted = $gradeid == '0' ? 1 : $conn->query("INSERT INTO `perms`(`teacherid`, `gradeid`, `main`) VALUES ('$newteacherid','$gradeid','1')"); // внимательно! тут проверка на $gradeid == '0'
    if ($teachercreated && $permsinserted) {
            $_SESSION['message'] = 'Учитель добавлен';
        } else {
            $_SESSION['message'] = 'ощибка какаята';
        }
    redirect('/regedit/teachers/');
} 


if (isset($_GET['deleteid'])) {  //УДАЛЯЕМ
    $deleteid = htmlspecialchars($_GET['deleteid']);
    if (in_array($deleteid, ADMINID)) {
        $_SESSION['message'] = 'нельзя удалить админа';
        redirect('/regedit/teachers/');
    } 
    
    if ($conn->query("DELETE FROM `teachers` WHERE `id` = '$deleteid';") === True) {
        $conn->query("DELETE FROM `perms` WHERE `teacherid` = '$deleteid'");
        $_SESSION['message'] = 'учитель удален';
        // redirect('/regedit/teachers/');
    } else {
        $_SESSION['message'] = 'ошибко '.$deleteid;
        // redirect('/regedit/teachers/');
    }
    redirect('/regedit/teachers/');
}

$_SESSION['message'] = 'почему-то ничего не произошло';
redirect('/regedit/teachers/');