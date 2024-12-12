<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';

if (DEBUGMODE) {
    $_SESSION['teacherid'] = ADMINID[0];
}

redirect('/home');
