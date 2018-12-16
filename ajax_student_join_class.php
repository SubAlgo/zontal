
    <?php
    include('config.php');

    $data = $_POST;

    $classid    = $data['cid'];
    $classpass  = $data['cpass'];

    
    $sql = "SELECT class.id, class.title, class.description, users.name
            FROM class LEFT JOIN users ON class.teacher_email = users.email
            WHERE class.id = '{$classid}' AND class.password = '{$classpass}'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = $result->fetch_assoc()) {
            
            $res = json_encode($row);
            echo($res);
        } 
    } else {
        echo("error");
    }
    
?>
