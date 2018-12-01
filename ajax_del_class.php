<?php
    include('config.php');

    //$classid = $_POST['data'];
    //echo(json_encode($_POST['classID']));
    $classid = $_POST['classID'];

    //ถ้า state = 0 หมายความว่า การลบข้อมูลสำเร็จ
    //ถ้ามีขั้นตอนไหนที่ผิดพลาด ค่า state จะถูกบวก
    $state = 0;


    $sql4 = "DELETE FROM subject_req WHERE subject_req.class_id = '{$classid}'";
    if (mysqli_query($conn, $sql4)) {
        //echo "success";
    } else {
        $state++;
    }

    
    $sql1 = "DELETE FROM gen_classid WHERE gen_classid.title = '{$classid}'";
    if (mysqli_query($conn, $sql1)) {
        //echo "success";
    } else {
        $state++;
    }


    $sql2 = "DELETE FROM result_grouped WHERE result_grouped.classid = '{$classid}'";
    if (mysqli_query($conn, $sql2)) {
        //echo "success";
    } else {
        $state++;
    }


    $sql3 = "DELETE FROM student_score WHERE student_score.class_id = '{$classid}'";
    if (mysqli_query($conn, $sql3)) {
        //echo "success";
    } else {
        $state++;
    }

    $sql = "DELETE FROM class WHERE class.id = '{$classid}'";
    if (mysqli_query($conn, $sql)) {
        //echo "success";
    } else {
        $state++;
    }

    echo($state);
?>