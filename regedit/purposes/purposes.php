<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();



if (isset($_POST['addpurpose'])) {
    $addpurpose = htmlspecialchars($_POST['addpurpose']);
    if (strlen($addpurpose) < 2) {
        $_SESSION['message'] = "<div id='message'>причина не должна быть пустой, йоу</div>";
        redirect('/regedit/purposes/');
    } else if ($conn->query("INSERT INTO `purpose`(`name`) VALUES ('$addpurpose')") === True) {
        $_SESSION['message'] = "<div id='message'>Причина добавлена</div>";
        redirect('/regedit/purposes/');
    } else {
        $_SESSION['message'] = "<div id='message'>ох уж эти ошибки))))знаете ли)))))))))))</div>";
        redirect('/regedit/purposes/');
    }
} else {
    if (isset($_POST['deleteid'])) {
        $deleteid = htmlspecialchars($_POST['deleteid']);
        $isusedanywhere = isset($conn->query("SELECT `id` FROM `passes` WHERE `purposeid` = '$deleteid'")->fetch_assoc()['id']);
        if ($isusedanywhere) {
            $_SESSION['message'] = "<div id='message'>Вы должны удалить все пропуски с этой причиной</div>";
            redirect('/regedit/purposes/');
        } else if ($conn->query("DELETE FROM `purpose` WHERE `id` = '$deleteid'") === True) {
            $_SESSION['message'] = "<div id='message'>Причина удалена</div>";
            redirect('/regedit/purposes/');
        } else {
            $_SESSION['message'] = "<div id='message'>ох уж эти ошибки))))знаете ли)))))))))))</div>";
            redirect('/regedit/purposes/');
        }
    } else if (isset($_POST['newpurposename']) && isset($_POST['id'])) {
        $newpurposename = htmlspecialchars($_POST['newpurposename']);
        $id = htmlspecialchars($_POST['id']);
        $isusedanywhere = isset($conn->query("SELECT `id` FROM `passes` WHERE `purposeid` = '$id'")->fetch_assoc()['id']);
        if ($isusedanywhere) {
            $_SESSION['message'] = "<div id='message'>Вы должны удалить все пропуски с этой причиной</div>";
            redirect('/regedit/purposes/');
        } else if ($conn->query("UPDATE `purpose` SET `name`='$newpurposename' WHERE `id` = '$if'") === True) {
            $_SESSION['message'] = "<div id='message'>Название причины изменено</div>";
            redirect('/regedit/purposes/');
        } else {
            $_SESSION['message'] = "<div id='message'>ох уж эти ошибки))))знаете ли)))))))))))</div>";
            redirect('/regedit/purposes/');
        }
    }
}
