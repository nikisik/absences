<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';
adminpage();
// if (!isadmin()) {
//     redirect('/src/actions/logout.php');
// }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create DB record</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <style>
        div {
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
    <div>
        <form method="POST" action="/regedit/actions/students.php">
            <input type="text" name="name" placeholder="name" require autofocus><br>
            <?php


            // $rows = $conn->query("SELECT `id`, `name`,`gradeid` FROM `teachers` ORDER BY `gradeid` DESC");
            // echo "<select name='teacherid'>";
            // foreach ($rows as $row) {
            //     $teachername = $row['name'];
            //     $teacherid = $row['id'];
            //     $gradeid = $row['gradeid'];
            //     // var_dump($row['gradeid']);
            //     // var_dump($_SESSION['getgrade']);
            //     echo "<option value='$teacherid' ";
            //     if ($teacherid == ($_SESSION['getteacherid'] ?? 0)) {
            //         echo 'selected';
            //     }
            //     echo ">$teachername</option>";
            // }
            // echo "</select><br>";




            $rows = $conn->query("SELECT `id`, `gradename` FROM `grades` ORDER BY `gradename`");
            echo "<select name='gradeid'>";

            foreach ($rows as $row) {
                $gradename = $row['gradename'];
                if ($gradename == '00') {
                    continue;
                }
                $id = $row['id'];
                echo "<option value='$id' ";
                if (($_SESSION['getgrade'] ?? null) == $gradename) {
                    echo "selected";
                }
                echo ">$gradename</option>";
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

            // var_dump();

            ?>
        </form>
    </div>
</body>

</html>