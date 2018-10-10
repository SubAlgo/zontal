
    <?php
    include('config.php');

    $data = $_POST;
    //echo($data['classid']);
    //echo($data['data']);

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
?>
