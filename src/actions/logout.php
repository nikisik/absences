<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     logout();
// }
logout();
redirect('/');
