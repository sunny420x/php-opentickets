<?php
$get_ticket_type = mysqli_query($db, "SELECT id,type,name,color FROM ticket_type ORDER BY id ASC");
?>