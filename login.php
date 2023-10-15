<?php
session_start();

if(isset($_SESSION['user_session'])) {
    header('Location: index.php');
    die();
}

require 'database.php';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = hash("sha256", $_POST['password']);

    $check_user = mysqli_prepare($db, "SELECT * FROM users WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($check_user, 'ss', $username, $password);
    mysqli_stmt_execute($check_user);
    mysqli_stmt_store_result($check_user);
    if(mysqli_stmt_num_rows($check_user) == 1) {
        $_SESSION['user_session'] = $username.":".$password;
        header("Location: index.php");
        die();
    } else {
        setcookie("alert", "fail_login");
        header("Location: login.php");
        die();
    }
}
?>

<html>
    <head>
        <title>Login | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
        <style>
            :root {
                --container-max-width: 500px;
            }
        </style>
    </head>
    <body class="black">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <?php
        require 'alert.php';
        ?>
        <div class="container" id="main">
        <div class="card mx-auto white rounded p-30" style="width: 100%; box-sizing: border-box;">
            <img src="image/open-ticket-logo.png" class="w-100" alt="OpenTicket Logo">
            <h1 class="bl-darkblue">Login</h1>
            <form action="" method="post">
                <input type="text" name="username" id="" placeholder="Username">
                <input type="password" name="password" id="" placeholder="Password">
                <input type="submit" value="Login" name="login" class="btn transparent ghost-darkblue">
            </form>
            <i class="fa-solid fa-question"></i> Not a member? <button onclick="window.location.href='register.php'" class="btn transparent ghost-lightred">Register Now!</button>
        </div>
        </div>
    </body>
</html>