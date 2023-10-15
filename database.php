<?php
$db = mysqli_connect('host', 'username', 'password', 'database');
if(mysqli_connect_errno()) {
    die(mysqli_connect_error());
}
?>