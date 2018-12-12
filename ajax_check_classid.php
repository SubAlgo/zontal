<?php
    include('config.php');

    $cid = $_POST['cid'];

    //function สำหรับ checl ว่ามี classid ที่ต้องการหรือไม่
    //ถ้ามีจะ return TRUE
    //ถ้าไม่มีจะ return FALSE
    function checkClassid($conn, $cid) {
        $sql = "SELECT id FROM class WHERE id = '{$cid}'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) > 0) {
            echo("have");
        } else {
            echo("no");
        }
    }

    checkClassid($conn, $cid);


?>