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
    <div>
        <h4>STUDENT JOIN CLASS</h4>
        

        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    CODE
                    <input type="text" id="search" name="search">
                    <span class="btn btn-primary" id="btn_search">SEARCH</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    Class ID : <p id="demo"></p>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">Name of Subject</div>
                <div class="col-md-4">Description</div>
                <div class="col-md-3">Teacher</div>
                <div class="col-md-2">Status</div>
            </div>
            <div class="row text-center">
                <div class="col-md-3"><p id="subjectname"></p></div>
                <div class="col-md-4"><p id="desc"></div>
                <div class="col-md-3"><p id="teacher"></p></div>
                <div class="col-md-2">-</div>
            </div>
        </div>
    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
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

                    //1 check ว่ามีข้อมูลวิชาหรือไม่ 
                    //โดย สร้างตัวแปรชื่อ test มาเพื่อเก็บค่าผลลัพธ์การ parse resullt
                    //ถ้าผลลัพธ์ ที่ได้กลับมา คือ คำว่า error
                    //ให้ alert ว่า ไม่มีรหัส class นี้
                    //
                    console.log(result)
                    //alert(typeof result)

                    let test = JSON.parse(result)
                    
                    if(test == "error") {
                        $("#subjectname").html("-")
                        $("#desc").html("-")
                        $("#teacher").html("-")

                        alert("ไม่มีรหัส class นี้")
                    } else{
                        console.log(result)
                        let objResult = JSON.parse(result)
                        
                        console.log(objResult)
                        $("#subjectname").html(objResult['title'])
                        $("#desc").html(objResult['description'])
                        $("#teacher").html(objResult['name'])
                    }
                    
                }
            })

            
        });
    });
</script>