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
   
    <title>Forgot password</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div class="container mt-5" style="width: 500px; border: solid 1px;">
        <h5 class="text-center mt-2">Password assistance</h5>
        <p class="text-center">Enter the email address and user id associated with your account.</p>
        <form action="resetpassword.php" method="post">
            <div class="form-group row">
                <label for="Email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="userid" class="col-sm-2 col-form-label">User ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="userid" name="userid" placeholder="User ID">
                </div>
            </div>

            <div class="form-group text-center">
                <input class="btn btn-warning" type="submit" value="Continue">
            </div>
        </form>
    </div>

    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>

</body>
</html>