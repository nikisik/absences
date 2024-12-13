<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();

if (isset($_POST['name']) && isset($_POST['gradeid'])) {
    $name = htmlspecialchars($_POST['name']);
    $gradeid = htmlspecialchars($_POST['gradeid']);
    if (empty($name) || empty($gradeid)) {
        $_SESSION['message'] = 'пустой name или gradeid';
        redirect('/regedit/students/');
    }
    if (isset($conn->query("SELECT `name` FROM `students` WHERE `name` = '$name'")->fetch_assoc()['name'])) {
        $_SESSION['message'] = 'такой ученик уже есть';
        redirect('/regedit/students/');
    }
    if ($conn->query("INSERT INTO `students`(`name`, `gradeid`) VALUES ('$name','$gradeid')") === true) {
        // $_SESSION['message'] = 'Запись успешно внесена в БД';
        $_SESSION['getgrade'] = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$gradeid';")->fetch_assoc()['gradename'];
        $_SESSION['message'] = 'ученик внесен в БД';
        redirect('/regedit/students/');
    } else {
        $_SESSION['message'] = 'ощибка какаята';
        redirect('/regedit/students/');
    }
} else if (isset($_POST['id']) && isset($_POST['newname'])) {
    $id = htmlspecialchars($_POST['id']);
    $newname = htmlspecialchars($_POST['newname']);
    if (empty($id) || empty($newname)) {
        $_SESSION['message'] = 'пустой id или newname';
        redirect('/regedit/students/');
    } else if ($conn->query("UPDATE `students` SET`name`='$newname' WHERE `id` = '$id'") === true) {
        $_SESSION['message'] = 'ученик внесен в БД';
        redirect('/regedit/students/');
    } else {
        $_SESSION['message'] = 'ощибка какаята';
        redirect('/regedit/students/');
    }
} else {
    redirect('/regedit/students/');
}
