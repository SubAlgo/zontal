<?php
    include('config.php');

    /**
     * ทำเพื่อ
     * เก็บผลลัพธ์การ Generate ไว้ใน DB เพื่อ เวลานักศึกษา หรือ ผู้เกี่ยวข้องต้องการดูว่าตัวเองอยู่กลุ่มไหนใน class 
     * ก็จะสามารถมาดูได้
     */
?>

<?php
    // DATA
    $data = $_POST;

    $arrGroup   = $_POST['group'];
    $arrClassid = $_POST['classid'];

    $objClassid = json_encode($data['classid']);
    $objGroup   = json_encode($data['group']);

    $classid    = $arrClassid;
    $group      = $objGroup;
    $score      = json_encode($data['score']);
   // $score      = (float)$score;

    $re         = false;

?>

<?php
    // function
    function tofloat($num) {
        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
       
        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        } 
    
        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
        );
    }

     //---------------------------------------
    
     //function สำหรับ check ว่า class ที่จะจัดกลุ่ม มีการจัดกลุ่มมาหรือยัง
     function checkHasData($conn, $classid) {
        $sql    = "SELECT id FROM `result_grouped` WHERE classid = '{$classid}'";
        $result = mysqli_query($conn, $sql);
        $check  = mysqli_num_rows($result);

        if($check > 0) {
            return true;
        } else {
            return false;
        }
     }


     //function ลบผลลัพธ์การจัดกลุ่มเดิม
     function delResultGrouped ($conn, $classid) {
        $sql = "DELETE FROM `result_grouped` WHERE `result_grouped`.`id` = '{$classid}'";

        if(mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
        //mysqli_close($conn);
     }

     //function สำหรับ update ข้อมูลในกรณีมีข้อมูลอยู่แล้ว
     function updateResultGrouped($conn, $classid, $group, $score) {
        $err = 0;
        $score = tofloat($score);
        $sql = "UPDATE `result_grouped` SET `result` = '{$group}' WHERE `result_grouped`.`id` = '{$classid}'";
        $sql2 = "UPDATE `class` SET `fitness_score` = '{$score}' WHERE `class`.`id` = '{$classid}'";
       // $sql3 = "UPDATE `class` SET `fitness_score` = '3.1458399488659916' WHERE `class`.`id` = 'class3'; "
        
        //เก็บผลลัพธ์การจัดกลุ่ม
        if(mysqli_query($conn, $sql)) {
            $err += 0;
        } else {
            $err += 1;
        }

        //เก็บผลคะแนนการจัดกลุ่ม
        if(mysqli_query($conn, $sql2)) {
            $err += 0;
        } else {
            $err += 1;
        }
        
        //ถ้ามี error ให้ return false
        if($err == 0) {
            return true;
        } else {
            return false;
        }
     }

     //function บันทึกข้อมูล
     function insertResultGrouped($conn, $classid, $group, $score) {
        $err = 0;
        $score = tofloat($score);
        $sql = "INSERT INTO result_grouped (classid, result)
                VALUES ('{$classid}' , '{$group}')
                ";
        
        $sql2 = "UPDATE `class` SET `fitness_score` = '{$score}' WHERE `class`.`id` = '{$classid}'";

        //เก็บผลลัพธ์การจัดกลุ่ม
        if(mysqli_query($conn, $sql)) {
            $err += 0;
        } else {
            $err += 1;
        }

        //เก็บผลคะแนนการจัดกลุ่ม
        if(mysqli_query($conn, $sql2)) {
            $err += 0;
        } else {
            $err += 1;
        }
        
        //ถ้ามี error ให้ return false
        if($err == 0) {
            return true;
        } else {
            return false;
        }
     }

?>

<?php
    //check ว่ามีข้อมูลถูกบันทึกหรือไม่
    //ถ้ามีจะ update
    //ถ้าไม่มีจะ insert
    if(checkHasData($conn, $classid)) {
        $re = updateResultGrouped($conn, $classid, $group, $score);
    } else {
        $re = insertResultGrouped($conn, $classid, $group, $score);
    }

    if($re) {
        echo("success");
    } else {
        $mystr = "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
        echo $mystr;
    }
?>
