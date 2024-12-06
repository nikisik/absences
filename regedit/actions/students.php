<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();
// if (!isadmin()) {
//     getout();
// }

$name = $_POST['name'];
$gradeid = $_POST['gradeid'];
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
$teacherid = $conn->query("SELECT `id` FROM `teachers` WHERE `gradeid` = '$gradeid'")->fetch_assoc()['id'];
if ($conn->query("INSERT INTO `students`(`name`, `teacherid`, `gradeid`) VALUES ('$name','$teacherid','$gradeid')") === true) {
    // $_SESSION['message'] = 'Запись успешно внесена в БД';
    $_SESSION['getgrade'] = $conn->query("SELECT `gradename` FROM `grades` WHERE `id` = '$gradeid';")->fetch_assoc()['gradename'];
    $_SESSION['getteacherid'] = $teacherid;
    redirect('../students.php');
} else {
    // $_SESSION['message'] = 'ощибка какаята';
    redirect('../students.php');
}
// } else {
//     redirect('/../../src/actions/logout.php');
// }
