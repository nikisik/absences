<?php

// sleep(3);

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';


$login = htmlspecialchars($_POST['login']) ?? '';
$password = $_POST['password'] ?? '';

if (empty($login) || empty($password)) {
    $_SESSION['message'] = 'ненене';
    redirect('/');
}


// Хайп, но менее безопасно
// if (!findUser($email)) {
//     $_SESSION['message'] = htmlspecialchars("Пользователь \"$email\" не найден");
//     redirect('/');
// } else {
//     login($email, $password);
// }

// Не хайп, но более безопасно
login($login, $password);


// checkAuth();
// if (!isset($_SESSION['userid'])) {
//     $_SESSION['userid'] = 0;
// }
// if ($_SESSION['userid'] > 0) {
//     redirect('/home.php');
// }
if (!checkAuth()) {
    redirect('/');
} else {
    redirect('/home/');
}




// $ = $conn->query("SELECT `password` FROM `users` WHERE `email`='$email';")->fetch_assoc()['password'];


// $user = $result = $conn->query("SELECT `id` FROM `users` WHERE `email`='$email';")->fetch_assoc()['id'];

// if (!password_verify($password, $user['password'])) {
//     setMessage('error', 'Неверный пароль');
//     redirect('/');
// }

// $_SESSION['user']['id'] = $user['id'];

// redirect('/home.php');
