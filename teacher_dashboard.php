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
        $sql = "SELECT
                    class.id,
                    class.title,
                    class.description
                FROM
                    class
                WHERE
                    class.teacher_email = '{$_SESSION['email']}'";
        $result = mysqli_query($conn, $sql);
        
    ?>
    <!-- content -->
    <div class="container-fluid">
        <div class="text-center">
            <p><h3>DASHBOARD</h3></p>
            <hr>
        </div>

        <div class="container">
            <p>Class ที่สร้าง</p>

            <table class="table table-bordered table-striped  text-center">
                <thead class="thead-dark">
                    <th scope="col">Class ID</th>
                    <th scope="col">Class Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">จำนวนผู้เข้าร่วม</th>
                </thead>
                <tbody>
                    <?php
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo("<tr>");
                                echo("<td>{$row['id']}</td>");
                                echo("<td>{$row['title']}</td>");
                                echo("<td>{$row['description']}</td>");
                                $sq = "SELECT class_id FROM student_score WHERE class_id = '{$row['id']}' ";
                                $resultCount = mysqli_query($conn, $sq);
                                $n = mysqli_num_rows($resultCount);
                                echo("<td>{$n} คน</td>");
                                echo("</tr>");
                            }
                        }
                    ?>
                    

                </tbody>
            
            
            </table>
            
        </div>

    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>