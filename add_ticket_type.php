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
?>
<html>
    <head>
        <title>Add Ticket Category | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <?php
            if(isset($_POST['add_type'])) {
                $type = $_POST['type'];
                if(!preg_match("/^[0-9]+$/", $type)) {
                    echo "<script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Type ID must be numbers.',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href='add_ticket_type.php';
                        }
                    })
                    </script>";
                }
                $name = $_POST['name'];
                $color = $_POST['color'];

                $sql = mysqli_prepare($db, "INSERT INTO ticket_type(type,name,color) VALUES(?,?,?)");
                mysqli_stmt_bind_param($sql, 'sss', $type, $name, $color);
                mysqli_stmt_execute($sql);

                if(!mysqli_stmt_error($sql)) {
                    echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Add new category success!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href='setting.php';
                        }
                    })
                    </script>";
                } else {
                    echo "<script>
                    Swal.fire({
                        icoon: 'error',
                        title: 'There are some errors with this request..',
                        content: ".mysqli_connect_errno($sql).",
                    })
                    </script>";
                }
            }
            ?>
            <div class="container white pb-50">
                <h1 class="bb-blue"><i class="fa-solid fa-user mr-10"></i>Add Ticket Category</h1>
                <form action="" method="post">
                    <input type="text" name="type" placeholder="Category ID">
                    <input type="text" name="name" placeholder="Category Name">
                    <input type="text" name="color" placeholder="Color">
                    <input type="submit" value="Add" class="btn darkblue" name="add_type">
                </form>
            </div>
        </div>
        <?php include('modules/footer.php');?>
    </body>
</html>

<?php
mysqli_close($db);
?>