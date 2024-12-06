<?php require_once __DIR__ . '/src/helpers.php';
if (!isset($_SESSION['teacherid'])) {
    $_SESSION['teacherid'] = 0;
}
if ($_SESSION['teacherid'] > 0) {
    redirect('/home');
}

?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <style>
        body {
            font-family: 'Poppins';
            font-size: 16px;
        }
    </style>

</head>

<body class="sign">


    <div class="wrapper">

        <form action="src/actions/login.php" method="POST">

            <h1>Login</h1>
            <div class="input-box">
                <b><input type="login" id="login" name="login" placeholder="Введите логин" required></b>
                <i class='bx bxs-envelope'></i>
            </div>

            <div class="input-box">
                <b><input type="password" id="password" name="password" placeholder="Введите пароль" required></b>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <!-- <div>
                <p class="register">У меня еще нет <b><a href="register.php">аккаунта</a></b>.</p>
            </div> -->

            <button class="btn" type="submit" id="submit"><b>Продолжить</b></button><br><br>

        </form>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='msg' id='messagebox'><b>";
            echo $_SESSION['message'];
            echo "</b></div>";
            unset($_SESSION['message']);
        }
        // echo htmlspecialchars('<script>alert(\'xss!\')</script>');
        userinfo();


        // checkAuth();



        ?>
    </div>

</body>

</html>