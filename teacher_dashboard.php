<!DOCTYPE html>
<html lang="en">
<?php
    include('config.php');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- import bootstrap and JS -->
    <?php
        include("./layouts/meta.php");
    ?>
 
    <title>Teacher Dashboard</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- Check permission access -->
    <?php
    
    //ถ้าไม่ได้ login
    if(!isset($_SESSION)) {
        header("Location: http://{$url}");
        die();
    }
    
    //ถ้าไม่ใช้ Teacher
    if($_SESSION['permission'] != 2) {
        header("Location: http://{$url}");
        die();
    }
    ?>

    <?php
        function getClassData($conn, $c_id) {
            $data = [];
            $sql = "SELECT
                        class.student_limit
                    FROM
                        class
                    WHERE class.id = '{$c_id}'";
            $result = mysqli_query($conn, $sql);

            $row = mysqli_fetch_array($result);

            return $row['student_limit'];
        }

        function getStudentData($conn, $c_id) {
            $data = [];
            $sql = "SELECT
                        users.name
                    FROM
                        student_score AS stdScore
                    LEFT JOIN users ON stdScore.std_email = users.email
                    WHERE
                        stdScore.class_id = '{$c_id}'
                    GROUP BY
                        stdScore.std_email";

            $result = mysqli_query($conn, $sql);

            
            $total = mysqli_num_rows($result);
            return $total;
        }
    ?>

    <?php
        

        $sql = "SELECT
                    class.id,
                    class.title,
                    class.description
                FROM
                    class
                WHERE
                    class.teacher_email = '{$_SESSION['email']}'";
        $result = mysqli_query($conn, $sql);

        $nClass = mysqli_num_rows($result);
        
    ?>
    <!-- content -->
    <div class="container-fluid">
        <div class="text-center">
            <p><h3>TEACHER DASHBOARD</h3></p>
            <hr>
        </div>

        


        <div class="container">
            <div class="text-center">
                <p><b>จำนวน Class ที่สร้าง : </b><?php echo($nClass); ?> Class</p>
            </div>
            <table class="table table-bordered table-striped  text-center">
                <thead class="thead-dark">
                    <th scope="col" style="width: 30%">Class ID</th>
                    <th scope="col" style="width: 40%">Class Name</th>
                    <th scope="col" style="width: 30%">Class Manage</th>
                </thead>
                <tbody>
                    <?php
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo("<tr>");
                                echo("<td>{$row['id']}</td>");
                                echo("<td>{$row['title']}</td>");
                                echo("<td>");
                                    echo(" <a href='class_description.php?c_id={$row['id']}' class='btn btn-primary'>View</a> ");
                                    $classData = getClassData($conn, $row['id']);
                                    $stdData = getStudentData($conn, $row['id']);
                                    if($classData == $stdData) {
                                        echo("<a class='btn btn-success' href='./generate_class.php?c_id={$row['id']}'>Generate</a>");
                                        //echo(" <span class='btn btn-success' id='{$row['id']}' value='{$row['id']}'>Generate</span> ");
                                    } else {
                                        //echo("{$stdData}/{$classData}");
                                        echo(" <span class='btn btn-success disabled'>Generate</span> " );
                                    }
                                    
                                    echo(" <a href='class_close.php?c_id={$row['id']}' class='btn btn-danger'>Close</a> ");
                                echo("</td>");
                                        
                                        
                                echo("</tr>");
                            }
                        }
                         
                    ?>
                    <a href=""></a>
                </tbody>
                <a href=''></a>
            </table>
        </div>

    </div>

    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>
</body>
</html>