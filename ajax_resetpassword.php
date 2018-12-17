<?php
    include('config.php');

    $email = $_POST['email'];
    $pass  = $_POST['pass'];
?>

<?php
    function updataPassword($conn, $email, $pass) {
        $sql = "UPDATE `users` SET `password` = '{$pass}' WHERE `users`.`email` = '{$email}' ";

        if(mysqli_query($conn, $sql)) {
            echo("success");
        } else {
            echo("fail");
        }
    }
?>

<?php
    updataPassword($conn, $email, $pass);
?>