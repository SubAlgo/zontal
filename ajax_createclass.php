<?php
    include('config.php');

    $data = $_POST;

    $classid        = "";
    $subject        = $data['subject'];
    $desc           = $data['desc'];
    $teacher        = $data['teacher'];
    $pass           = $data['pass'];
    $perGroup       = $data['perGroup'];
    $nGroup         = $data['nGroup'];
    $student_limit  = $data['nStudent'];
    $v              = $data['v'];
    $a              = $data['a'];
    $k              = $data['k'];
    $prev           = [];
    $checkDate      = $data['checkDate'];

    //เตรียมข้อมูล รายวิชาที่ต้องผ่าน
    if(isset($data['prev'])) {
        $prev = $data['prev'][0];
    }
    
    //-----สร้าง CLASS_ID-----
    
    function createClassid($conn) {
        $sql        = "SELECT title FROM gen_classid ORDER BY title DESC";
        $result     = mysqli_query($conn, $sql);
        $r          = mysqli_fetch_array($result);
        $lasteID    = $r['title'];
        $cut1       = substr($lasteID,0,5);
        $cut2       = substr($lasteID,5);
        $cid        = $cut2+1;
        $newID      = "{$cut1}".$cid;

        return $newID;
    }
    $classid = createClassid($conn);
    
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
                        `n_group`,
                        `student_limit`,
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
                        '{$nGroup}',
                        '{$student_limit}',
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
