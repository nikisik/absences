<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();
// if (!isadmin()) {
//     getout();
// }

$name = htmlspecialchars($_POST['name']);
$gradeid = htmlspecialchars($_POST['gradeid']);
// $teacherid = $_POST['teacherid'];

if (empty($name)) {
    $_SESSION['message'] = 'пустой name';
    redirect('../students.php');
    die();
}
// if (empty($teacherid)) {
//     $_SESSION['message'] = 'пустой teacherid';
//     redirect('../students.php');
//     die();
// }
if (empty($gradeid)) {
    $_SESSION['message'] = 'пустой gradeid';
    redirect('../students.php');
    die();
}


// if (isadmin()) {
// $teacherid = $conn->query("SELECT `id` FROM `teachers` WHERE `gradeid` = '$gradeid'")->fetch_assoc()['id'];
if (isset($conn->query("SELECT `name` FROM `students` WHERE `name` = '$name'")->fetch_assoc()['name'])) {
    $_SESSION['message'] = 'такой ученик уже есть';
    redirect('/regedit/students/');
}
if ($conn->query("INSERT INTO `students`(`name`, `gradeid`) VALUES ('$name','$gradeid')") === true) {
    // $_SESSION['message'] = 'Запись успешно внесена в БД';
    $_SESSION['getgrade'] = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$gradeid';")->fetch_assoc()['gradename'];
    $_SESSION['getteacherid'] = $teacherid;
    $_SESSION['message'] = 'ученик внесен в БД';
    redirect('/regedit/students/');
} else {
    $_SESSION['message'] = 'ощибка какаята';
    redirect('/regedit/students/');
}
// } else {
//     redirect('/../../src/actions/logout.php');
// }
