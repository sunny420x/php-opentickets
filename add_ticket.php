<?php
session_start();

if(!isset($_SESSION['user_session'])) {
    header("Location: login.php");
    die();
}

require 'database.php';
require 'modules/get_user_info.php';
require 'modules/get_ticket_type.php';
?>
<html>
    <head>
        <title>Add Ticket | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <?php
    if(isset($_POST['send_ticket'])) {
        $title = $_POST['title'];
        $contents = $_POST['contents'];
        $priority = $_POST['priority'];
        $type = $_POST['type'];

        $insert = mysqli_prepare($db, "INSERT INTO tickets(title,contents,priority,type,time,user_id) VALUES(?,?,?,?,?,?)");
        mysqli_stmt_bind_param($insert, 'ssssss', $title, $contents, $priority, $type, $datetime, $user_info['id']);
        mysqli_stmt_execute($insert);
        if(mysqli_stmt_error($insert)) {
            die("Error ".mysqli_stmt_error($insert));
        } else {
            setcookie("alert", "successfully_add_ticket");
            header("Location: index.php");
            die();
        }
    }
    ?>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <div class="container white pb-50">
                <h1 class="bb-red"><i class="fa-solid fa-ticket mr-10"></i>Add Tickets</h1>
                <form action="" method="post">
                    <h3 class="mb-0">Title:</h3><input type="text" name="title" maxlength="100" required>
                    <h3 class="mb-0">Content:</h3>
                    <textarea name="contents" rows="10" style="width: 100%;" required maxlength="16777215"></textarea>
                    <h3 class="mb-0">Priority:</h3>
                    <select name="priority">
                        <option value="5">Lowest</option>
                        <option value="4">Low</option>
                        <option value="3">Moderate</option>
                        <option value="2">High</option>
                        <option value="1">Critical</option>
                    </select>
                    <h3 class="mb-0">Category:</h3>
                    <select name="type">
                        <?php
                        while($ticket_type = mysqli_fetch_array($get_ticket_type)) {
                        ?>
                        <option value="<?=$ticket_type['type']?>"><?=$ticket_type['name']?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p class="tab">
                        วันที่ เวลา: <?=$datetime;?>
                    </p>
                    <input type="submit" name="send_ticket" value="Add Ticket" class="btn red">
                </form>
            </div>
        </div>
        <?php include('modules/footer.php');?>
    </body>
</html>