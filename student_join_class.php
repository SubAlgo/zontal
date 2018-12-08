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
                <h4>ลงทะเบียนเข้าร่วมคลาส</h4>
            </div>
        </div>
        
        
        
        <form id="qqq" action="./student_join_form.php" method="post">
            <?php
                //สร้าง input hidden เพื่อเก็บข้อมูล student email
                echo("<input type='hidden' name='std_email' id='std_email' value='{$_SESSION['email']}'>");
            ?>
            <input type="hidden" name="class_id" id="class_id" value="">
            
        </form>
        
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <b>กรอกไอดีของคลาส:</b> 
                    <input type="text" id="search" name="search">
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
                        <td><b>ชื่อวิชา</b></td>
                        <td><b>คำอธิบาย</b></td>
                        <td><b>ผู้สอน</b></td>
                        <td><b>เข้าร่วม</b></td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td><p id="subjectname"></p></td>
                        <td><p id="desc"></td>
                        <td><p id="teacher"></p></td>
                        <td>
                            <input class="btn btn-primary" style="display:none" id="join" type="submit" value="Join" form="qqq">
                        </td>
                    </tr>
                </tbody>
            
            </table>

 

            
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
        let glo_classid = "";    //ตัวแปรแบบ global สำหรับเก็บ class_id

        $("#btn_search").on("click", () => {
            $("#score_req").val("");
            let text = $("#search").val();
            $("#demo").html(text);

            let data = {'classid': text};

            $.ajax({
                url: 'ajax_student_join_class.php',
                type: 'post',
                data: data,
                success: function(result) {

                    /*1 check ว่ามีข้อมูลวิชาหรือไม่ 
                    #โดย สร้างตัวแปรชื่อ test มาเพื่อเก็บค่าผลลัพธ์การ parse resullt
                    #ถ้าผลลัพธ์ ที่ได้กลับมา คือ คำว่า error
                    #ให้ alert ว่า ไม่มีรหัส class นี้
                    */
                    console.log(result)
                    //alert(typeof result)

                    let test = JSON.parse(result)
                    //ถ้าส่งข้อความกลับมาว่า "error"
                    //ให้เคลีย form แล้ว alert ว่า "ไม่มีรหัส class นี้"
                    if(test == "error") {
                        $("#subjectname").html("-")
                        $("#desc").html("-")
                        $("#teacher").html("-")
                        $("#join").attr("style", "display:none");

                        alert("ไม่มีรหัส class นี้")
                    } else{
                        console.log(result)
                        let objResult = JSON.parse(result)
                        
                        console.log(objResult)
                        $("#subjectname").html(objResult['title'])
                        $("#desc").html(objResult['description'])
                        $("#teacher").html(objResult['name'])
                        $("#join").attr("style", "display");
                        //$("#join").html("-")

                        glo_classid = objResult['id']

                        let st = $("#std_id").val();
                        //set input type hidden id="class_id" ให้ value = class ที่เลือก 
                        //เพื่อใช้ในการสร้าง form การกรอกข้อมูลต่อไป
                        $("#class_id").val(glo_classid)
                    } 
                }
            })
        });

        //$("#btn_join").on("click", () => {
        //    //alert(glo_classid)
        //    let st = $("#std_id").val();
        //    alert(st + ' | ' + glo_classid)
        //    $("#class_id").val(glo_classid)
        //});
    });
</script>