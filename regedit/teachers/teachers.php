<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['gradeid'])) {// ДОБАВЛЯЕМ

    if (empty($_POST['login']) || empty($_POST['password']) || empty($_POST['name']) || empty($_POST['gradeid'])) { //ПУСТОЕ ЧТО-ТО
        // $_SESSION['message'] = 'ЧТО-ТО ПУСТО БОЖЕ МОЙ КАКОЙ СТРЕСС КАКОЙ СТРЕСС';
        // redirect('/regedit/teachers/');
        getout();
    }

    $login = htmlspecialchars($_POST['login']);
    $password = hash('sha256', $_POST['password']);
    $name = htmlspecialchars($_POST['name']);
    $gradeid = htmlspecialchars($_POST['gradeid']);

    if ($conn->query("SELECT `id` FROM `perms` WHERE `gradeid` = '$gradeid' AND `main` = 1")->num_rows > 0) { // если класс занят
        $_SESSION['message'] = "этот класс уже принадлежит кому-то другому";
        redirect('/regedit/teachers/');
    }

    if ($conn->query("SELECT `id` FROM `teachers` WHERE `login` = '$login'")->num_rows == 1) { // логин занят
        $_SESSION['message'] = 'ошибка, либо такой логин уже есть, либо что-то еще, задумайтесь.....';
        redirect('/regedit/teachers/');
    }

    $ins = $conn->query("INSERT INTO `teachers`( `login`, `password`, `name`) VALUES ('$login','$password','$name');");
    $teacherid = $conn->query("SELECT `id` FROM `teachers` ORDER BY `id` DESC LIMIT 1")->fetch_assoc()['id'];
    $perm = $conn->query("INSERT INTO `perms`(`teacherid`, `gradeid`, `main`) VALUES ('$teacherid','$gradeid','1')");
    var_dump($ins);
    var_dump($perm);
    if ($ins && $perm) {
            
            
            $_SESSION['message'] = 'Учитель добавлен';
            // redirect('/regedit/teachers/');
        } else {
            $_SESSION['message'] = 'ощибка какаята';
            // redirect('/regedit/teachers/');
            die();
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
