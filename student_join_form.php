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
   
    <title>Join Class Form</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div class="container-fluid">
        <!-- ส่วนหัว Form -->
        <div class="container text-center">
            <h3>Join Class Form</h3>
            <p>หน้าสำหรับให้นักศึกษาเข้ามากรอกข้อมูลคะแนนสำหรับการ join class</p>
        </div>


        <!-- Show Data ทีได้รับมา -->
        <div class="container" style="border: 1px solid black">
            <div class="text-center">
                <b>Data from $_POST</b><br>
                <?php
                    if($method == "GET") {
                        header("Location: http://{$url}");
                        die();
                    }
                    print_r($_POST);
                    echo("<br>");
                ?>
            </div>
        </div>
        <hr>

        <!-- Show Data -->
        <div class="container" style="border: 1px solid black">
            <div class="text-center">
                <b>Data from select SQL</b><br>
                <?php
                    $std_email = $_POST['std_email'];
                    $class_id = $_POST['class_id'];
                    $class_name;
                    $class_desc;
                    $v_req;
                    $a_req;
                    $k_req;
                    $v_score;
                    $a_score;
                    $k_score;
                    $subject_req = [];

                    /* Select ข้อมูล Class และ Subject_Require */
                    $sql = "SELECT
                                    class.title AS 'class_name',
                                    class.description AS 'class_desc',
                                    class.v AS 'v_req',
                                    class.a AS 'a_req',
                                    class.k AS 'k_req',
                                    subject_req.id AS 'subject_id',
                                    subject_req.title AS 'subject_req'
                            FROM
                                    class
                            LEFT JOIN subject_req ON class.id = subject_req.class_id
                            WHERE
                                    class.id = '{$_POST['class_id']}'";

                    $result = mysqli_query($conn, $sql);

                    if(mysqli_num_rows($result) > 0) {
                        while($row = $result->fetch_assoc()){
                            print_r($row);
                            echo("<br>");

                            $class_name = $row['class_name'];
                            $class_desc = $row['class_desc'];
                            $v_req      = $row['v_req'];
                            $a_req      = $row['a_req'];
                            $k_req      = $row['k_req'];
                            array_push($subject_req, $row['subject_req']);
                        }
                    }

                    
                ?>
            </div>  
        </div>
        <br>
        
        <!-- SELECT VAK Score -->
        <div class="container text-center" style="border: 1px solid black">
            <p>Select VAK Score</p>
            <?php
               /*Select VAK Score */
               $sqlVAK = "SELECT v, a, k FROM users WHERE email = '{$std_email}'";
               $vakResult = mysqli_query($conn, $sqlVAK);
               if(mysqli_num_rows($vakResult) > 0) {
                   while($row = $vakResult->fetch_assoc()) {
                       $v = $row['v'];
                       $a = $row['a'];
                       $k = $row['k'];
                   }
               }
               echo("{$v}, {$a}, {$k}")
            ?>
        </div>
        <br>

        <!-- Show Data ทีได้รับมา -->
        <div class="container" style="border: 1px solid black">
            <div class="text-center">
                <b>Form</b><br>                
            </div>
            <form action="">
                <input type="hidden" name="email" id="email" value="<?php echo($_POST['std_email']) ?>">

                <div class="form-group row">
                    <label for="className" class="col-sm-4 col-form-label">รายวิชา</label>
                    <div class="col-sm-8">
                        <input type="hidden" name="class_id" id="class_id" value="<?php echo($class_id); ?>">
                        <input type="text" readonly class="form-control-plaintext" id="className" value="<?php echo($class_name); ?>">
                    </div>            
                </div>

                <div class="form-group row">
                    <label for="classDesc" class="col-sm-4 col-form-label">คำอธิบายรายวิชา</label>
                    <div class="col-sm-8">
                        <textarea class="form-control-plaintext" readonly name="classDesc" id="lassDesc" rows="3" value="" style="border: 1px solid black"><?php echo($class_desc); ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-form-label">คะแนนที่ต้องการ</label>          
                </div>

                <!-- VAK SCORE -->
                <!-- ถ้าไม่ต้องการก็จะ display:none -->
                
                <div>
                    <div class="form-group row" style="<?php if($v_req == 0){echo("display:none");} ?>">
                        <div class="col-sm-1"></div>
                        <label for="v_score" class="col-sm-3 col-form-label">คะแนน V</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="v_score" value="<?php echo($v); ?>">
                        </div>            
                    </div>
    
                    <div class="form-group row" style="<?php if($a_req == 0){echo("display:none");} ?>">
                        <div class="col-sm-1"></div>
                        <label for="a_score" class="col-sm-3 col-form-label">คะแนน A</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="a_score" value="<?php echo($a); ?>">
                        </div>            
                    </div>
    
                    <div class="form-group row" style="<?php if($k_req == 0){echo("display:none");} ?>">
                        <div class="col-sm-1"></div>
                        <label for="k_score" class="col-sm-3 col-form-label">คะแนน K</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="k_score" value="<?php echo($k); ?>">
                        </div>            
                    </div>
                </div>
               
               <!-- สร้าง FORM สำหรับกรอกข้อมูลคะแนนรายวิชาที่ต้องการ -->
                <?php
                    for($i=0; $i<count($subject_req); $i++){
                        echo("<div class='form-group-row'>");
                        echo("<label class='col-sm-4 col-form-label' name='subject'>คะแนนวิชา {$subject_req[$i]}</label>");
                        echo("<input type='hidden' name='subject' value='{$subject_req[$i]}'>");
                        echo("<input class='col-sm-4 col-form-input' name='score'  type='number' min='0' max='4' id='reqScore{$i}'>");
                        echo("</div>");
                    }
                ?>
                
                <div class="text-center">
                    <!--<input class="btn btn-primary" type="submit" value="ยืนยัน">-->
                    <span class="btn btn-primary" id="submit">Submit</span>
                </div>
            
            </form>
        </div>
        <hr>

        <script type="text/javascript">
            $(document).ready(function() {

                /*Function checkValue
                * ใช้สำหรับ check ค่าว่า มากกว่า4 หรือ น้อยกว่า 0 หรือไม่
                */
                const checkValue = (v) => {
                    if(v > 4 || v<0 ){
                        alert("คะแนนต้องอยู่ระหว่าง 0.00 - 4.00 เท่านั้น");
                        return false
                    }
                    alert(typeof v);
                    if(Number.isInteger(v)){
                        alert("f")
                    }
                }

                $("#submit").on("click", ()=>{
                    let resultR;
                    let email       = $("#email").val()
                    let class_id    = $("#class_id").val();
                    let subject     = [];
                    let score       = [];
                    let data        = {};
    
                    

                    //Set Subject
                    $("input[name='subject']").each(function() {
                        subject.push(this.value)
                    })

                    //Set Score Value
                    $("input[name='score']").each(function() {
                        //resultR = checkValue(this.value);
                        score.push(this.value);
                    });

                    data = {"class_id"  : class_id,
                            "email"     : email,
                            "subject"   : subject,
                            "score"     : score}
                    console.log(data)

                    $.ajax({
                        url: 'ajax_student_join_form.php',
                        type: 'post',
                        data: data,
                        success: function(result) {
                        alert(result)
                        if(result == "Success") {
                            alert("x")
                        }
                        //window.location.replace("./student_join_class.php");
                    }
            });

                    
                })
            });
        </script>

    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>