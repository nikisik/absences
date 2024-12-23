<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/config.php';

if (!isset($_SESSION['teacherid'])) {
    $_SESSION['teacherid'] = 0;
}

function redirect($path)
{
    header("Location: $path");
    die();
}



function login($login, $password)
{
    global $conn;
    if (hash('sha256', $password) === ($conn->query("SELECT `password` FROM `teachers` WHERE `login`='$login';")->fetch_assoc()['password'])) {
        $_SESSION['teacherid'] = $conn->query("SELECT `id` FROM `teachers` WHERE `login`='$login';")->fetch_assoc()['id'];
        settype($_SESSION['teacherid'], 'int');
        redirect('/');
    } else {
        $_SESSION['message'] = "Неверный логин или пароль";
        redirect('/');
    }
}

function checkAuth()
{
    if (!isset($_SESSION['teacherid'])) {
        $_SESSION['teacherid'] = 0;
        return false;
    }
    if ($_SESSION['teacherid'] > 0) {
        return true;
    }
}

function logout()
{
    $_SESSION['teacherid'] = 0;
}

function getout()
{
    $_SESSION['teacherid'] = 0;
    redirect('/');
}

function adminpage()
{
    if (!isadmin()) {
        getout();
    }
}

function userpage()
{
    if (!checkAuth() || $_SESSION['teacherid'] == 0) {
        redirect('/');
    }
}

function userinfo()
{
    if (DEBUGMODE) {
        echo "<br>id: " . $_SESSION['teacherid'] . '<br>';
    }
    // var_dump($_SESSION['userid']);
    // echo '<br>';
}

function isadmin()
{
    if (in_array($_SESSION['teacherid'], ADMINID)) {
        return true;
    } else {
        return false;
    }
}
