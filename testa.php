<?php
    include('config.php');

    $sql = "SELECT result from result_grouped where id = '4'";

    $result = mysqli_query($conn, $sql);

    $data;

    if (mysqli_num_rows($result) > 0) {
        while($row = $result->fetch_assoc()) {
            
            $data = $row['result'];
            //print_r($re['result']);
           
            echo("<br>");
            //echo("x");
        } 
    } else {
        $arr = array("error");
        $myJSON = json_encode($arr);
        echo $myJSON;
    }

    //$data type = string
    //$data_decode type = array
    $data_decode = json_decode($data);

    foreach($data_decode as $val) {
        echo(json_encode($val));
        echo("<br><br>");
    }

    print_r($data_decode);
    echo("<br><br>");
    echo(json_encode($data_decode));
?>