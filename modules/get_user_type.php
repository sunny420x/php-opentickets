<?php
$get_user_type = mysqli_query($db, "SELECT t.id, t.type,t.name FROM users_type as t ORDER BY t.id ASC");
?>