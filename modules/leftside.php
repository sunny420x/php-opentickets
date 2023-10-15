<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require 'alert.php';
?>

<div class="leftside black">
    <button class="btn transparent text-white" id="collapse-btn" onclick="leftcollapse();">
        <i class="fa-solid fa-bars"></i>
    </button>
    <div id="leftcollapse">
    <img src="image/open-ticket-logo-small.png" alt="" class="w-100">
    <ul class="list">
        <a href="index.php" class="text-white"><li><i class="fa-solid fa-house mr-10"></i> Home</li></a>
        <a href="add_ticket.php" class="text-white"><li><i class="fa-solid fa-ticket mr-10"></i> New Tickets</li></a>
        <?php
        if($user_info['type'] == 0) {
        ?>
        <a href="users.php" class="text-white"><li><i class="fa-solid fa-user mr-10"></i> Users Management</li></a>
        <a href="setting.php" class="text-white"><li><i class="fa-solid fa-cog mr-10"></i> Setting</li></a>
        <?php
        }
        ?>
        <a href="change_password.php" class="text-white"><li><i class="fa-solid fa-cog mr-10"></i> Change Password</li></a>
        <a href="logout.php" class="text-white"><li><i class="fa-solid fa-right-from-bracket mr-10"></i> Logout</li></a>
    </ul>
    </div>
</div>
<script>
    checkcollapse();
</script>