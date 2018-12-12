<?php
    include('config.php');

    $data = $_POST;
    
    $email      = $data['email'];
    $u_id       = $data['id'];
    $name       = $data['name'];
    $password   = $data['password'];
    $confirm    = $data['confirm'];
    $p_id       = $data['permission'];

    $checkEmail = checkEmail ($conn, $email);
    

    if($password != $confirm) {
        echo("ยืนยันรหัสผ่านไม่ถูกต้อง");
        return false;
    }

    

    if($checkEmail == "have") {
        echo("email");
        return false;
    } else {
        createUser($conn, $email, $u_id, $name, $password, $p_id);
    }
    


    function createUser($conn, $email, $u_id, $name, $password, $p_id) {
        $sql = "INSERT INTO `users`(
                    `email`,
                    `u_id`,
                    `name`,
                    `password`,
                    `v`,
                    `a`,
                    `k`,
                    `p_id`
                )
                VALUES(
                    '{$email}',
                    '{$u_id}',
                    '{$name}',
                    '{$password}',
                    '',
                    '',
                    '',
                    '{$p_id}'
                );";

        $result = mysqli_query($conn, $sql);
        
        if($result) {
            echo("success");
        } else {
            echo("ลงทะเบียนไม่สำเร็จ");
        }
    }


    function checkEmail ($conn, $email) {
        $sql = "SELECT id FROM users WHERE email = '{$email}'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            return "have";
        }
    }
?>