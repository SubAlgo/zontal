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
        $std_email      = $_POST['std_email'];
        $class_id       = $_POST['class_id'];
        $classData      = getClassData($conn, $class_id);
        $vakData        = getVakScore($conn, $std_email);

        $class_name     = $classData['class_name'];
        $class_desc     = $classData['class_desc'];    
        $v_req          = $classData['v_req'];
        $a_req          = $classData['a_req'];
        $k_req          = $classData['k_req'];
        $subject_req    = $classData['subject_req'];
        $teacher_name   = $classData['teacher_name'];
        
        $v_score        = $vakData['v'];
        $a_score        = $vakData['a'];
        $k_score        = $vakData['k'];

    ?>

    <?php
    /************************
     *      FUCNTION        *
     ***********************/
    
        //function getClassData
        function getClassData($conn, $class_id) {
            $subject_req = [];
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
                        class.id = '$class_id'";

            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
                while($row = $result->fetch_assoc()){
                    $class_name = $row['class_name'];
                    $class_desc = $row['class_desc'];
                    $teacher_name = $row['teacher_name'];
                    $v_req      = $row['v_req'];
                    $a_req      = $row['a_req'];
                    $k_req      = $row['k_req'];
                    array_push($subject_req, $row['subject_req']);
                }
            }
            $data = [   'class_name' => $class_name,
                        'class_desc' => $class_desc,
                        'teacher_name' => $teacher_name,
                        'v_req'         => $v_req,
                        'a_req'         => $a_req,
                        'k_req'         => $k_req,
                        'subject_req'   => $subject_req
                    ];

            return $data;
        }

        //function getVakScore
        function getVakScore($conn, $std_email) { 
            $sql = "SELECT v, a, k FROM users WHERE email = '{$std_email}'";
               $result = mysqli_query($conn, $sql);
               if(mysqli_num_rows($result) > 0) {
                   while($row = $result->fetch_assoc()) {
                       $v = $row['v'];
                       $a = $row['a'];
                       $k = $row['k'];
                   }
                   $data = ['v' => $v, 
                            'a' => $a, 
                            'k' => $k
                            ];
                    return $data;
               }
        }
    
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

    <div class="container-fluid" style="width:60%;">
        <!-- ส่วนหัว Form -->
        <div class="container text-center" style="margin-top:10px;">
            <h3>Class registration</h3>
            
            <input type="hidden" id="class_id" value="<?php echo($class_id); ?>">
        </div>

        <!-- Class DATA -->
        <div class="container" style="border: solid 1px; ">
        <h4> <p class="text-center"> Detail of class id : <?php echo($class_id); ?> </p></h4>
            <div class="row mt-2">
                <div class="col-md-4">
                    <b>Class name: </b>
                </div>
                <div class="col-md-8">
                    <?php echo($class_name); ?>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-4">
                    <b>Advisor: </b>
                </div>
                <div class="col-md-8">
                    <?php echo($teacher_name); ?>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-4">
                    <b>Class description:</b>
                </div>
                <div class="col-md-8">
                    <textarea class="bg-secondary" rows="4" cols="40" readonly><?php echo($class_desc); ?></textarea>
                </div>
            </div>
        </div>


        <!-- VAK Score -->
        <div class="container mt-2" style="border: solid 1px;">
            <h5><p class="text-center"><b>Your learning Score</b></p></h5>
            <p class="text-center"><b>(V = Visual | A = Auditory | K = Kinesthetic)</b></p>
            <?php
               echo("<p class='text-center'><b>V Score:</b> {$v_score} คะแนน | <b>A Score:</b> {$a_score} คะแนน | <b>K Score:</b> {$k_score} คะแนน</p>")
            ?>
        </div>
    

        <!-- Show Data ทีได้รับมา -->
        <div class="container mt-2" style="border: 1px solid black">
            <div class="text-center">
                <h5><b>Score require</b></h5>             
            </div>
            <form action="">
                <input type="hidden" name="email" id="email" value="<?php echo($_POST['std_email']) ?>">

               <!-- สร้าง FORM สำหรับกรอกข้อมูลคะแนนรายวิชาที่ต้องการ -->
                <?php
                    //check ก่อนว่า มีรายวิชาที่ต้องการก่อนหน้าหรือไม่
                    if($subject_req[0] != "") {
                        for($i=0; $i<count($subject_req); $i++){
                            echo("<div class='form-group-row'>");
                            echo("<label class='col-sm-4 col-form-label' name='subject'><b>Required grade of:</b> {$subject_req[$i]}</label>");
                            echo("<input type='hidden' name='subject' value='{$subject_req[$i]}'>");
                            //echo("<input class='col-sm-4 col-form-input' name='score'  type='number' min='0' max='4' id='reqScore{$i}'>");
                            echo("<select name='score'> 
                                    <option value='nul'>-</option>
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
                    }  
                ?>
            </form>
            
        </div>

        <div class="text-center mt-2" >
            <!--<input class="btn btn-primary" type="submit" value="ยืนยัน">-->
            <span class="btn btn-primary" id="submit">Submit</span>
            <span class="btn btn-danger" id="cancle">Cancel</span>
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
                    if(confirm("ต้องการออกจากหน้านี้หรือไม่")) {
                        location.href = "./student_join_class.php"
                    }
                    
                })

                $("#submit").on("click", ()=>{
                    let resultR;
                    let email           = $("#email").val()
                    let class_id        = $("#class_id").val();
                    //let pass            = $('#password').val();
                    let subject         = [];
                    let score           = [];
                    let data            = {};
                    let setScoreError   = false; //ใช้ check ว่าได้กรอกเลือกคะแนนหรือไม่
    
                    
                    
                    //Set Subject
                    $("input[name='subject']").each(function() {
                        subject.push(this.value)
                    })

                    //Set Score Value
                    $("select[name='score']").each(function() {
                        //ถ้าไม่มีการเลือกเกรด setScoreError จะเป็น true
                        //เพื่อให้เมื่อออก function จะให้ถูก break การทำงาน
                        if(this.value == 'nul') {
                            setScoreError = true;
                        }
                        score.push(this.value);
                    });

                    if(setScoreError) {
                        alert('กรุณากำหนดคะแนน');
                        return false;
                    }

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
                            //alert(result)
                            if(result.trim() === 'PassError') {
                                alert("รหัส Class ไม่ถูกต้อง")
                            } else {
                                alert(result)
                                location.href = "student_dashboard.php"
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