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
        //include("./layouts/meta.php");
    ?>
    <?php
        include("./layouts/meta.php");
    ?>
   
    <title>Home</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div>
    <?php
        /*
        if(isset($_SESSION['email'])) {
            echo "{$_SESSION['email']}";
            echo "{$_SESSION['permission']}";
        } else {
            echo "not setting session!!";
        }
        */
    ?>

    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>

    
</body>
</html>