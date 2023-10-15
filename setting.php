<?php
session_start();

if(!isset($_SESSION['user_session'])) {
    header("Location: login.php");
    die();
}

require 'database.php';
require 'modules/get_user_info.php';
require 'modules/get_user_type.php';
require 'modules/get_ticket_type.php';

//Allow Only System_Owner
if($user_info['type'] != 0) {
    setcookie('alert', 'permission_denial');
    header("Location: index.php");
    die();
}

?>
<html>
    <head>
        <title>Setting | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <div class="container white pb-50">
                <h1 class="bb-blue"><i class="fa-solid fa-user mr-10"></i>Database Management.</h1>
                <h3>User Types:</h3>
                <div class="table-overflow">
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Content</th>
                        </tr>
                        <?php
                        while($user_type = mysqli_fetch_array($get_user_type)) {
                        ?>
                        <tr>
                            <td><?=$user_type['type']?></td>
                            <td><a href="edit_user_type.php?id=<?=$user_type['type']?>" class="link"><?=$user_type['name']?></a></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>

                <h3>Ticket Category <button class="btn blue" onclick="window.location.href='add_ticket_type.php'">Add</button></h3>
                <div class="table-overflow">
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Content</th>
                        </tr>
                        <?php
                        while($ticket_type = mysqli_fetch_array($get_ticket_type)) {
                        ?>
                        <tr>
                            <td><?=$ticket_type['id']?></td>
                            <td><?=$ticket_type['color']?></td>
                            <td><?=$ticket_type['type']?></td>
                            <td><a href="edit_ticket_type.php?id=<?=$ticket_type['type']?>" class="link"><?=$ticket_type['name']?></a></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>     
            </div>
        </div>
        <?php include('modules/footer.php');?>
    </body>
</html>

<?php
mysqli_close($db);
?>