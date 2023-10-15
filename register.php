<?php
session_start();

if(isset($_SESSION['user_session'])) {
    header('Location: index.php');
    die();
}

require 'database.php';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = hash("sha256", $_POST['password']);
    $type = 4;

    $register = mysqli_prepare($db, "INSERT INTO users(username,password,email,type) VALUES(?,?,?,?)");
    mysqli_stmt_bind_param($register, 'sssi', $username, $password, $email, $type);
    mysqli_stmt_execute($register);
    if(!mysqli_stmt_error($register)) {
        setcookie("alert", "register_success");
        header("Location: login.php");
        die();
    }
}
?>

<html>
    <head>
        <title>Register | PHP-OpenTickets</title>
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
            <h1 class="bl-darkblue">Register</h1>
            <form action="" method="post">
                <input type="text" name="username" id="" placeholder="Username">
                <input type="password" name="password" id="" placeholder="Password">
                <input type="email" name="email" id="" placeholder="Email">
                <input type="submit" value="Register" name="login" class="btn transparent ghost-lightred">
            </form>
            <i class="fa-solid fa-question"></i> already a member? <button onclick="window.location.href='login.php'" class="btn transparent ghost-darkblue">Login Now!</button>
        </div>
        </div>
    </body>
</html>