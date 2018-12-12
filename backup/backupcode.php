<!DOCTYPE html>
<html lang="en">
<?php
    include('config.php');
    if(isset($_SESSION['email'])){
        header( "location: ./index.php" );
        exit(0);
    }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- import bootstrap and JS -->
    <?php
        include("./layouts/meta.php");
    ?>
   
    <title>Student Register</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    
    <div class="container" style="margin-top:10px;">
    <form action="" method="post">
    
        <div class="row" style="margin-top:10px;">
            <div class="col-md-2"></div>
            <div class="col-md-8 text-center">
                <span><h5>Student Registration</h5></span>
            </div>
            <div class="col-md-2"></div>
        </div>

        <div class="form-group row">
            <label for="email" class="col-md-2 col-form-label">Email: </label>
            <div class="col-md-10">
                <input class="form-control" type="email" id="email" name="email" placeholder="xxx@gmail.com" required style="width: 70%;">
            </div>
        </div>

        <div class="form-group row">
            <label for="userid" class="col-md-2 col-form-label">Student ID: </label>
            <div class="col-md-10">
                <input class="form-control" type="text" id="userid" name="userid" placeholder="Student ID" required style="width: 70%;">
            </div>
        </div>

        <div class="form-group row">
            <label for="name" class="col-md-2 col-form-label">Name: </label>
            <div class="col-md-10">
                <input class="form-control" type="text" id="name" name="name" placeholder="Name" required style="width: 70%;">
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-2 col-form-label">Password: </label>
            <div class="col-md-10">
                <input class="form-control" type="password" id="password" name="password" placeholder="Password" required style="width: 70%;">
            </div>
        </div>

        <div class="form-group row">
            <label for="confirm" class="col-md-2 col-form-label">Confirm password: </label>
            <div class="col-md-10">
                <input class="form-control" type="password" id="confirm" name="confirm" placeholder="Confirm password" required style="width: 70%;">
            </div>
        </div>

        <div class="row text-center">
            <div class="col-md-12">
                
                <input type="submit" class="btn btn-primary" onclick="return checkPassword();"  value="Register">
                <a class="btn btn-primary" href="./login.php">Back</a> 
            </div>
        </div>
    
    </form>
    </div>
    
    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>

    
</body>
</html>


<!-- JAVASCRIPT -->
<script type="application/javascript">

    $(document).ready(function() {
        $("#")
        let email = $("#email").val()
    })
    function checkPassword() 
    {
        var pass = document.getElementById("password").value;
        var confirm = document.getElementById("confirm").value;
        
        //Check ความยาว Password
        if(pass.length < 4) 
        {
            alert("Password ต้องมากกว่า4 ตัวอักษร")
            return false
        }
        
        if(pass != confirm) 
        {
            alert('ยืนยัน Password ไม่ถูกต้อง')
            return false
        }
    }
</script>

<!-- PHP -->
<?php
    //$method = $_SERVER['REQUEST_METHOD'];
    //echo($method);
    if($method == "POST") 
    {
        //ตัดช่องว่าง และเตรียมข้อมูล
        $email      = trim((string)$_POST['email']);
        $u_id     = trim((string)$_POST['userid']);
        $name       = trim((string)$_POST['name']);
        $password   = trim((string)$_POST['password']);
        $confirm    = trim((string)$_POST['confirm']);

        //สร้างคำสั่ง sql ในการ insert
        //$sql = "INSERT INTO `users` (`email`, `u_id`, `name`, `password`, `p_id`) 
        //        VALUES ('{$email}', '{$u_id}', '{$name}', '{$password}', '3');";
        $sql = "INSERT INTO `users` (`email`, `u_id`, `name`, `password`, `v`, `a`, `k`, `p_id`) 
                VALUES ( '{$email}', '{$u_id}', '{$name}', '{$password}', '', '', '', '3');"; 
        //ประมวลผลคำสั้่ง sql
        if(mysqli_query($conn, $sql) == false) {
            echo "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
        } else {
            echo '<script type="text/javascript">alert("ลงทะเบียนสำเร็จ");</script>';
            echo('<script type="text/javascript">location.reload();</script>');
        }
        //mysqli_query($conn, $sql);
    } else if($method == "GET") {
        //echo "e";
    }
?>