<!DOCTYPE html>
<html lang="en">
<?php
    include('config.php');
?>

<?php
    //Check Permission
    //ต้อง login และเป็น Admin เท่านั้นถึงจะเข้ามาหน้านี้ได้

    //Check ว่า login มาหรือยัง
    /*
    if(!isset($_SESSION['email']) && !isset($_SESSION['permission'])) {
        header("Location: http://{$url}");
        die();
    }
    */

    //ถ้าไม่ใช้ admin ให้ redirect to homepage
    /*
    if($_SESSION['permission'] != 1) {
        header("Location: http://{$url}");
        die();
    } else {
        echo "Hi admin";
    }
    */
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- import bootstrap and JS -->
    <?php
        include("./layouts/meta.php");
    ?>
    <!--
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    -->
    <title>Student Register</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div class="container">

        <form action="" method="post">

            <div class="row" style="margin-top:10px;">
                <div class="col-md-2"></div>
                <div class="col-md-8 text-center">
                    <span><h5>Teacher Registration</h5></span>
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
                <label for="userid" class="col-md-2 col-form-label">Teacher ID: </label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="userid" name="userid" placeholder="Teacher ID" required style="width: 70%;">
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
    function checkPassword() 
    {
        var pass = document.getElementById("password").value;
        var confirm = document.getElementById("confirm").value;
        var id = document.getElementById("userid").value;

        if(id[0] != "a") 
        {
            alert("กำหนด ID ไม่ถูกต้อง")
            return false
        }
        
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
        $sql = "INSERT INTO `users` (`email`, `u_id`, `name`, `password`, `p_id`) 
                VALUES ('{$email}', '{$u_id}', '{$name}', '{$password}', '2');";

        //ประมวลผลคำสั้่ง sql
        if(mysqli_query($conn, $sql) == false) 
        {
            echo "<div align='center'><b> Error: " .  mysqli_error($conn)."</b></div>";
        }
        //mysqli_query($conn, $sql);
    } else if($method == "GET") {
        //echo "e";
    }
?>