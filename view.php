<?php
session_start();

if(!isset($_SESSION['user_session'])) {
    header("Location: login.php");
    die();
}

if(!isset($_GET['id'])) {
    header('Location: index.php');
    die();
} else {
    $id = $_GET['id'];
}

require 'database.php';
require 'modules/get_user_info.php';

if(isset($id)) {
    $get_info = mysqli_prepare($db,"SELECT t.id,t.title,t.contents,t.user_id,t.time,t.type,t.priority,t.status,tt.name as type_name,tt.color as type_color,tt.type as type_id, p.name as priority_name, p.color as priority_color, p.type as priority_type, u.username 
        FROM tickets as t JOIN ticket_type as tt ON tt.type = t.type 
        JOIN priority as p ON p.type = t.priority 
        JOIN users as u ON u.id = t.user_id WHERE t.id = ?");

    mysqli_stmt_bind_param($get_info, 's', $id);
    mysqli_stmt_execute($get_info);
    $result = mysqli_stmt_get_result($get_info);
    $row = mysqli_fetch_array($result);
    mysqli_stmt_close($get_info);
}
if($user_info['type'] == 4) {
    if($user_info['id'] != $row['user_id']) {
        setcookie('alert', 'permission_denial');
        header("Location: index.php");
        die();
    }
}
if(isset($_POST['update_ticket'])) {
    $ticket_id = $_POST['ticket_id'];
    $reply_content = $_POST['reply'];
    $status = $_POST['status'];

    if($reply_content != "") {
        $insert = mysqli_prepare($db, "INSERT INTO reply(ticket_id,reply,user_id) VALUES(?,?,?)");
        mysqli_stmt_bind_param($insert, 'sss', $ticket_id, $reply_content, $user_info['id']);
        mysqli_stmt_execute($insert);
        if(mysqli_stmt_error($insert)) {
            die(mysqli_stmt_error($insert));
        }
    }
    $update = mysqli_prepare($db, "UPDATE tickets SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, 'ss', $status, $ticket_id);
    mysqli_stmt_execute($update);
    if(mysqli_stmt_error($update)) {
        die(mysqli_stmt_error($update));
    }
    header("Location: view.php?id=".$id);
    die();
}
?>
<html>
    <head>
        <title><?=htmlentities($row['title'])?> | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <div class="container white pb-50">
                <h1 class="bb-red"><i class="fa-solid fa-ticket mr-10"></i>Ticket #<?=htmlentities($id)?></h1>
                <h2 class="mb-0">Title: <?=htmlentities($row['title'])?></h2>
                <h3>By: <?=htmlentities($row['username'])?></h3>
                <p class="mt-20 mb-5 bl-darkblue">
                    <?=$row['time'];?>
                </p>
                <div class="my-20">
                    Category: <button class="btn <?=$row['type_color']?>" onclick="window.location.href='index.php?type=<?=$row['type_id']?>'"><?=$row['type_name']?></button>
                    <div class="flex mt-10">
                        <div class="mr-10">Status:</div> 
                        <div class="btn w-max h-max <?php if($row['status'] == 0) {echo 'red';}else{echo"green";} ?>" onclick="window.location.href='index.php?ordering=<?=htmlentities($row['status']);?>'">
                        <?php if($row['status'] == 0) {echo "Closed";}else{echo "Open";}?></div>
                    </div>
                    <div class="flex mt-10">
                        <div class="mr-10">Priority:</div>
                        <button class="btn <?=$row['priority_color']?>" onclick="window.location.href='index.php?priority=<?=htmlentities($row['priority_type']);?>'"><?=$row['priority_name']?></button>
                    </div>
                </div>
                <h4 class="my-0">Content:</h4>
                <p class="grey rounded-border p-20">
                <?=htmlentities($row['contents'])?>
                </p>
                <div class="flex">
                <?php
                $get_reply = mysqli_prepare($db, "SELECT r.ticket_id, r.reply, r.user_id, u.username, u.type, t.name as user_type FROM reply as r JOIN users as u ON u.id = r.user_id JOIN users_type as t ON t.type = u.type WHERE r.ticket_id = ?");
                mysqli_stmt_bind_param($get_reply, 's', $id);
                mysqli_stmt_execute($get_reply);
                $result = mysqli_stmt_get_result($get_reply);
                while($reply = mysqli_fetch_array($result)) {
                ?>
                <div class="user_profile p-20 rounded-border ml-0 mr-20 my-20 w-max">
                    <img src="image/default-avatar.jpg" width="100px" height="100px" class="rounded">
                    <div class="pl-20 pt-10">
                        <h3 class="m-0"><?=htmlentities($reply['username'])?></h3>
                        <p class="m-0 t-16 text-darkblue"><?=htmlentities($reply['user_type'])?></p>
                        <p class="m-0"><?=htmlentities($reply['reply'])?></p>
                    </div>
                </div>
                <?php
                }
                ?>
                </div>
                <h2 class="bl-green">Manage Ticket</h2>
                Status:
                <form action="" method="post">
                    <?php
                    if($user_info['type'] != 4) {
                    ?>
                    <select name="status">
                        <option value="0" <?php
                        if($row['status'] == 0) {
                            echo "selected";
                        }
                        ?>>Closed</option>
                        <option value="1"<?php
                        if($row['status'] == 1) {
                            echo "selected";
                        }
                        ?>>Open</option>
                    </select>
                    <?php
                    } else {
                    ?>
                        <input type="hidden" name="status" value="<?=$row['status']?>">
                    <?php
                    }
                    ?>
                    <input type="hidden" name="ticket_id" value="<?=$id?>">
                    <textarea name="reply" style="width: 100%;" rows="10"></textarea>
                    <input type="submit" name="update_ticket" value="Update Ticket" class="btn green">
                </form>
            </div>
        </div>
        <?php include('modules/footer.php');?>
    </body>
</html>