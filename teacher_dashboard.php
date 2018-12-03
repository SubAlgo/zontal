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
                                echo("<td>
                                        <a href='class_description.php?c_id={$row['id']}' class='btn btn-primary'>View</a>
                                        <span class='btn btn-success'>Generate</span>
                                        <a href='class_close.php?c_id={$row['id']}' class='btn btn-danger'>Close</a>
                                    </td>");
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