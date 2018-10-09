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
    

    if($checkDate == 0) {
        $data_start = null;
        $data_end   = null;
    } else if($checkDate == 1) {
        $data_start = $data['date_start'];
        $data_end   = $data['date_end'];
    }
    

    $sql = "INSERT INTO `class` 
                        (`title`, 
                        `teacher_email`, 
                        `description`, 
                        `password`, 
                        `pergroup`, 
                        `v`, 
                        `a`, 
                        `k`, 
                        `date_start`, 
                        `date_end`) 
            values(
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
    /*
    if(mysqli_query($conn, $sql) == false) {
        $mystr = "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
    }
    */
    if(count($prev) > 0) {
        foreach ($prev as $x) {
            //echo "{$x} <br/>";
            echo (sha1($x[0]) . "<br>");
            
        }

    } else {
        echo "None";
    }
  
?>
