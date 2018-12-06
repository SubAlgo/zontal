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

    $re         = false;

?>

<?php
    // function
    
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
     function updateResultGrouped($conn, $classid, $group) {
         $sql = "UPDATE `result_grouped` SET `result` = '{$group}' WHERE `result_grouped`.`id` = '{$classid}'";

         if(mysqli_query($conn, $sql)) {
            return true;
         } else {
             return false;
         }
     }

     //function บันทึกข้อมูล
     function insertResultGrouped($conn, $classid, $group) {
        $sql = "INSERT INTO result_grouped (classid, result)
                VALUES ('{$classid}' , '{$group}')
                ";
        if(mysqli_query($conn, $sql)) {
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
        $re = updateResultGrouped($conn, $classid, $group);
    } else {
        $re = insertResultGrouped($conn, $classid, $group);
    }

    if($re) {
        echo("success");
    } else {
        $mystr = "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
        echo $mystr;
    }
?>


<?php
        
    //echo(count($arr));
    //echo($objClassid);
    //echo($data['classid']);
    //echo($data['data']);

    /*

    $sql = "INSERT INTO result_grouped (classid, result)
            VALUES ('{$classid}' , '{$group}')
            ";

    if(mysqli_query($conn, $sql) == false) {
        $mystr = "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
        echo $mystr;
    } else {
        echo "success";
    }
    */

?>
