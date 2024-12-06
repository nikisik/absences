<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';

if (DEBUGMODE) {
    $_SESSION['teacherid'] = 999;
}

redirect('./index.php');
