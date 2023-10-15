<?php
session_start();

if(!isset($_SESSION['user_session'])) {
    header("Location: login.php");
    die();
}

require 'database.php';
require 'modules/get_user_info.php';
?>
<html>
    <head>
        <title>Change Password | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <?php
            if(isset($_POST['edit_user'])) {
                $password = hash('sha256', $_POST['password']);
                $old_password = hash('sha256', $_POST['old_password']);

                $check_password = mysqli_prepare($db, "SELECT * FROM users WHERE id = ? AND password = ?");
                mysqli_stmt_bind_param($check_password, 'ss', $user_info['id'], $old_password);
                mysqli_stmt_execute($check_password);
                mysqli_stmt_store_result($check_password);
                if(mysqli_stmt_num_rows($check_password) == 1) {
                    $edit_user = mysqli_prepare($db, "UPDATE users SET password = ? WHERE id = ?");
                    mysqli_stmt_bind_param($edit_user, 'ss', $password, $user_info['id']);
                    if(mysqli_stmt_execute($edit_user)) {
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully Change Your Password!',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href='logout.php';
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
                } else {
                    echo "<script>
                    Swal.fire({
                        icoon: 'error',
                        title: 'Current password does not match.'
                    })
                    </script>";
                }
            }
            ?>
            <div class="container white pb-50">
                <h1 class="bb-blue"><i class="fa-solid fa-user mr-10"></i>Change Password</h1>
                <form action="" method="post">
                    <input type="password" name="old_password" placeholder="Current Password" autocomplete="off">
                    <input type="password" name="password" placeholder="New Password" autocomplete="off">
                    <input type="submit" value="Change Now!" name="edit_user" class="btn darkblue">
                </form>
            </div>
        </div>
        <?php include('modules/footer.php');?>
    </body>
</html>

<?php
mysqli_close($db);
?>