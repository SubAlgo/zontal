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
 
    <title>Student Dashboard</title>
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
    
    //ถ้าไม่ใช้ Student
    if($_SESSION['permission'] != 3) {
        header("Location: http://{$url}");
        die();
    }
    ?>

    <?php 
        $sql = "SELECT
                    student_score.class_id,
                    class.title,
                    users.name,
                    result_grouped.classid
                FROM
                    student_score
                LEFT JOIN class ON class.id = student_score.class_id
                JOIN users ON users.email = class.teacher_email
                LEFT JOIN result_grouped ON result_grouped.classid = class.id
                WHERE
                    student_score.std_email = '{$_SESSION['email']}'
                GROUP BY
                    class_id";
        $result = mysqli_query($conn, $sql);

        $nClass = mysqli_num_rows($result);
        
        
    ?>
    <!-- content -->
    <div class="container-fluid">
        <div class="text-center">
            <p><h3>STUDENT DASHBOARD</h3></p>
            <hr>
        </div>

        <div class="container">
            <p class="text-center"><?php echo("<b>Class ที่เข้ารวม :</b> <u>{$nClass}</u> <b>class</b>"); ?></p>

            <table class="table table-bordered table-striped  text-center">
                <thead class="thead-dark">
                    <th scope="col">รหัส Class</th>
                    <th scope="col">ชื่อ Class</th>
                    <th scope="col">อาจารย์เจ้าของ Class</th>
                    <th scope="col">ผลลัพธ์การจัดกลุ่ม</th>
                </thead>
                <tbody>
                    <?php
                        if(mysqli_num_rows($result)>0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo("<tr>");
                                echo("<td>{$row['class_id']}</td>");
                                echo("<td>{$row['title']}</td>");
                                echo("<td>{$row['name']}</td>");
                                if($row['classid'] == '') {
                                    echo("<td><span class='btn btn-info disabled'>ยังไม่มีการจัดกลุ่ม</span></td>");
                                } else {
                                    echo("<td><a class='btn btn-primary' href='./showgrouped.php?c_id={$row['classid']}'>View</a></td>");
                                }
                                echo("</tr>");
                            }
                        }
                    ?>

                </tbody>
            
            
            </table>
            
        </div>

    </div>

    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>
</body>
</html>