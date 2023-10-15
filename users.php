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

$sql = "SELECT u.id, u.username, u.email, u.type, t.name as type_name FROM users as u JOIN users_type as t ON u.type = t.type";

if(isset($_GET['type'])) {
    $type = $_GET['type'];
    if($type != "") {
        $sql = $sql." WHERE u.type = '".mysqli_real_escape_string($db, $type)."'";
    }
}

$sql = $sql." ORDER BY u.id DESC";
?>
<html>
    <head>
        <title>Users | PHP-OpenTickets</title>
        <?php include 'modules/head.php'; ?>
    </head>
    <body>
        <div class="admin-container">
            <?php include('modules/leftside.php'); ?>
            <?php
            //Add User
            if(isset($_POST['add_user'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = hash('sha256',$_POST['password']);
                $type = $_POST['type'];

                if($type == "" || $username == "" || $email == "") {
                    echo "<script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Please fill all the required information such as username, password and type.',
                    })
                    </script>";
                } else {
                    $add_user = mysqli_prepare($db,"INSERT INTO users(username,password,email,type) VALUES(?,?,?,?)");
                    mysqli_stmt_bind_param($add_user, 'ssss', $username, $password, $email, $type);
                    if(!mysqli_stmt_execute($add_user)) {
                        echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '".mysqli_stmt_errno($add_user)."',
                        })
                        </script>";
                    } else {
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Added User: ".$username." Success!',
                        })
                        </script>";
                    }
                    mysqli_stmt_close($add_user);
                }
            }
            ?>
            <div class="container white pb-50">
                <h1 class="bb-lightred"><i class="fa-solid fa-user mr-10"></i>Users Management</h1>
                <div class="table-overflow">
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Type</th>
                        </tr>
                        <?php
                        $get_user = mysqli_query($db, $sql);
                        while($row = mysqli_fetch_array($get_user)) {
                        ?>
                        <tr>
                            <td><?=$row['id']?></td>
                            <td><a href="user.php?id=<?=$row['id']?>" class="link"><?=$row['username']?></a></td>
                            <td><?=$row['email']?></td>
                            <td><?=$row['type_name']?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
                <br>
                <h2 class="bb-darkblue"><i class="fa-solid fa-user-plus mr-10"></i>Add User</h2>
                <form action="" method="post">
                    <input type="text" name="username" placeholder="Username" autocomplete="off" required>
                    <input type="password" name="password" placeholder="Password" autocomplete="off" required>
                    <input type="email" name="email" placeholder="Email" autocomplete="off" required>
                    ประเภท:
                    <select name="type" id="">
                        <option value="">Choose User Type</option>
                        <?php
                        while($user_type = mysqli_fetch_array($get_user_type)) {
                        ?>
                        <option value="<?=$user_type['type']?>"><?=$user_type['name']?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" value="Add User" name="add_user" class="btn darkblue">
                </form>
            </div>
        </div>
        <?php include('modules/footer.php');?>
    </body>
</html>

<?php
mysqli_close($db);
?>