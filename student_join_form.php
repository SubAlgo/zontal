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

    <?php
        //DATA
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

    ?>

    <?php
    /************************
     *      FUCNTION        *
     ***********************/
    
     //function สำหรับ check ว่า นักเรียนคนนี้ได้ลงทะเบียนใน class นี้หรือยัง
    function checkJoined($conn, $classid, $stdEmail) {
        $sql = "SELECT
                    class_id,
                    std_email
                FROM
                    `student_score`
                WHERE
                    class_id = '{$classid}' AND std_email = '{$stdEmail}'";

        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0) {;
            return true;
        } else {
            return false;
        }
    }

    ?>

    <!-- content -->
    <div class="container-fluid">
        <!-- ส่วนหัว Form -->
        <div class="container text-center" style="margin-top:10px;">
            <h3>ลงทะเบียนเข้าร่วมคลาส</h3>
            <h4>คลาสไอดี : <?php echo($class_id); ?></h4>
            <p>แบบฟอร์มสำหรับกรอกข้อมูลลงทะเบียนเข้าร่วมคลาส</p>
        </div>


        <!-- Show Data ทีได้รับมา -->   
        <?php
            if($method == "GET") {
                header("Location: http://{$url}");
                die();
            }
            //ถ้า select แล้ว row มากกว่า 1 เท่ากับว่า ได้ลงทะเบียนใน class เรียบร้อยแล้ว
            $checkJoined = checkJoined($conn, $_POST['class_id'], $_POST['std_email']);
            if ($checkJoined) {
                echo ("<script LANGUAGE='JavaScript'>
                        window.alert('คุณได้ลงทะเบียนคลาส [classid: {$_POST['class_id']}] ไว้ก่อนหน้านี้แล้ว');
                        window.location.href='./student_join_class.php';
                        </script>");
            }
        ?>
        

        <!-- Show Data -->
        <?php
            $sql = "SELECT
                        class.title AS 'class_name',
                        users.name AS 'teacher_name',
                        class.description AS 'class_desc',
                        class.v AS 'v_req',
                        class.a AS 'a_req',
                        class.k AS 'k_req',
                        subject_req.id AS 'subject_id',
                        subject_req.title AS 'subject_req'
                    FROM
                        class
                    LEFT JOIN subject_req ON class.id = subject_req.class_id
                    JOIN users ON users.email = class.teacher_email
                    WHERE
                        class.id = '{$_POST['class_id']}'";
                            
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
                while($row = $result->fetch_assoc()){
                    //print_r($row);
                    $class_name = $row['class_name'];
                    $class_desc = $row['class_desc'];
                    $teacher_name = $row['teacher_name'];
                    $v_req      = $row['v_req'];
                    $a_req      = $row['a_req'];
                    $k_req      = $row['k_req'];
                    array_push($subject_req, $row['subject_req']);
                }
            }
            
        ?>
        
        
        <!-- SELECT VAK Score -->
        <div class="container text-center" style="border: 1px solid black">
            <div class="row">
                <div class="col-md-12">
                    <p><b>คะแนน VAK ของคุณ</b></p>
                    <p><b>(V = Visual | A = Auditory | K = Kinesthetic)</b></p>
                    <p>
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
                       echo("<b>V Score:</b> {$v} คะแนน | <b>A Score:</b> {$a} คะแนน | <b>K Score:</b> {$k} คะแนน")
                    ?>
                    </p>
                </div>
            </div>
        </div>
        <br>

        <!-- Show Data ทีได้รับมา -->
        <div class="container" style="border: 1px solid black">
            <div class="text-center">
                <h5><b>แบบฟอร์ม</b></h5>             
            </div>
            <form action="">
                <input type="hidden" name="email" id="email" value="<?php echo($_POST['std_email']) ?>">

                <div class="form-group row">
                    <label for="className" class="col-sm-4 col-form-label"><b>รายวิชา: </b></label>
                    <div class="col-sm-8">
                        <input type="hidden" name="class_id" id="class_id" value="<?php echo($class_id); ?>">
                        <input type="text" readonly class="form-control-plaintext" id="className" value="<?php echo($class_name); ?>">
                    </div>            
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label"><b>อาจารย์เจ้าของวิชา: </b></label>
                    <div class="col-sm-8">
                        <span>
                            <?php
                                echo($teacher_name);
                            ?>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="classDesc" class="col-sm-4 col-form-label"><b>คำอธิบายรายวิชา: </b></label>
                    <div class="col-sm-8">
                        <textarea class="form-control-plaintext" readonly name="classDesc" id="lassDesc" rows="3" value="" style="border: 1px solid black"><?php echo($class_desc); ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-sm-4 col-form-label"><b>Password class: </b></label>
                    <div class="col-sm-8">
                        <input type="password" name="password" id="password">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><b>คะแนนที่ต้องการ:</b></label>          
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
    
                    <div class="form-group row" style="<?php if($k_req == 0){echo("display:none;");} ?>">
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
                        //echo("<input class='col-sm-4 col-form-input' name='score'  type='number' min='0' max='4' id='reqScore{$i}'>");
                        echo("<select name='score'> 
                                <option value='4'>A</option>
                                <option value='3.5'>B+</option>
                                <option value='3'>B</option>
                                <option value='2.5'>C+</option>
                                <option value='2'>C</option>
                                <option value='1.5'>D</option>
                                <option value='0'>F</option>
                                </select>");
                        echo("</div>");
                    }
                ?>
                
                <div class="text-center" style="margin-bottom: 10px;">
                    <!--<input class="btn btn-primary" type="submit" value="ยืนยัน">-->
                    <span class="btn btn-primary" id="submit">Submit</span>
                    <span class="btn btn-danger" id="cancle">Cancel</span>
                </div>
            
            </form>
        </div>

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

                $('#cancle').on('click', ()=> {
                    
                    location.href = "./student_join_class.php"
                })

                $("#submit").on("click", ()=>{
                    let resultR;
                    let email       = $("#email").val()
                    let class_id    = $("#class_id").val();
                    let pass        = $('#password').val();
                    let subject     = [];
                    let score       = [];
                    let data        = {};
    
                    
                    
                    //Set Subject
                    $("input[name='subject']").each(function() {
                        subject.push(this.value)
                    })

                    //Set Score Value
                    $("select[name='score']").each(function() {
                        //resultR = checkValue(this.value);
                        //alert(this.value)
                        score.push(this.value);
                    });

                    data = {"class_id"  : class_id,
                            "email"     : email,
                            "subject"   : subject,
                            "score"     : score,
                            "pass"      : pass}
                    console.log(data)

                    
                    $.ajax({
                        url: 'ajax_student_join_form.php',
                        type: 'post',
                        data: data,
                        success: function(result) {
                            //alert(result)
                            if(result.trim() === 'PassError') {
                                alert("รหัส Class ไม่ถูกต้อง")
                            } else {
                                alert(result)
                                location.href = "student_join_class.php"
                            }                            
                        }
                    });
                })
            });
        </script>

    </div>

    <div style="height:100px;">
    </div>

    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>
</body>
</html>