<?php
    include('config.php');

    $data      = $_POST;
    $class_id  = $data['class_id'];
    $email     = $data['email'];
    $score     = $data['score'];
    $subject   = $data['subject'];
    $n         = count($score);
    $mystr;

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
            $mystr = mysqli_error($conn);
        } else {
            $mystr = "Success";
        }
    }

    echo($mystr);
?>

