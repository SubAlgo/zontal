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

    <!-- Check permission access -->
    <?php
    
    //ถ้าไม่ได้ login
    if(!isset($_SESSION)) {
        header("Location: http://{$url}");
        die();
    }
    
    //ถ้าไม่ใช้ Teacher
    if($_SESSION['permission'] != 2) {
        header("Location: http://{$url}");
        die();
    }
    ?>
    
    <title>Generate</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <?php
        /*----- FUNCTION -----*/

        //get ข้อมูลของ class
        function getClassData ($conn, $classid) {
            $sql = "SELECT
                        class.id,
                        class.title,
                        class.teacher_email,
                        class.description,
                        class.pergroup,
                        class.v,
                        class.a,
                        class.k
                    FROM
                        class
                    WHERE
                        class.id = '{$classid}'";

            $result = mysqli_query($conn, $sql);
            $data = $result->fetch_assoc();
            return $data;
        }

        //get Object ข้อมูลนักศึกษาที่มาลงทะเบียน join class
        function studentObj ($conn, $classid) {
            $sql = "SELECT
                        users.email,
                        users.name,
                        users.v,
                        users.a,
                        users.k
                    FROM
                        users
                    LEFT JOIN student_score ON users.email = student_score.std_email
                    WHERE
                        student_score.class_id = 'class3'
                    GROUP BY
                        users.email";
            $result = mysqli_query($conn, $sql);
            return $result;
        }

        // get ค่า score ของนักศึกษาแต่ละใน class
        function getScore($conn, $classid, $stdEmail) {
            $sql = "SELECT
                        class.title,
                        users.name,
                        student_score.score
                    FROM
                        users
                    LEFT JOIN student_score ON users.email = student_score.std_email
                    JOIN class ON class.id = student_score.class_id
                    WHERE
                        users.email = '{$stdEmail}' && class.id = '{$classid}'";
            $result = mysqli_query($conn,$sql);
            $data = array();
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            
            return $data;
        }

        //get จำนวนผู้ลงทะเบียน join class
        function getTotalStudent ($conn, $classid) {
            $result = studentObj($conn, $classid);
            $data = mysqli_num_rows($result);
            return $data;
        }

        //Create Student Data
        function getStudentData($conn, $classid, $v_req, $a_req, $k_req) {
            $result = studentObj ($conn, $classid);
            $data = [];
            $buffer =   [   "name"  => '',
                            "score" => []
                        ];

            while($row = $result->fetch_assoc()) {
                $buffer['name'] = $row['name'];
                
                /*บันทึกคะแนน vak */
                if($v_req == 1){
                    array_push($buffer['score'], $row['v']);
                }

                if($a_req == 1){
                    array_push($buffer['score'], $row['a']);
                }

                if($k_req == 1){
                    array_push($buffer['score'], $row['k']);
                }

                /*บันทึกคะแนนวิชาที่ require */
                $arrScore = getScore($conn, $classid, $row['email']);
                foreach($arrScore as $value) {
                    array_push($buffer['score'], $value['score']);
                }

                //ยันข้อมูลจาก buffer ใส่ data
                array_push($data, $buffer);

                //clear ข้อมูลใน buffer
                $buffer =    [ "name"  => '', "score" => [] ];
            }
            
            return $data;
        }

        //Create Group
        function createGroup($studentData, $perGroup) {
            $data = [];
            $buffer = array();

            for($i=0; $i<count($studentData); $i++) { 
                array_push($buffer, $studentData[$i]);
                if( (($i+1) % $perGroup == 0) && ($i != 0)) {
                    array_push($data, $buffer);
                    $buffer = [];
                }
            }
            return $data;
        }

        //get Average Score
        function averageScore($group) {
            //$group[กลุ่มที่][คนที่][field'score'][ลำดับคะแนนที่]
            //$group[$i][$k]['score'][$j]
            $bufferScore    = 0;            //ตัวแปรเก็บข้อมูลคะแนนเพื่อเตรียมเอาไปหาค่าเฉลี่ย
            $bufferAvg      = [];           //ตัวแปรเก็บข้อมูลคะแนนเฉลี่ย เพื่อเตรียมเอาไปบันทึกลง $dataAvg
            $dataAvg        = [];           //ตัวแปรเก็บข้อมูลคะแนนเฉลี่ย โดยแบ่งเป็นกลุ่ม
            $nScore         = count($group[0][0]['score']);
            $nGroup         = count($group);

            for($i=0; $i<$nGroup;$i++){
                
                //วนรอบตามจำนวนคะแนน
                for($j=0; $j<$nScore; $j++) {
                    //วนรอบตามจำนวนคน
                    
                    for( $k=0; $k<(count($group[$i])); $k++){
                        //เอาคะแนนวิชา[$j]ของคนในกลุ่มมารวมกัน
                        $bufferScore += $group[$i][$k]['score'][$j];
                        
                    }
                    //เอาค่าเฉลี่ยของคะแนน[$j] ของคนในกลุ่มมาเก็บไว้ในตัวแปร $bufferAvg
                    array_push($bufferAvg, $bufferScore / count($group[$i]));
                    //Clear bufferScore
                    $bufferScore = 0;
                    //ผลลัพธ์ที่ได้หลังวน loop เสร็จ คือ array ค่าเฉลี่ยของแต่ละวิชาในกลุ่ม
                    //Array ( [0] => 7.5 [1] => 10 [2] => 3.25 [3] => 3.25 ) 
                }
                //push array ค่าเฉลี่ยคะแนนของแต่ละวิชาลงโดย $dataAvg โดยแยกเป็นกลุ่ม
                //ตัวอย่าง ข้อมูลที่ได้ [[7.5,10,3.25,3.25],[5,10,3.25,2.5]]
                array_push($dataAvg, $bufferAvg);
                $bufferAvg = [];
            }
            return $dataAvg;
        }

        //generate Score
        function generateScore($group) {
            $avg    = averageScore($group);
            $nScore = count($avg[0]); //จำนวนคะแนนในแต่ละกลุ่ม
            $nGroup = count($group);

            $sumPow     = 0;        //สำหรับเก็บผลลัพธ์การเปรียบเทียบคะแนน และนำไปยกกำลัง
            $bufferPow  = [];       //สำหรับเก็บ stack ผลลัพธ์ของเลขยกกำลัง เพื่อเตรียมเอาไป sqrt ต่อไป
            $scoreGen   = 0;        //สำหรับเก็บผลลัพธ์การ sqrt ทุกรอบ และสุดท้ายจะเป็นคำตอบของสมการ

            //Loop สำหรับสร้าง index ตัวตั้ง
            for($i=0; $i<$nGroup-1; $i++) {
                //Loop สำหรับสร้าง index ตัวลบ
                for($j=1; $j<$nGroup; $j++) {
                    //Loop สำหรับสร้าง index ตำแหน่งค่าเฉลี่ย
                    for($k=0; $k<$nScore; $k++) {
                        if(!($i>=$j) && ($k==$k)) {
                            $sumPow = pow(($avg[$i][$k] - $avg[$j][$k]), 2);
                            array_push($bufferPow, $sumPow);   
                        }                   
                    }
                    if(!($i>=$j) ) {
                        $scoreGen += sqrt(array_sum($bufferPow));
                        $bufferPow = [];
                    }   
                }
            }
            return $scoreGen;
        }


    ?>

    <?php
        /* ----- DATA -----*/
        $classid = $_POST['classid'];
        
        $classData  = getClassData($conn, $classid);
        $v_req      = $classData['v'];
        $a_req      = $classData['a'];
        $k_req      = $classData['k'];
        $perGroup   = $classData['pergroup'];

        $nStudent   = getTotalStudent($conn, $classid);
        $studentData = getStudentData($conn, $classid,$v_req, $a_req, $k_req );
    
        $group      = createGroup($studentData, $perGroup);    

        $nScore     = count($group[0][0]['score']);
        $nGroup     = $nStudent / $perGroup;
        
        $generateScore = 0; //กำหนดค่าเริ่มต้นของ generate score

    ?>

    <!-- content -->
    <div class="container-fluid">
        <!-- -->
        <div class="container text-center">
            <h3>Generate</h3>
        </div>

        <!-- Class Data -->
        <div id="classData" class="container" style="border: 1px solid black;">
            <p><b>ข้อมูลคลาส</b></p>
            <p><b>ชื่อคลาส</b> : <?php echo($classData['title']); ?></p>
            <p><b>รายละเอียดของคลาส</b> : <?php echo($classData['description']); ?></p>
            <p><b>จำนวนนักศึกษาที่เข้าร่วมคลาส</b> : <?php echo($nStudent); ?> คน</p>
            <p><b>จำนวนกลุ่มในคลาส</b> : <?php echo($nGroup); ?> กลุ่ม</p>
            <p><b>จำนวนนักศึกษาต่อกลุ่ม</b> : <?php echo($perGroup); ?> คน/กลุ่ม</p>
        </div>

        <!-- Student Data -->
        <div id="studentData" class="container" style="border: 1px solid black;">
            <p><b>ข้อมูลนักศึกษา</b></p>

            <table class="table table-striped table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ชื่อ</th>
                        <th scope="col">คะแนน</th>
                        
                    </tr>
                </thead>
                    
                <tbody>
                    <?php
                        $i = 1;
                        foreach($studentData as $value) {
                            echo("<tr>");
                            echo("<th scope='row'>{$i}</th>");
                            echo("<td>{$value['name']}</td>");

                            echo("<td>");
                            echo(json_encode($value['score']));
                            echo("</td>");
                           
                            echo("</tr>");
                            $i++;
                        }
                    ?> 
                </tbody>
            </table>
        </div>

        <!-- Group Data -->
        <div id="groupData" class="container" style="border: 1px solid black;">
            <div class="text-center">
                <h5>จัดกลุ่ม</h5>
            </div>
            
            <hr>
            <div class="row">
                <div class="col-md-6">
                <?php
                    for($i=0; $i<($nGroup);$i++){
                        $n = $i + 1;
                        echo("<b>สมาชิกกลุ่ม [{$n}] </b>: <br>");
                        //echo(json_encode($group[$i]));
                        foreach($group[$i] as $value) {
                            echo("<ul>");
                            echo("<li>");
                            echo("  ชื่อ    ". json_encode($value['name']));
                            echo("  คะแนน  ". json_encode($value['score']));
                            echo("</li>");
                            echo("</ul>");
                        }
                    }
                ?>
                </div>
                <div class="col-md-6">
                    <?php
                        $generateScore = generateScore($group);
                        echo("<b>ผลลัพธ์การ Generate score</b> : ". $generateScore);
                    ?>
                </div>
            
            </div>
            
        
        </div>


    </div>

    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>