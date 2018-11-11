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
    <!--
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    -->
    <title>Generate</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <br>
    <div class="container" style="border: 1px solid black; padding: 20px 5px 20px 5px;">
        <form action="generate_class.php" method="post" >
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">กรอก id class</div>
                <div class="col-md-4"><input type="text" name="classid" id="classid" placeholder="class id"></div>
                <div class="col-md-2"></div>
            </div>
            
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">กรอก จำนวนรอบการ random</div>
                <div class="col-md-4"><input type="text" name="loop" id="loop" placeholder="รอบการ random"></div>
                <div class="col-md-2"></div>
            </div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">กรอก เปอร์เซ็นต์ ข้อมูลที่จะนำไปทำการ shift ค่า</div>
                <div class="col-md-4">
                    <input type="text" name="percentMin" id="percentMin" value="50">%
                </div>
                <div class="col-md-2"></div>
            </div>

            <div class="row text-center">
                <div class="col-md-12"><input class="btn btn-primary" type="submit" value="ยืนยัน"></div>
            </div>

            <div class="row text-center">
                <div class="col-md-12">
                    <p>ทดลองด้วย class1 หรือ class3</p>
                </div>
            </div>
        </form>

    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>