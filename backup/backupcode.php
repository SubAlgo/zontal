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
                        class.student_limit,
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

        //function random Index
        function randIndex($n) {
            /**
             * สร้างตัวแปร val[] สำหรับเก็บค่าการ random
             * โดยค่าที่จะเก็บเข้า array คือ ค่าที่ไม่ซ้ำกับที่มีอยู่ใน array
             * จำนวนการวนรอบ เท่ากับ จำนวนสมาชิกที่ลงทะเบียนใน class
             */
            $val = [];
            while(count($val)<$n) {
                $r = mt_rand(0,$n-1);
                if(!in_array($r, $val)) {
                    array_push($val, $r);
                }
            }
            //echo(json_encode($val)."<br>");
            return $val;
        }

        //function Random ตำแหน่งข้อมูลนักศึกษา
        function createRandomData($studentData, $rIndex) {
            $rData = [];
            for($i=0; $i<count($studentData); $i++) {
                $rData[$i] = $studentData[$rIndex[$i]];
            }
            return $rData;
        }

        //function RandomGroup
        function createRandomGroup($studentData, $perGroup) {
            $rIndex = randIndex(count($studentData));
            $rData = createRandomData($studentData, $rIndex);
            $groupRandom = createGroup($rData, $perGroup);

            //echo(json_encode($groupRandom));
            return $groupRandom;
        }

        // function สำหรับสร้างข้อมูลกลุ่มที่ถูก random การจัดเรียง โดยขนาด array เท่ากับ จำนวนรอบในการ random
        function createObjRandomData ($studentData, $perGroup, $loopRandom) {
            for($i=0; $i<$loopRandom; $i++) {
                $objRandomData[$i] = createRandomGroup($studentData, $perGroup);
            }
            return $objRandomData;
        }

        //********** สร้างค่า SCORE **********
        //function setgenScore สำหรับ generate score เพื่อส่งไปเก็บใน array $genScore[] 
        function setgenScore($objRandomData, $i) {
            $genScore = generateScore($objRandomData[$i]);
            return $genScore;
        }

        //สร้างข้อมูล $genScore[] ซึ่งเป็นข้อมูลผลลัพธ์การ generate ที่อยู่ในรูปแบบ array
        function setArrayScore($loopRandom, $objRandomData) {
            for($i=0; $i<$loopRandom; $i++) {
                $genScore[$i] = setgenScore($objRandomData, $i);
            }
            return $genScore;
        }
        //********** สร้างค่า SCORE **********
        
        //function สำหรับหา index ของค่าที่ต่ำที่สุดใน array
        function fineMinGenScoreIndex($genScore, $nSelect) {
            /**
             * จุดประสงค์ เพื่อหา index ของค่า min ใน array ตามจำนวนที่ระบุ
             * วิธีการ 
             * 1 หาค่า max ใน array
             * 2 หา index ของค่า min
             * 3 เอาค่า index ที่ได้ไปเก็บไว้ใน array $minIndexs[]
             * 4 แทนค่าข้อมูล min เดิมด้วย ค่า max  | $genScore[$minIndex] = $max;
             * 
             */
            $minIndexs = [];
            $max =  max($genScore);
            //echo("<br>" . $max);
            for($i=0; $i<$nSelect; $i++) {
                $minIndex = array_keys($genScore, min($genScore));
                $minIndex = $minIndex[0];
                array_push($minIndexs, $minIndex);
                $genScore[$minIndex] = $max;
            }
           // echo("<br>" . json_encode($minIndexs));
            //echo("<br>" . json_encode($genScore));
            return $minIndexs;
        }

        //function สำหรับ shift ข้อมูล เพื่อเตรียมส่งไป generate ต่อไป
        function shiftData($minIndexs, $objRandomData, $perGroup) {            
            //หาจำนวนกลุ่มที่ถูกแบ่ง
            $g = count($objRandomData);

            //ใช้เพื่อทำการ deGroup
            $src = []; 
            $shiftData = [];

            for($i=0; $i<$g; $i++) {
                $src = array_merge($src, $objRandomData[$i]);
            }
            //หาจำนวนสมาชิกทั้งหมดใน array
            $n = count($src);
            //shift value
            for($i=0; $i<$n; $i++) {
                if($i == 0) {
                    $shiftData[$i] = $src[$n-1];
                } else {
                    $shiftData[$i] = $src[$i-1];
                }                        
            }
            return($shiftData);
        }

        //function สำหรับ check ว่า class นี้ได้มีการ save ผลการgenerate หรือยัง
        function checkGrouped($conn, $classid) {
            $sql = "SELECT id FROM `result_grouped` WHERE classid = '{$classid}'";
            $result = mysqli_query($conn, $sql);
            //ถ้ามีการบันทึกการจัดกลุ่มแล้วให้ return true
            //ถ้ายังไม่มีการบันทึกให้ return false
            if(mysqli_num_rows($result) > 0) {
                return true;
            } else {
                return false;
            }
        }

        


    ?>

    <?php
        /* ----- DATA -----*/
        $classid = $_GET['c_id'];
        //------------------


        $groupedStatus = checkGrouped($conn, $classid);
        

        
        $classData  = getClassData($conn, $classid);
        $v_req      = $classData['v'];
        $a_req      = $classData['a'];
        $k_req      = $classData['k'];
        $perGroup   = $classData['pergroup'];
        $stdLimit   = $classData['student_limit'];

        $nStudent   = getTotalStudent($conn, $classid);
        $studentData = getStudentData($conn, $classid,$v_req, $a_req, $k_req );     //source หลักข้อมูลนักศึกษา
        //จำนวนกลุ่มทั้งหมด
        $nGroup     = $nStudent / $perGroup;

        //จำนวนรอบการ random
        $loopRandom = ($stdLimit*2) - (round($stdLimit*0.1));
        $perSelect = 50;

        //จำนวนค่า min ที่จะตัดมาใช้
        $nSelect = round(($loopRandom * $perSelect)/100);
        
        /********************
        *                   * 
        *   Randomdata      *
        *                   * 
        ********************/
        
        //random index ใช้สำหรับจัดเรียงข้อมูลตำแหน่ง
        $rIndex = randIndex($nStudent);
        //random data เป็น list ข้อมูลนักศึกษาที่ได้จากการจัดเรียงตามค่า randomIndex
        $rData = createRandomData($studentData, $rIndex);
        //ผลลัพธ์การจัดกลุ่มนักศึกษาก่อนที่จะมีการ random
        $group          = createGroup($studentData, $perGroup);
        $groupRandom    = createGroup($rData, $perGroup);
        
        $generateScore = 0; //กำหนดค่าเริ่มต้นของ generate score

        /******************************************************************
        * ในการ random เพื่อ generate ค่า จะมีการกำหนดรอบของการ random         *
        * โดยในการ random นี้ จะใช้ข้อมูล $studentData ซึ่งเป็น source ข้อมูลหลัก    *
        * เมื่อส่งข้อมูลเข้าไป func ก็จะเอาค่าไป random เพื่อจัดกลุ่มใหม่                *
        * แล้วส่งค่ากลับออกมาเป็น array ข้อมูลที่ถูกจัดเรียงกลุ่มใหม่                    *
        * โดยขนาด array เท่ากับรอบการ random ที่กำหนด                         *
        *******************************************************************/
        $objRandomData =  createObjRandomData($studentData, $perGroup, $loopRandom);
        
        //ผลลัพธ์ของการ generate ที่อยู่ในรูปแบบ array เพื่อให้พร้อมในการนำไปวน loop แสดงผล
        $genScore = setArrayScore($loopRandom, $objRandomData);
        
        $minIndexs = fineMinGenScoreIndex($genScore, $nSelect);

        $bestGroup = ['group' => '', 'score' => 100 ];
        
        
        /* ----- DATA -----*/
    ?>

    <!-- content -->
    <div class="container-fluid">
        <!-- -->
        <div class="container text-center">
            <h3>Generate</h3>
            <?php
                if($groupedStatus) {
                    echo("มีการจัดกลุ่มแล้ว");
                } else {
                    echo("ยังไม่มีการจัดกลุ่ม");
                }
            ?>
        </div>

        <!-- Class Data -->
        <div id="classData" class="container" style="border: 1px solid black;">
            
            <table class="table table-bordered" style="margin-top:10px;">
                <thead>
                    <tr>
                        <td class="text-center table-dark" colspan="2"><h5>ข้อมูลคลาส</h5></td>
                    </tr>
                </thead>
                <tr>
                    <td style="width: 30%"><b>ชื่อคลาส :</b></td>
                    <td style="width: 70%"><?php echo($classData['title']); ?></td>
                </tr>

                <tr>
                    <td><b>รายละเอียดของคลาส :</b></td>
                    <td><?php echo($classData['description']); ?></td>
                </tr>

                <tr>
                    <td><b>จำนวนนักศึกษาที่เข้าร่วมคลาส :</b></td>
                    <td><?php echo($nStudent); ?> คน</td>
                </tr>

                <tr>
                    <td><b>จำนวนกลุ่มในคลาส :</b></td>
                    <td><?php echo($nGroup); ?> กลุ่ม</td>
                </tr>

                <tr>
                    <td><b>จำนวนนักศึกษาต่อกลุ่ม :</b></td>
                    <td><?php echo($perGroup); ?> คน/กลุ่ม</td>
                </tr>
            </table>
        </div>

        <!-- Student Data -->
        <div id="studentData" class="container" style="border: 1px solid black;">
            <p></p>

            <table class="table table-striped table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <td colspan="3" class="table-dark">
                            <h5>ข้อมูลนักศึกษา</h5>
                        </td>
                    </tr>
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
            <div class="row" style="border: 1px solid black;">
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
                    
            <!--********แสดงผลลัพธ์การ Random*********
            *                                       *
            *           แสดงผลลัพธ์การ Random        *
            *                                       *
            **************************************-->
            <?php
                //createRandomGroup($studentData, $perGroup);
                
                for($i=0; $i<$loopRandom; $i++) {
                    echo("<div class='row' style='border: 1px solid black;'>");
                        echo("<div class='col-md-6'>");
                        echo("<b>random รอบที่: {$i} </b><br>");
                        for($j=0; $j<$nGroup;$j++) {
                            $n = $j+1;
                            echo("<b>สมาชิกกลุ่มที่ [ {$n} ] </b>: <br>");
                            foreach($objRandomData[$i][$j] as $value) {
                                echo("<ul>");
                                echo("<li>");
                                echo("  ชื่อ    ". json_encode($value['name']));
                                echo("  คะแนน  ". json_encode($value['score']));
                                echo("</li>");
                                echo("</ul>");
                            }
                        }
                        echo("</div>");
                        echo("<div class='col-md-6'>");
                            echo("<b>ผลลัพธ์การ Generate score</b> : ". $genScore[$i]);
                        
                        echo("</div>");
                    echo("</div>");
                }
            ?>
            <!--********แสดงผลลัพธ์การ Random*********
            *                                       *
            *           แสดงผลลัพธ์การ Random        *
            *                                       *
            **************************************-->

            <!--******** Select score for shift*********
            *                                          *
            *           Select score for shift         *
            *                                          *
            ******************************************-->
            <div class="container" style="margin-top: 10px; background: pink;">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <b>ชุดข้อมูลการจัดกลุ่มเบื้องต้นที่ได้ผลคะแนน Generate Score น้อยที่สุด <?php echo($nSelect); ?> อันดับ</b>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php
                            echo("<div class='text-center'> ผลลัพธ์การ Random รอบที่ ". json_encode($minIndexs)  ."</div>")
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php
                            foreach($minIndexs as $val) {
                                echo("การ Random รอบที่ [". $val. "] ได้คะแนน : " . json_encode($genScore[$val]). "<br>");
                            }
                        ?>
                    </div>
                
                </div>
            </div>
            <?php
            
            ?>
            <!--************* crossOver data ****************
            *                                               *
            *              crossOver data                   *
            *                                               *
            **********************************************-->
            <div class="container" style="background-color:grey;">
                <div class="row text-center">
                    <div class="col-md-12">
                        <h5>จัดกลุ่มด้วยการ CrossOver</h5>
                        <p>จัดเรียงตำแหน่งของข้อมูลที่ได้ผลการ generate ที่ดีที่สุด <?php echo($nSelect); ?> ลำดับแรก ใหม่ด้วยวิธี Cross Over</p>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-md-4 text-center">การจัดกลุ่มต้นฉบับ</div>
                    <div class="col-md-4">ผลลัพธ์การ Cross Over</div>
                    <div class="col-md-4">ผลลัพธ์คะแนนการ Generate หลัง Cross Over</div>
                </div>
                <?php
                //echo("<br>----CrossOver-----<br>");
                    $crossNo = 1;
                    foreach($minIndexs as $val) {
                        $preCross   = $objRandomData[$val];
                        $cross      = $objRandomData[$val];
                        $nGroup     = count($preCross);
                        $nMember    = count($preCross[0]);
                        $mean       = floor($nGroup/2);
                        $countGroup = count($preCross);

                    
                        /**
                         * Cross Over Algorithm
                         */
                        for($i=0; $i<$mean; $i++) {
                            $cross[$i][$i] = $preCross[$nGroup-($i + 1)][$nMember-($i + 1)];
                            $cross[$nGroup-($i + 1)][$nMember-($i + 1)] = $preCross[$i][$i];
                        }


                        echo("<div class='row'>");
                        //-------------------
                        echo("<div class='col-md-1'>");
                            echo($crossNo);
                            $crossNo++;
                        echo("</div>");

                        //-------------------
                        echo("<div class='col-md-4'>");
                            foreach($preCross as $v) {
                                echo("[");
                                foreach($v as $y) {
                                    echo( json_encode($y['name']). " | ");
                                }
                                echo("],");
                                echo("<br>");
                            }
                        echo("</div>");

                        //-------------------
                        echo("<div class='col-md-4'>");
                            foreach($cross as $v) {
                                echo("[");
                                foreach($v as $y) {
                                    echo( json_encode($y['name']). " | ");
                                }
                                echo("]");
                                echo("<br>");
                            }

                        echo("</div>");

                        //-------------------
                        echo("<div class='col-md-3'>");
                            
                            $scoreAfterCross = generateScore($cross);
                            echo($scoreAfterCross);
                            //หาการจัดกลุ่มที่ดี ที่สุด
                            if($bestGroup['score']>$scoreAfterCross) {
                                $bestGroup['group'] = $cross;
                                $bestGroup['score'] = $scoreAfterCross;
                            }
                        echo("</div>");
                        echo("</div>");
                        


                        echo("<br><br>");

                        

                    }
                
            ?>
            
            </div>

            

             <!--************* shift data ***************
            *                                           *
            *                  shift data               *
            *                                           *
            ******************************************-->
            
            <div class="container" style="margin-top: 10px; background: #428ff4;">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h5>จัดกลุ่มด้วยการ Shift Data</h5>
                        <p>จัดเรียงข้อมูลใหม่โดยการ shift โดยเลือกจากกลุ่มการ random ที่ได้คะแนนดีที่สุด <?php echo($nSelect); ?> ลำดับแรก </p>
                    </div>
                </div>

                <?php
                    
                    
                    foreach($minIndexs as $val) {
                        //$objRandomData[$val] คือตำแหน่งข้อมูลที่มีค่าผลลัพธ์การ gen ที่มีค่าน้อยที่สุด
                        echo("<div class='row'>");
                            echo("<div class='col-md-12'>");
                            echo("<br>");
                                echo("<hr>");
                                echo("<h4>Shift ข้อมูลผลการ Random กลุ่มที่ [{$val}]</h4>");
                                $bufferShift = $objRandomData[$val];
                                /****************************************
                                 *                                      *
                                 * รอบมากกว่า $perGroup ก็ไม่มีประโยชน์แล้ว   *  
                                 *                                      * 
                                ****************************************/
                                for($i=0; $i<$perGroup; $i++) {
                                    echo("<br>");
                                    $sho = $i+1;
                                    echo("<b>Generate Score Shift รอบที่ [" . $sho . "]  : </b>");
                                    $bufferShift = shiftData($minIndexs, $bufferShift, $perGroup);
                                    //echo("<h3>". json_encode($bufferShift). "</h3>");
                                    //echo(count($bufferShift));
                                    $bufferShift = createGroup($bufferShift, $perGroup);
                                    echo("<br>");
                                    //แสดงผลการจัดกลุ่ม
                                    foreach($bufferShift as $v) {
                                        foreach($v as $y) {
                                            echo(json_encode($y['name']));
                                        }
                                        echo("<br>");
                                    }
                                    $scoreAfterShift = generateScore($bufferShift);
                                    echo("<b>score : </b> ". $scoreAfterShift);

                                    //หาการจัดกลุ่มที่ดี ที่สุด
                                    if($bestGroup['score']>$scoreAfterShift) {
                                        $bestGroup['group'] = $bufferShift;
                                        $bestGroup['score'] = $scoreAfterShift;
                                    }
                                }
                                
                                //shiftData($minIndexs, $objRandomData[$val], $perGroup);

                            echo("</div>");
                        echo("</div>");
                        //echo("การ Random รอบที่ [". $val. "] ได้คะแนน : " . json_encode($genScore[$val]). "<br>");
                    }
                ?>
            </div>



            <div class="container" style="background-color:green; margin-top:10px;">
                <div class="row">
                    <div class="col-md-12 text-center" >
                        <p><h3>ผลลัพธ์การจัดกลุ่มที่ดีที่สุด</h3></p>
                        <?php 
                            //Show Result Best Group
                            echo("<h5>คะแนน : ".json_encode($bestGroup['score']). "</h5>");
                            //$sendValue = json_encode($bestGroup);
                            $sendValue = [  'classid' => $classid, 
                                            'group' => $bestGroup['group']
                                        ];
                            
                            $sendValue = json_encode($sendValue);
                            //echo($sendValue);
                            echo("<input type='hidden' id='sendValue' value='{$sendValue}'>");
                            $i = 1;
                            foreach($bestGroup['group'] as $v) {
                                echo("<b>กลุ่มที่ [". $i ."] : </b>");
                                foreach($v as $y) {
                                    echo( json_encode($y['name']). " | ");
                                }
                                $i++;
                                echo("<br>");
                            }

                            /**
                             * $groupedStatus = checkGrouped($conn, $classid);
                                if($groupedStatus) {
                                    echo("มีการจัดแล้ว");
                                } else {
                                    echo("ยังไม่มีการจัดกลุ่ม");
                                }
                             */
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="container" style="margin-top:10px;">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <?php
                            if($groupedStatus) {
                                echo("
                                <p>Class ได้ถูกบันทึกผลการจัดกลุ่มเรียบร้อยแล้ว ต้องการบันทึกผลการจัดกลุ่ม ใหม่ หรือ ไม่?</p>
                                <span class='btn btn-primary' id='confirm'>ใช่</span>
                                <span class='btn btn-danger' id='cancel'>ไม่</span>
                                ");
                            }
                        ?>
                        
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-12 text-center">
                        <?php
                            if($groupedStatus) {
                                echo("<span class='btn btn-success' id='send' style='display:none;'>บันทึกผลการจัดกลุ่ม</span>");
                            } else {
                                echo("<span class='btn btn-success' id='send'>บันทึกผลการจัดกลุ่ม</span>");
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                //เมื่อกดปุ่ม ใช่ จะ show ปุ่ม send group report
                $("#confirm").on('click', ()=> {
                    $('#send').show();
                })

                $("#cancel").on('click', ()=> {
                    $('#send').hide();
                })

                //เมื่อกดปุ่ม ใม่ จะ disable ปุ่ม send group report
                $('#send').on('click', ()=>{
                    let data = $('#sendValue').val()
                    let obj = JSON.parse(data)
                    
                    $.ajax({
                        url: 'ajax_save_group.php',
                        type: 'post',
                        data: obj,
                        success: function(result) {
                            //return กลับมาจะเป็น string 
                            //console.log(JSON.parse(result))
                            alert(result)
                            //console.log(result)
                        }
                    }) 
                })
            })
        </script>


    </div>

    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>