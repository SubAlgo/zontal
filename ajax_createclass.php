<?php
    include('config.php');

    $data = $_POST;

    $classid    = "";
    $subject    = $data['subject'];
    $desc       = $data['desc'];
    $teacher    = $data['teacher'];
    $pass       = $data['pass'];
    $perGroup   = $data['perGroup'];
    $v          = $data['v'];
    $a          = $data['a'];
    $k          = $data['k'];
    $prev = [];
    $checkDate  = $data['checkDate'];

    //เตรียมข้อมูล รายวิชาที่ต้องผ่าน
    if(isset($data['prev'])) {
        $prev = $data['prev'][0];
    }
    
    //-----สร้าง CLASS_ID-----
    //1. check ว่า id run ไปถึงรหัสไหนแล้ว
    //2. เอา id ล่าสุด + 1 แล้วผสมกับคำว่า "class" เช่น class1
    $sqlCheckClassId = "SELECT COUNT('id') as total FROM gen_classid";
    $resultCheck = mysqli_query($conn, $sqlCheckClassId);
    $r =  $resultCheck->fetch_row();

    $classid = "class".($r[0]+1);
    echo $classid;
    //-----สร้าง CLASS_ID-----
    

    if($checkDate == 0) {
        $data_start = null;
        $data_end   = null;
    } else if($checkDate == 1) {
        $data_start = $data['date_start'];
        $data_end   = $data['date_end'];
    }
    
    //-----เพิ่มค่าในตาราง class-----
    $sql = "INSERT INTO `class` 
                        (`id`,
                        `title`, 
                        `teacher_email`, 
                        `description`, 
                        `password`, 
                        `pergroup`, 
                        `v`, 
                        `a`, 
                        `k`, 
                        `date_start`, 
                        `date_end`) 

            values(     '{$classid}',
                        '{$subject}',
                        '{$teacher}',
                        '{$desc}',
                        '{$pass}',
                        '{$perGroup}',
                        '{$v}',
                        '{$a}',
                        '{$k}',
                        '{$data_start}',
                        '{$data_end}'
                    ) ";

    $mystr;
    /**/
    if(mysqli_query($conn, $sql) == false) {
        $mystr = "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
        echo $mystr;
    } else {
        echo "success";
    }

    //-----เพิ่มค่าในตาราง gen_classid-----
    
    $sql_plusclassid = "INSERT INTO `gen_classid` (`id`, `title`) VALUES (NULL, '{$classid}')";
    if(mysqli_query($conn, $sql_plusclassid) == false) {
        $mystr = "<div align='center'><b> Error on gen_classid : " .  mysqli_error($conn)."</b></div>";
        echo $mystr;
    } else {
        echo "success";
    }

    //----- เพิ่มค่าในตาราง subject_req
    if(count($prev) > 0) {
        foreach ($prev as $x) {
            $sql_subjectreq = "INSERT INTO `subject_req` (`title`, `class_id`) values('{$x}', '{$classid}')";
            if(mysqli_query($conn, $sql_subjectreq) == false) {
                $mystr = "<div align='center'><b> Error on subject : " .  mysqli_error($conn)."</b></div>";
                echo $mystr;
            }
        }

    } else {
        //echo "None";
    }
    
  
?>
