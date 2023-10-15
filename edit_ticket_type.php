<?php
session_start();

if(!isset($_SESSION['user_session'])) {
    header("Location: login.php");
    die();
}

require 'database.php';
require 'modules/get_user_info.php';

//Allow Only System_Owner
if($user_info['type'] != 0) {
    setcookie('alert', 'permission_denial');
    header("Location: index.php");
    die();
}

if(isset($_GET['id'])) {
    $get_type = mysqli_prepare($db, "SELECT * FROM ticket_type WHERE type = ?");
    mysqli_stmt_bind_param($get_type, 's', $_GET['id']);
    mysqli_stmt_execute($get_type);
    $type_result = mysqli_stmt_get_result($get_type);
    $type_row = mysqli_fetch_array($type_result);
    mysqli_stmt_close($get_type);
} else {
    header("Location: setting.php");
    die();
}
?>
<html>
    <head>
        <title>Ticket Category Manager | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <?php
            if(isset($_POST['edit_type'])) {
                $id = $_POST['id'];
                $type = $_POST['type'];
                $name = $_POST['name'];

                $sql = mysqli_prepare($db, "UPDATE ticket_type SET type = ?, name = ? WHERE id = ?");
                mysqli_stmt_bind_param($sql, 'sss', $type, $name, $id);
                mysqli_stmt_execute($sql);

                if(!mysqli_stmt_error($sql)) {
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
                        title: 'There are some error.',
                        content: ".mysqli_connect_errno($sql).",
                    })
                    </script>";
                }
            }
            ?>
            <div class="container white pb-50">
                <h1 class="bb-blue"><i class="fa-solid fa-user mr-10"></i>Ticket Category Manager.</h1>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?=$type_row['id']?>">
                    <input type="text" name="type" value="<?=$type_row['type']?>">
                    <input type="text" name="name" value="<?=$type_row['name']?>">
                    <input type="submit" value="Save" class="btn darkblue" name="edit_type">
                </form>
            </div>
        </div>
        <?php include('modules/footer.php');?>
    </body>
</html>

<?php
mysqli_close($db);
?>