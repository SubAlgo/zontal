<!DOCTYPE html>
<html lang="en">
<?php
    include('config.php');
?>

<!-- Check permission access -->
<?php

    //ถ้าไม่ได้ login
    if(!isset($_SESSION['permission'])) {
        header("Location: http://{$url}");
        die();
    }

    //ถ้าไม่ใช้ Teacher
    if($_SESSION['permission'] != 2) {
        header("Location: http://{$url}");
        die();
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
    <!--
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    -->
    <title>Document</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div class="1">
        <div class="container">

            <!-- <form action="" method="post"> -->

                <div class="form-group row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8 text-center">
                        <label>TEACHER VIEW</label>
                    </div>
                    <div class="col-md-2"></div>

                    <!-- สร้างกลุ่ม -->
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="group_name" name="group_name" placeholder="">
                    </div>
                    <div class="col-md-2"><button class="btn btn-primary">สร้าง</button></div>

                    <!-- Section Header -->
                    <div class="col-md-4 text-center">ตั้งค่ากลุ่ม</div>
                    <div class="col-md-4 text-center">
                        <label>Group</label>
                    </div>
                    <div class="col-md-4 text-center">STATUS</div>

                    <hr/>

                    <!-- Section List1 -->
                    <div class="col-md-4 text-center">
                        <button class="">Setting</button>
                    </div>

                    <div class="col-md-4 text-center">
                        <span>Section 2401</span>
                    </div>

                    <div class="col-md-4 text-center">
                        <label>Close</label>
                    </div>

                    <!-- Section List2 -->
                    <div class="col-md-4 text-center">
                        <button class="">Setting</button>
                    </div>

                    <div class="col-md-4 text-center">
                        <span>Section 2402</span>
                    </div>

                    <div class="col-md-4 text-center">
                        <label>Open</label>
                    </div>

                    <!-- Section List3 -->
                    <div class="col-md-4 text-center">
                        <button class="">Setting</button>
                    </div>

                    <div class="col-md-4 text-center">
                        <span>Section 2403</span>
                    </div>

                    <div class="col-md-4 text-center">
                        <label>Generate</label>
                    </div>


                    <!-- Submit button -->
                    <div class="col-md-4 text-center">
                        <button class="btn btn-primary">Open</button>
                    </div>
                    <div class="col-md-4 text-center">
                        <button class="btn btn-primary">Generate</button>
                    </div>
                    <div class="col-md-4 text-center" >
                        <button class="btn btn-primary">View</button>
                    </div>
                </div>
            <!-- </form> -->
        </div>
        
    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>