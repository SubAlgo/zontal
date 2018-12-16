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
        //Check permission
        if(!isset($_SESSION)) {
            header("Location: http://{$url}");
            die();
        }
        
        //ถ้าไม่ใช้ Teacher
        if($_SESSION['permission'] != 3) {
            header("Location: http://{$url}");
            die();
        }
    ?>

    <?php
        //echo($_SESSION['email']);
        $checkVAK = checkVAKScore($_SESSION['email'], $conn);

        if($checkVAK<=0) {
            echo "<script>
                    alert('คุณยังไม่ได้ทำการทดสอบ VAK Test');
                    window.location.href='http://localhost/zontal/vaktest.php';
                   </script>";
        }


        function checkVAKScore($email, $conn) {
            $sql = "SELECT v,a,k FROM users WHERE email = '{$email}'";
            $result = mysqli_query($conn, $sql);

            if(!(mysqli_num_rows($result) > 0)) {
                //echo "Login fail";
            
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    $v = $row["v"];
                    $a = $row['a'];
                    $k = $row['k'];
                }
            }
            $sum = $v + $a + $k;
            //echo($sum);
            return $sum;
        }
    ?>
    <!--
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    -->
    <title>Student join class</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>


    <!-- content -->
    <div class="container" style="margin-top: 10px;">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4>Join new class</h4>
            </div>
        </div>
        
        
        
        <form id="join_class" action="./student_join_form.php" method="post">
            <?php
                //สร้าง input hidden เพื่อเก็บข้อมูล student email
                echo("<input type='hidden' name='std_email' id='std_email' value='{$_SESSION['email']}'>");
            ?>
            <input type="hidden" name="class_id" id="class_id" value="">
            
        </form>
        
        <div class="container">

            <div class="row" style="width: 500px; margin-left: 30%; margin-top: 10px;">
                <div class="col-md-4">
                    <b>Class id: </b>
                </div>
                <div class="col-md-8">
                    <input type="text" id="c_id" name="c_id">
                </div>
            </div>

            <div class="row" style="width: 500px; margin-left: 30%; margin-top: 10px;">
                <div class="col-md-4">
                    <b>Verification code: </b>
                </div>
                <div class="col-md-8">
                    <input type="text" id="c_password" name="c_password">
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="col-md-12 text-center">
                    <span class="btn btn-primary" id="btn_search">ค้นหา</span>
                </div>
            </div>



            <div class="row" style="margin-top:10px;">
                <div class="col-md-12 text-center">
                    <b>Class ID :</b> <span id="demo"></span>
                </div>
            </div>
        </div>

        <div class="container" style="margin-top:10px;">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <td><b>Class name</b></td>
                        <td><b>Description</b></td>
                        <td><b>Advisor</b></td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td><p id="subjectname"></p></td>
                        <td><p id="desc"></td>
                        <td><p id="teacher"></p></td>
                        
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="container">
            <div class="col-md-12 text-center">
                
                <input class="btn btn-primary" style="display:none" id="join" type="submit" value="Submit" form="join_class">
                <span class="btn btn-danger" style="display:none" id="btn_cancel">Cancel</span>
                <!--<a href="./student_dashboard.php" class="btn btn-danger" style="display:none" id="cancel">Cancel</a> -->     
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
        let cid     = "";
        let cpass   = "";

        $("#btn_search").on("click", function() {
            cid     = $("#c_id").val()
            cpass   = $("#c_password").val()

            if(cid.trim() == "") {
                alert("กรุณากรอกข้อมูล class id");
                return false;
            }

            if(cpass.trim() == "") {
                alert("กรุณากรอกข้อมูล verification code");
                return false;
            }

            let data = {'cid' : cid, 'cpass' : cpass};


            
            $.ajax({
                type: "post",
                url: "ajax_student_join_class.php",
                data: data,
                success: function (res) {
                    console.log(res)
                    

                    if(res.trim() == "error") {
                        $("#demo").html("-")
                        $("#subjectname").html("-")
                        $("#desc").html("-")
                        $("#teacher").html("-")
                        $("#join").attr("style", "display:none");
                        $("#btn_cancel").attr("style", "display:none");
                        alert("ไม่มี class ที่ค้นหา หรือ verification code ไม่ถูกต้อง");
                        return false
                    }
                    
                    let obj = JSON.parse(res)
                    $("#demo").html(obj['id'])
                    $("#subjectname").html(obj['title']);
                    $("#desc").html(obj['description']);
                    $("#teacher").html(obj['name'])
                    $("#join").attr("style", "display");
                    $("#btn_cancel").attr("style", "display");

                    $("#class_id").val(obj['id'])
                    
                }
            });


        })

        $("#btn_cancel").on("click", function() {
            location.reload();
        })


    });
</script>