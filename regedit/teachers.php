<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();


// if (!isset($_SESSION['teacherid'])) {
//     $_SESSION['teacherid'] = 0;
// }
// if ($_SESSION['teacherid'] == 0) {
//     redirect('../');
// }

?>



<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create DB record</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <style>
        #flexbox {
            display: flex;
            justify-content: center;
            align-items: center;
            /* height: 100%; */
            min-width: 450px;
        }

        form {
            margin-top: 150px;
        }

        form input {
            width: 250px;
            height: 40px;
            margin-top: 20px;
            background-color: rgba(154, 23, 216, .2);
            border: 0;
            border-radius: 5px;
            outline: none;
        }

        form input::placeholder {
            color: rgba(50, 50, 140, .5);
            font-weight: 700;
        }


        form button {
            width: 100%;
            height: 40px;
            margin-top: 20px;
            border-color: rgba(174, 23, 156, .4);
            border-radius: 5px;
        }

        form button:hover {
            background-color: rgba(174, 23, 156, .3);
        }

        #back {
            width: 100px;
            height: 40px;
            /* margin-top: 20px; */
            border-color: rgba(174, 23, 156, .4);
            border-radius: 5px;
        }

        select {
            min-height: 5vh;
            width: 100%;
            margin-top: 20px;
            background-color: rgba(154, 23, 216, .2);
            border: 0;
            border-radius: 5px;
            outline: none;
        }
    </style>

</head>

<body>
    <button id="back" style="position:absolute; " onclick="window.location='../home/';">Назад</button>


    <div id="flexbox">
        <form method="POST" action="./actions/teachers.php">
            <input type="text" name="login" placeholder="login" require><br>
            <input type="text" name="password" placeholder="password" require><br>
            <input type="text" name="name" placeholder="name" require><br>
            <!-- <input type="text" name="gradeid" placeholder="gradeid" require><br>-->
            <?php
            $rows = $conn->query("SELECT `id`, `gradename` FROM `grades` ORDER BY `gradename`");
            echo "<select name='gradeid'>";
            foreach ($rows as $row) {
                $gradename = $row['gradename'];
                if ($gradename == '00') {
                    continue;
                }
                $id = $row['id'];
                echo "<option value='$id'>$gradename</option>";
            }
            echo "</select><br>";
            ?>
            <button type="submit">Сделать запись</button>
            <?php //DEBUG

            userinfo();

            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            ?>
        </form>
    </div>
</body>

</html>