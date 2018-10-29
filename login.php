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
        if($method == "POST") {
            if(isset($_POST['email']) && isset($_POST['password'])) {
                $email      = $_POST['email'];
                $password   = $_POST['password'];

                //สร้างคำสั่ง SQL เพื่อใช้ตรวจสอบการ Login
                $sql = "SELECT * FROM users WHERE email = '{$email}' and password = '{$password}'";

                $result = mysqli_query($conn, $sql);

                if(!(mysqli_num_rows($result) > 0)) {
                    echo "Login fail";
                
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
    <!--
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    -->
    <title>Student Register</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div class="container">
    <form action="" method="post">
            <div class="form-group row">

                <!-- Email -->
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="col-md-2"></div>

                <!-- Password -->
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>                
                </div>
                <div class="col-md-2"></div>


                <!-- Submit button -->
                <div class="col-md-2" ></div>
                <div class="col-md-8">
                    <input type="submit" id="submit" class="btn btn-primary" value="Login">
                    <button type="button" class="btn btn-primary" id="btn-back" name="btn-back">Register</button>    
                </div>
                <div class="col-md-2" ></div>
            </div>
        </form>
    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>

    
</body>
</html>

