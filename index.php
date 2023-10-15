<?php
session_start();

if(!isset($_SESSION['user_session'])) {
    header("Location: login.php");
    die();
}

$sql = "SELECT t.id, t.title, t.user_id, t.priority, t.status, t.type, t.time, u.username, tt.name as type_name, tt.color as type_color, p.color as priority_color, p.name as priority_name FROM tickets as t JOIN users as u ON u.id = t.user_id JOIN ticket_type as tt ON tt.type = t.type JOIN priority as p ON p.type = t.priority";

require 'database.php';
require 'modules/get_user_info.php';
require 'modules/get_ticket_type.php';

if(isset($_GET['ordering'])) {
    $ordering = $_GET['ordering'];
    if($ordering != "") {
        $sql = $sql." WHERE t.status = '".mysqli_real_escape_string($db, $ordering)."'";
    }
    if(isset($_GET['type'])) {
        $type = $_GET['type'];
        if($type != "") {
            $sql = $sql." AND t.type = '".mysqli_real_escape_string($db, $type)."'";
        }
    }
    if(isset($_GET['priority'])) {
        $priority = $_GET['priority'];
        if($priority != "") {
            $sql = $sql." AND t.priority = '".mysqli_real_escape_string($db, $priority)."'";
        } 
    }
} else {
    if(isset($_GET['type'])) {
        $type = $_GET['type'];
        if($type != "") {
            $sql = $sql." WHERE t.type = '".mysqli_real_escape_string($db, $type)."'";   
        }
    }
    if(isset($_GET['priority'])) {
        $priority = $_GET['priority'];
        if($priority != "") {
            $sql = $sql." WHERE t.priority = '".mysqli_real_escape_string($db, $priority)."'";
        } 
    }
}

if($user_info['type'] == 4) {
    $sql = $sql." AND user_id = ".mysqli_real_escape_string($db, $user_info['id'])." ORDER BY t.id DESC";
} else {
    $sql = $sql." ORDER BY t.id DESC";
}
?>
<html>
    <head>
        <title>Home | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <div class="container white pb-50">
                <div class="grid-3-9 white h-max white-transparent rounded-border p-10 px-20" style="border: 1.5px solid var(--grey);">
                    <img src="image/open-ticket-logo.png" alt="Opent Ticket Logo" width="250px" class="my-auto">
                    <div class="">
                    <h1 class="mt-5">ยินดีต้อนรับ <?=$user_info['username']?></h1>
                    <p>PHP-OpenTickets is opentickets system on website for customers to report technical or others issues to both admins and developer.</p>
                    </div>
                </div>
                <h1 class="bl-orange"><i class="fa-solid fa-ticket mr-10"></i>Tickets</h1>
                <div class="tab">
                    <form action="" method="get">
                        Status:
                        <select name="ordering" id="">
                            <option <?php if(!isset($_GET['ordering'])) {echo "selected";} ?> value="">All</option>
                            <option value="1" <?php
                            if(isset($_GET['ordering'])) { if($_GET['ordering'] == 1) {echo 'selected';} }
                            ?>>Opened Tickets</option>
                            <option value="0" <?php
                            if(isset($_GET['ordering'])) { if($_GET['ordering'] == 0) {echo 'selected';} }
                            ?>>Closed Tickets</option>
                        </select>
                        Category:
                        <select name="type" id="">
                            <option <?php if(!isset($_GET['type'])) {echo "selected";} ?> value="">All</option>
                            <?php
                            while($ticket_type = mysqli_fetch_array($get_ticket_type)) {
                            ?>
                            <option value="<?=$ticket_type['type']?>" <?php
                            if(isset($_GET['type'])) { if($_GET['type'] == $ticket_type['type']) {echo 'selected';} }
                            ?>><?=$ticket_type['name']?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <button class="btn darkblue">จัดเรียง</button>
                    </form>
                </div>
                <div class="table-overflow">
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>By</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Date</th>
                        </tr>
                        <?php
                        $get_tickets = mysqli_query($db, $sql);
                        while($row = mysqli_fetch_array($get_tickets)) {
                        ?>
                        <tr>
                            <td><?=$row['id']?></td>
                            <td><a href="view.php?id=<?=$row['id']?>" class="link"><?=$row['title']?></a></td>
                            <td><?=$row['username']?></td>
                            <td><?php 
                            if($row['status'] == 1) {  
                            ?>
                                <button class='btn darkblue' onclick="window.location.href='?ordering=1'">Open</button>
                            <?php
                            }
                            ?>
                            <?php
                            if($row['status'] == 0) {
                            ?>
                                <button class='btn red' onclick="window.location.href='?ordering=0'">Close</button>
                            <?php
                            }
                            ?></td>
                            <td><button class="btn <?=$row['type_color']?>" onclick="window.location.href='?type=<?=$row['type']?>'"><?=$row['type_name']?></button></td>
                            <td><button class="btn <?=$row['priority_color']?>" onclick="window.location.href='?priority=<?=$row['priority']?>'"><?=$row['priority_name']?></td></button>
                            <td><?=$row['time']?></td>
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