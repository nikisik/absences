<?php require_once __DIR__ . '/src/helpers.php';
if (!isset($_SESSION['teacherid'])) {
    $_SESSION['teacherid'] = 0;
}
if ($_SESSION['teacherid'] > 0) {
    redirect('/home');
}

$keyicon =  '<svg style="fill:azure;" height="20" width="20" viewBox="0 0 600 400"><path d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0 160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-40 40 0c13.3 0 24-10.7 24-24l0-40 40 0c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>';
$lockicon = '<svg style="fill:azure;" height="20" width="20" viewBox="0 0 600 400"><path d="M128 96l0 64 128 0 0-64c0-35.3-28.7-64-64-64s-64 28.7-64 64zM64 160l0-64C64 25.3 121.3-32 192-32S320 25.3 320 96l0 64c35.3 0 64 28.7 64 64l0 224c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 224c0-35.3 28.7-64 64-64z"/></svg>';

?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> -->
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
                <!-- <i class='bx bxs-envelope'></i> -->
                <i style="height:20px;width:20px;"><?php echo $keyicon; ?></i>
            </div>

            <div class="input-box">
                <b><input type="password" id="password" name="password" placeholder="Введите пароль" required></b>
                <!-- <i class='bx bxs-lock-alt'></i> -->
                <i style="height:20px;width:20px;"><?php echo $lockicon; ?></i>
            </div>

            <!-- <div>
                <p class="register">У меня еще нет <b><a href="register.php">аккаунта</a></b>.</p>
            </div> -->

            <button class="btn" type="submit" id="submit"><b>Продолжить</b></button><br><br>
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div style='color:rgba(255, 0, 34, 1);'><b>";
                echo $_SESSION['message'];
                echo "</b></div>";
                unset($_SESSION['message']);
            }
            // echo htmlspecialchars('<script>alert(\'xss!\')</script>');
            userinfo();


            // checkAuth();



            ?>
        </form>
        
    </div>

</body>

</html>