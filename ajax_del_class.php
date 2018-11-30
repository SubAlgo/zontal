<?php
    include('config.php');

    //$classid = $_POST['data'];
    //echo(json_encode($_POST['classID']));
    $classid = $_POST['classID'];

    
    $sql = "DELETE FROM class WHERE class.id = '{$classid}'";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "fail";
    }    
    

?>