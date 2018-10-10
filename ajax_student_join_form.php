<?php
    include('config.php');
    //ไฟล์นี้ทำหน้าที่บันทึกข้อมูลที่จะ นักศึกษา เข้ามากรอกในหน้า form กรอกข้อมูลการ join class
    $data = $_POST;
    //print_r($data);
    //echo($data['scoreReq'][0]);

    //$myj = json_encode([$data['v'], $data['a'], $data['k']]);
    $myj = [$data['v'], $data['a'], $data['k']];


    for($x=0; $x < $data['nOfScore']; $x++) {
        $d =$data['scoreReq'][$x];
        array_push($myj, $d);
    }
    
    //for ($x = 0; $x <= 10; $x++) {
    //    echo "The number is: $x <br>";
    //} 


    $score= json_encode($myj);

    $sql = "INSERT INTO pre_data (class_id, std_email, score) 
            values(
                    '{$data['classid']}',
                    '{$data['std_email']}',
                    '{$score}'
            
            )";

    $mystr;
    /**/
    if(mysqli_query($conn, $sql) == false) {
        echo "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
        //echo $mystr;
    } else {
        echo "success";
    }

    //echo($sql);
            //print_r($myj);
?>