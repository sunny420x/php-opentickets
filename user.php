<?php
session_start();

if(!isset($_SESSION['user_session'])) {
    header("Location: login.php");
    die();
}

require 'database.php';
require 'modules/get_user_info.php';
require 'modules/get_user_type.php';

//Allow Only System_Owner
if($user_info['type'] != 0) {
    setcookie('alert', 'permission_denial');
    header("Location: index.php");
    die();
}

if(isset($_GET['id'])) {
    $sql = mysqli_prepare($db,"SELECT u.id, u.username, u.type, t.name as type_name FROM users as u JOIN users_type as t ON u.type = t.type WHERE u.id = ?");
    mysqli_stmt_bind_param($sql, 's', $_GET['id']);
    mysqli_stmt_execute($sql);
    $user_result = mysqli_stmt_get_result($sql);
    $user_row = mysqli_fetch_array($user_result);
} else {
    header("Location: users.php");
    die();
}

?>
<html>
    <head>
        <title>Users Management | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <?php
            if(isset($_POST['edit_user'])) {
                $username = $_POST['username'];
                $type = $_POST['type'];

                $edit_user = mysqli_prepare($db, "UPDATE users SET username = ?, type = ? WHERE id = ?");
                mysqli_stmt_bind_param($edit_user, 'sss', $username, $type, $user_row['id']);
                if(mysqli_stmt_execute($edit_user)) {
                    echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Successfully Updating Data!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    })
                    </script>";
                } else {
                    echo "<script>
                    Swal.fire({
                        icoon: 'error',
                        title: 'There are some error..',
                        content: ".mysqli_connect_errno($edit_user).",
                    })
                    </script>";
                }
            }
            ?>
            <div class="container white pb-50">
                <h1 class="bb-blue"><i class="fa-solid fa-user mr-10"></i>Manage User: <?=htmlentities($user_row['username'])?></h1>
                <form action="" method="post">
                    <input type="text" name="username" placeholder="Username" autocomplete="off" value="<?=$user_row['username']?>">
                    ประเภท:
                    <select name="type" id="">
                        <?php
                        while($user_type = mysqli_fetch_array($get_user_type)) {
                        ?>
                        <option value="<?=$user_type['type']?>" <?php
                        if($user_type['type'] == $user_row['type']) {echo "Selected";}
                        ?>><?=$user_type['name']?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" value="Save" name="edit_user" class="btn darkblue">
                </form>
            </div>
        </div>
        <?php include('modules/footer.php');?>
    </body>
</html>

<?php
mysqli_close($db);
?>