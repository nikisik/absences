<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
userpage();


$date = date('Y.m.d');

if (isset($_POST['studentid']) && isset($_POST['purposeid'])) { //add pass!
    // $studentid = htmlspecialchars($_POST['studentid'] ?? null);
    // if (isadmin()) {
    //     $teacherid = $conn->query("SELECT `teacherid` FROM `students` WHERE `id` = '$studentid'")->fetch_assoc()['teacherid'];
    // } else {
    //     $teacherid = $_SESSION['teacherid'];
    // }
    // $purposeid = htmlspecialchars($_POST['purposeid'] ?? null);
    // $gradeid = $conn->query("SELECT `gradeid` FROM `students` WHERE `id` = '$studentid'")->fetch_assoc()['gradeid'];
    // // var_dump($studentid);
    // if ($conn->query("INSERT INTO `passes`(`studentid`, `teacherid`, `purposeid`, `date`, `gradeid`) VALUES ('$studentid','$teacherid','$purposeid','$date','$gradeid')") === true) {
    //     redirect('./');
    // } else {
    //     $_SESSION['message'] = 'ошибка, сообщите о ней куда надо, или не надо, по желанию, так';
    //     redirect('./');
    // }

    $studentid = htmlspecialchars($_POST['studentid'] ?? null);
    $purposeid = htmlspecialchars($_POST['purposeid'] ?? null);
    $teacherid = $_SESSION['teacherid'];
    // if (isadmin()) {
    //     null;
    // } elseif ($conn->query("SELECT `gradeid` FROM `teachers` WHERE `id` = '$teacherid'")->fetch_assoc()['gradeid'] == $conn->query("SELECT `gradeid` FROM `students` WHERE `id` = '$studentid'")->fetch_assoc()['gradeid']) {
    //     null;
    // } else {
    //     getout();
    // }

    if (!isadmin() && !($conn->query("SELECT `gradeid` FROM `teachers` WHERE `id` = '$teacherid'")->fetch_assoc()['gradeid'] == $conn->query("SELECT `gradeid` FROM `students` WHERE `id` = '$studentid'")->fetch_assoc()['gradeid'])) {
        getout();
    }


    if ($conn->query("INSERT INTO `passes`(`studentid`, `purposeid`, `date`) VALUES ('$studentid','$purposeid','$date')") === true) {
        redirect('./');
    } else {
        $_SESSION['message'] = 'ошибка, сообщите о ней куда надо, или не надо, по желанию, так';
        redirect('./');
    }
} else if (isset($_POST['deleteid'])) { //delete pass


    $deleteid = htmlspecialchars($_POST['deleteid']);
    $teacherid = $_SESSION['teacherid'];
    if (isadmin()) {
        $conn->query("DELETE FROM `passes` WHERE `studentid` = '$deleteid' AND `date` = '$date'");
        // unset($deleteid);
    } elseif ($conn->query("SELECT `gradeid` FROM `teachers` WHERE `id` = '$teacherid'")->fetch_assoc()['gradeid'] == $conn->query("SELECT `gradeid` FROM `students` WHERE `id` = '$deleteid'")->fetch_assoc()['gradeid']) {
        // unset($deleteid);
        $conn->query("DELETE FROM `passes` WHERE `studentid` = '$deleteid' AND `date` = '$date'");
    } else {
        // unset($deleteid);
        // redirect('/../src/actions/logout.php');
        getout();
    }
    // unset($deleteid);
    redirect('./');
    // die();


    // $deleteid = htmlspecialchars($_POST['deleteid']);
    // $teacherid = $_SESSION['teacherid'];
}


// if (empty($name)) {
//     $_SESSION['message'] = 'пустой name';
//     redirect('./');
//     die();
// }
// if (empty($teacherid)) {
//     $_SESSION['message'] = 'пустой teacherid';
//     redirect('./');
//     die();
// }
// if (empty($studentid)) {
//     $_SESSION['message'] = 'пустой studentid';
//     redirect('./');
//     die();
// }



// if ($conn->query("INSERT INTO `passes`(`studentid`, `teacherid`, `purposeid`, `date`, `gradeid`) VALUES ('$studentid','$teacherid','$purposeid','$date','$gradeid')") === true) {
//     // $_SESSION['message'] = 'Запись успешно внесена в БД';
//     // $studentid 
//     redirect('./');
// } else {
//     // $_SESSION['message'] = 'ощибка какаята';
//     redirect('./');
// }
