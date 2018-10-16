<!DOCTYPE html>
<html lang="en">
<?php
    include('config.php');
?>

<?php
    //ถ้าไม่ได้ login
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
    <title>Join class form</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div>
        <div class="container text-center">
            <h3>Join class form</h3>
            <p>หน้าสำหรับให้นักศึกษาเข้ามากรอกข้อมูลคะแนนสำหรับการ join class</p>
        </div>
        
        <?php
            $data = $_POST;
            print_r($data);
            //echo("<br>");

            /*------------------Select Class and Subject require Data------------------------*/

            $sqlstudent = "";
            $sql = "SELECT
                            class.title,
                            class.description,
                            class.teacher_email,
                            class.pergroup,
                            class.v,
                            class.a,
                            class.k,
                            subject_req.title AS 'sub_req'
                    FROM
                            class
                    LEFT JOIN subject_req ON class.id = subject_req.class_id
                    WHERE
                            class.id = '{$data['class_id']}'";            

            $title;
            $description;
            $teacher_email;
            $perGroup;
            $v;
            $a;
            $k;
            $sub_req = array(); //รายวิชาที่ต้องการ

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = $result->fetch_assoc()) {
                    $title          = $row['title'];
                    $description    = $row['description'];
                    $perGroup       = $row['pergroup'];
                    $v              = $row['v'];
                    $a              = $row['a'];
                    $k              = $row['k'];
                    array_push($sub_req, $row['sub_req']);
                } 
            }

            /*-----------------Select Student Data--------------------------*/

            $sqlstudent = "SELECT v, a, k FROM users WHERE email = '{$data['std_email']}'";

            $resultstd = mysqli_query($conn, $sqlstudent);
            if(mysqli_num_rows($resultstd) > 0) {
                while($stdrow = $resultstd->fetch_assoc()) {
                    $std_vScore = $stdrow['v'];
                    $std_aScore = $stdrow['a'];
                    $std_kScore = $stdrow['k'];
                }
            }
            echo("<br>");
            echo("std_vScore = {$std_vScore}");
            echo("<br>");
            echo("std_aScore = {$std_aScore}");
            echo("<br>");
            echo("std_kScore = {$std_kScore}");
            echo("<br>");
            /*---------------------------------------------------*/

            $sqlstudent = "";
            echo($title);
            echo("<br>");
            echo($description);
            echo("<br>");
            //echo($perGroup);
            //echo("<br>");
            echo($v);
            echo("<br>");
            echo($a);
            echo("<br>");
            echo($k);
            echo("<br>");
            print_r($sub_req);
            echo("<br>");
            echo ("จำนวนวิชาที่ต้องผ่าน ".count($sub_req));
        ?>
        <!-- พวก v_data จะมีข้อมูลเป็น 0,1 เพื่อใช้ในการสร้าง ฟอร์มกรอกข้อมูล -->
        <input type="hidden" name="v_data" id="v_data" value="<?php echo($v);?>">
        <input type="hidden" name="a_data" id="a_data" value="<?php echo($a);?>">
        <input type="hidden" name="k_data" id="k_data" value="<?php echo($k);?>">
        <input type="hidden" name="classid" id="classid" value="<?php echo($data['class_id']);?>">
        <input type="hidden" name="stdemail" id="stdemail" value="<?php echo($data['std_email']);?>">
        <?php
            $nOfSubjectReq = count($sub_req);
        ?>
        <input type="hidden" name="nOfSubjectReq" id="nOfSubjectReq" value="<?php echo($nOfSubjectReq);?>">
        <div class="container">
            <div class="row">
                <div class="col-md-4">รายวิชา</div>
                <div class="col-md-8"> <?php echo($title); ?> </div>
            </div>
        
            <div class="row">
                <div class="col-md-4">Description</div>
                <div class="col-md-8"> <?php echo($description); ?> </div>
            </div>

            <div class="row" id="v" style="display:none">
                <div class="col-md-4">กรอกคะแนน v</div>
                <div class="col-md-8">
                    <input type="text" id="v_input" value="<?php echo("{$std_vScore}"); ?>" readonly>
                </div>
            </div>

            <div class="row" id="a" style="display:none">
                <div class="col-md-4">กรอกคะแนน a</div>
                <div class="col-md-8">
                    <input type="text" id="a_input" value="<?php echo("{$std_aScore}"); ?>" readonly>
                </div>
            </div>

            <div class="row" id="k" style="display:none">
                <div class="col-md-4">กรอกคะแนน k</div>
                <div class="col-md-8">
                    <input type="text" id="k_input" value="<?php echo("{$std_kScore}"); ?>" readonly>
                </div>
            </div>

            
            
            
            <?php
            //Code สร้าง form สำหรับใส่คะแนนวิชาที่ต้องผ่านก่อนหน้า
                $nSubreq = count($sub_req);
                echo $nSubreq;
                if($nSubreq > 0) {
                    $i = 1;
                    foreach($sub_req as $x) {
                        echo("<div class='row'>");
                            echo("<div class='col-md-4'> กรอกคะแนนวิชา {$x} </div>");
                            echo("<div class='col-md-8'> <input type='number' id='sub{$i}'> </div>");
                        echo("</div>");
                        $i++;
                    }  
                }   
            ?>
            
            <?php
                echo($x);
            ?>
            
            <div class="row">
                <div class="col-md-12 text-center">
                    <!--<input class="btn btn-primary" type="submit" value="บันทึก">-->
                    <span class="btn btn-primary" id="submit">บันทึก</span>
                </div>
            </div>
            
            ข้อมูลที่ต้องเก็บสำหรับเตรียม gen 1.classId 2.std_email 3.พวกคะแนน
            {
                "v"     : "$v",
                "a"     : "$a",
                "k"     : "$k",
                "score": {
                            "CS100": "80",
                            "CS101": "85"
                }
            }
            
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
        if($("#v_data").val() == 1) {
            $("#v").attr("style", "display")
        }

        if($("#a_data").val() == 1) {
            $("#a").attr("style", "display")
        }

        if($("#k_data").val() == 1) {
            $("#k").attr("style", "display")
        }

        $("#submit").on("click",()=>{
            let class_id = $("#classid").val()
            let std_email = $("#stdemail").val()
            let v, a, k;

            //set vak score
            if($("#v_data").val() == 1) {
                v = $("#v_input").val();
            } else {
                v = 0
            }

            if($("#a_data").val() == 1) {
                a = $("#a_input").val();
            } else {
                a = 0
            }

            if($("#k_data").val() == 1) {
                k = $("#k_input").val();
            } else [
                k = 0
            ]

            //set ข้อมูลคะแนนรายวิชาที่ต้องผ่านก่อนหน้า โดย
            //1. หาจำนวนรายวิชาที่ต้องผ่านทั้งหมด (nOfSubReq)
            //2. สร้าง array (sReq) สำหรับเก็บข้อมูลคะแนนวิชาที่ต้องผ่าน
            
            let nOfSubReq = $("#nOfSubjectReq").val(); //จำนวนวิชาที่ต้องผ่าน
            let sReq =[];
            let i;
            let testVal;
            for(i = 1; i <= nOfSubReq; i++) {
                if($("#sub"+i).val() == "") {
                    alert("กรุณากรอกข้อมูลให้ครบ")
                    return false
                }
                
                sReq.push($("#sub"+i).val());
            }
            //alert(JSON.stringify(sReq))

            //alert(nOfSubReq)
            let d = {   "classid"       : class_id,
                        "std_email"     : std_email,
                        "v"             : v,
                        "a"             : a,
                        "k"             : k,
                        "nOfScore"      : nOfSubReq,
                        "scoreReq"      : sReq

                    };
            //let dumy = JSON.stringify(d)
            console.log(d)
            //alert(d['classid'])

             $.ajax({
                url: 'ajax_student_join_form.php',
                type: 'post',
                data: d,
                success: function(result) {
                    alert(result)
                    window.location.replace("./student_join_class.php");
                }
            });


        })
    });
</script>
        