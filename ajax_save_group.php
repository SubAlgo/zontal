<?php
    include('config.php');
    /**
     * ทำเพื่อ
     * เก็บผลลัพธ์การ Generate ไว้ใน DB เพื่อ เวลานักศึกษา หรือ ผู้เกี่ยวข้องต้องการดูว่าตัวเองอยู่กลุ่มไหนใน class 
     * ก็จะสามารถมาดูได้
     */

    $data = $_POST;

    $arrGroup = $_POST['group'];
    $arrClassid = $_POST['classid'];

    $objClassid = json_encode($data['classid']);
    $objGroup = json_encode($data['group']);
    //echo(count($arr));
    //echo($objClassid);
    //echo($data['classid']);
    //echo($data['data']);

    $classid = $arrClassid;
    $group = $objGroup ;

    $sql = "INSERT INTO result_grouped (classid, result)
            VALUES ('{$classid}' , '{$group}')
            ";

    if(mysqli_query($conn, $sql) == false) {
        $mystr = "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
        echo $mystr;
    } else {
        echo "success";
    }

    /*
    $classid = $data['classid'];
    
    $sql = "SELECT class.id, class.title, class.description, users.name
            FROM class LEFT JOIN users ON class.teacher_email = users.email
            WHERE class.id = '{$classid}'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = $result->fetch_assoc()) {
            
            $re = json_encode($row);
            echo($re);
            //echo("x");
        } 
    } else {
        $arr = array("error");
        $myJSON = json_encode($arr);
        echo $myJSON;
    }
    */

    //echo(var_dump($data)); //type array
    //echo(json_encode($data['group'][0]));
    //echo(var_dump($obj));
?>
