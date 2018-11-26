<?php
    include('config.php');

    $data      = $_POST;
    $class_id  = $data['class_id'];
    $email     = $data['email'];
    $score     = $data['score'];
    $subject   = $data['subject'];
    $pass      = "";
    $pass      = $data['pass'];
    $n         = count($score);
    $mystr;

    $checkPassword = checkPassword($conn, $class_id, $pass);

    function checkPassword($conn, $class_id, $pass) {
        $sql = "SELECT class.password FROM class where class.id = '{$class_id}' ";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()) {
                $password = $row['password'];

                if($password == $pass) {
                    return true;
                } else {
                    return false;
                }
                
            }
        }
        return false;
    }

    if ($checkPassword) {
        for($i=0; $i<$n; $i++) {
            $sql = "INSERT INTO `student_score`(
                `class_id`,
                `subject_title`,
                `std_email`,
                `score`
            )
            VALUES(
                '{$class_id}',
                '{$subject[$i]}',
                '{$email}',
                '{$score[$i]}'
            );";
    
            if(mysqli_query($conn, $sql) == false) {
                //$mystr = mysqli_error($conn);
                $mystr = "ลงทะเบียนไม่สำเร็จ";
            } else {
                $mystr = "ลงทะเบียนสำเร็จ";
            }
        }
    
        echo($mystr);
    } else {
        //echo("รหัสผ่านไม่ถูกต้อง");
        echo("PassError");
    }

    
?>

