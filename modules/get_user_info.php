<?php
if(isset($_SESSION['user_session'])) {
    $session = $_SESSION['user_session'];
    $session = explode(':',$session);
    $username = $session[0];
    $password = $session[1];
    $get_user_info = mysqli_prepare($db, "SELECT * FROM users WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($get_user_info, 'ss', $username, $password);
    mysqli_stmt_execute($get_user_info);
    $result = mysqli_stmt_get_result($get_user_info);
    $user_info = mysqli_fetch_array($result);
    unset($username);
    unset($password);
    mysqli_stmt_close($get_user_info);
}
?>