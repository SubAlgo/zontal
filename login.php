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

    <?php
        //ตรวจว่ามีการ Login มาหรือยัง
        if(isset($_SESSION) && isset($_SESSION['email'])) {
            header("Location: http://{$url}");
            die();
        }
    
        //จัดการ การ Login
        $loginStatus = 1;
        if($method == "POST") {
            
            if(isset($_POST['email']) && isset($_POST['password'])) {
                $email      = $_POST['email'];
                $password   = $_POST['password'];
                echo($email);
                echo($password);

                //สร้างคำสั่ง SQL เพื่อใช้ตรวจสอบการ Login
                $sql = "SELECT * FROM `users` WHERE `users`.`email` = '{$email}' and `users`.`password` = '{$password}'";

                $result = mysqli_query($conn, $sql);

                if(!(mysqli_num_rows($result) > 0)) {
                    $loginStatus = 0;
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row["name"];
                        $_SESSION['email']          = $email;
                        $_SESSION['permission']     = $row['p_id'];
                    }
                    header("Location: http://{$url}");

                }          
            }
        }
    ?>

    <title>Login</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>


    <!-- content -->
    <div class="container" style="margin-top:10px;">
        <form action="" method="post">

            <div class="form-group">
                <div class="row mt-2">
                    <!-- Email -->
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                
                <div class="row mt-2" >
                    <!-- Password -->
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>                
                    </div>
                    <div class="col-md-4"></div>
                </div>
                
                <div class="row text-center mt-2">
                    <!-- Submit button -->
                    <div class="col-md-12">
                        <input type="submit" id="submit" class="btn btn-primary" value="Login">
                        <!--<button type="button" class="btn btn-primary" id="btn-back" name="btn-back">Register</button> -->
                    </div>
                </div>
            </div>
        </form>
        
        <div class="container text-center mt-2">
            <a class="text-primary" href="./forgetpassword.php">Forgot password</a>
        </div>

        <div class="container text-center mt-2">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        if ($loginStatus == 0) {
                            echo("<p class='text-danger' id='showError'><b>อีเมล์หรือพาสเวิร์ดไม่ถูกต้อง</b></p>");
                            $loginStatus = 1;
                        }
                    ?>
                    <p class='text-danger' id='showError' style='display:none'><b>อีเมล์หรือพาสเวิร์ดไม่ถูกต้อง</b></p>
                </div>
            </div>
        </div>

    </div>

    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>
   

    
</body>
</html>

