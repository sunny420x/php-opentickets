<?php
if(isset($_COOKIE['alert'])) {
    $alert = $_COOKIE['alert'];
    if($alert == "permission_denial") {
        echo "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Sorry, You do not have permission...',
            text: 'This page are only accessible to admins.',
        })
        </script>
        ";
    }
    if($alert == "fail_login") {
        echo "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Sorry...',
            text: 'Your information does not match any rows.',
            footer: 'Recheck your username and password infomation.'
        })
        </script>
        ";
    }
    if($alert == "register_success") {
        echo "
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Register Sucessfully!',
            text: 'You are now can login to the system.'
        })
        </script>
        ";
    }
    if($alert == "successfully_add_ticket") {
        echo "
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Add Ticket Successful!',
            text: 'Your tickets are now visible to admins.'
        })
        </script>
        ";
    }
    if($alert == "successfully_logout") {
        echo "
        <script>
        Swal.fire({
            icon: 'success',
            title: 'You are now logged out!'
        })
        </script>
        ";
    }
    setcookie('alert', '', 0);
}
?>