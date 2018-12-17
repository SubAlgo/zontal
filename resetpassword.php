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
   
    <title>Reset password</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <?php
        if(!isset($_POST['email']) || !isset($_POST['userid'])) {
            header( "location: ./index.php" );
            exit(0);
        }
    ?>

    <?php
        $email = $_POST['email'];
        $userid = $_POST['userid'];
        $checkUser = checkUser($conn, $email, $userid);

        if($checkUser == false) {
            echo ("<script LANGUAGE='JavaScript'>
                        window.alert('Email หรือ Userid ไม่ถูกต้อง');
                        window.location.href='./forgetpassword.php';
                    </script>");                
        }
        
    ?>


    <?php
        function checkUser($conn, $email, $userid) {
            $sql = "SELECT u_id FROM USERS WHERE email = '{$email}' AND u_id = '{$userid}'";
            $result = mysqli_query($conn, $sql);
            
            if(mysqli_num_rows($result) == 1) {
                return true;
            } else {
                return false;
            }
        }
    ?>
   
    <!-- content -->
    <div class="container mt-5" style="width:700px; border: solid 1px;">
        <input type="hidden" name="email" id="email" value="<?php echo($email); ?>">
        <h4 class="text-center">RE-NEW PASSWORD</h4>
        <div class="row mt-2 ml-3">
            <div class="col-md-1"></div>
            <div class="col-md-3">new-password:</div>
            <div class="col-md-8">
                <input type="password" name="pass" id="pass">
            </div>
        </div>

        <div class="row mt-2 ml-3">
            <div class="col-md-1"></div>
            <div class="col-md-3">confirm password:</div>
            <div class="col-md-8">
                <input type="password" name="confirm" id="confirm">
            </div>
        </div>

        <div class="row mt-2 ml-3">
            <div class="col-md-12 text-center">
                <span class="btn btn-primary" id="submit">Submit</span>
                <a class="btn btn-danger" href="./login.php">Cancel</a>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#submit").on("click", () => {
                let email   = $("#email").val();
                let pass    = $("#pass").val();
                let confirm = $("#confirm").val();
                pass        = pass.trim();
                confirm     = confirm.trim();

                if(pass == "") {
                    alert("กรุณาตั้ง Password");
                    return false;
                }

                if(pass.length < 4) {
                    alert("กรุณาตั้ง Password มากกว่า 4 ตัวอักษร");
                    return false;
                }

                if(pass != confirm) {
                    alert("การยืนยันรหัสผ่านไม่ถูกต้อง");
                    return false;
                }

                let data = {'email': email,
                            'pass' : pass
                            }
                
                $.ajax({
                    type: "post",
                    url: "ajax_resetpassword.php",
                    data: data,
                    success: function (res) {
                        if(res.trim() == "success") {
                            alert("Your password has been reset successfully!")
                            location.href = "login.php"
                        }
                    }
                });
            })
        })
    </script>

    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>

</body>
</html>