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
    <!--
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    -->
    <title>Generate</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <?php
        $classid = "class3";

        //จำนวนคนลงทะเบียน
        $nStdSQL = mysqli_query($conn, "SELECT std_email FROM student_score WHERE class_id = '{$classid}' GROUP BY std_email");
        $nStd = mysqli_num_rows($nStdSQL);

        //ข้อมูล class
        $classSQL = mysqli_query($conn, "SELECT class.title, class.pergroup, class.v, class.a, class.k FROM class WHERE id = '{$classid}'");
        $classData  = $classSQL->fetch_assoc();

        $classTitle = $classData['title'];
        $perGroup   = $classData['pergroup'];
        $v_req      = $classData['v'];
        $a_req      = $classData['a'];
        $k_req      = $classData['k'];
        
        print_r($classData);
        echo("<br>");
        

        //ข้อมูล student

        //function checkWhoRegister -> เช็คว่าในคลาสมีใครมาลงทะเบียนบ้าง
        function checkWhoRegister ($conn,$classid) {
            $sqltext = "SELECT
                            users.email,
                            users.name,
                            users.v,
                            users.a,
                            users.k
                        FROM
                            users
                        LEFT JOIN student_score ON users.email = student_score.std_email
                        WHERE
                            student_score.class_id = '{$classid}'
                        GROUP BY
                            users.email";
            
            $result = mysqli_query($conn,$sqltext);
            $return = array();
            while($row = $result->fetch_assoc()){
                array_push($return, $row);
            }
            //$row = $result->fetch_assoc();
            return $return;
        }

        //----------get Score ถึงข้อมูลคะแนนของนักเรียนที่ต้องการ ตามที่ class require ไว้---------------
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
            $return = array();
            while($row = $result->fetch_assoc()){
                array_push($return, $row);
            }
            //$row = $result->fetch_assoc();
            return $return;
        }
        

        //สร้าง Array ชุดข้อมูล stdData เพื่อใช้จัดกลุ่มข้อมูลว่านักเรียนคนไหน ได้คะแนนอะไรเท่าไหร่บ้าง
        $stdData = [];
        $myArr = [  "name"  => '',
                    //"v"     => '',
                    //"a"     => '',
                    //"k"     => '',
                    "score" => []
                 ];

        $dbStd = checkWhoRegister($conn, $classid);
        
        for($i=0; $i<count($dbStd); $i++){

            $myArr['name']  = $dbStd[$i]['name'];
            //$myArr['v']     = $dbStd[$i]['v'];
            //$myArr['a']     = $dbStd[$i]['a'];
            //$myArr['k']     = $dbStd[$i]['k'];

            /*บันทึกคะแนน vak */
            if($v_req == 1){
                array_push($myArr['score'], $dbStd[$i]['v']);
            }

            if($a_req == 1){
                array_push($myArr['score'], $dbStd[$i]['a']);
            }

            if($k_req == 1){
                array_push($myArr['score'], $dbStd[$i]['k']);
            }


            /*----- บันทึกคะแนน vak ----- */
            //select ค่าคะแนนที่ต้องการโดยอาศัย function getScore (return array)
            $dbScore = getScore($conn, $classid, $dbStd[$i]['email']);

            //เอาค่าที่ได้มาบันทึกลง $myArr
            for($j=0; $j<count($dbScore); $j++){
                array_push($myArr['score'], $dbScore[$j]['score']);
            }

            //print_r($myArr);
            array_push($stdData, $myArr);
            $myArr['score'] = [];
            //echo("<br>");
        }

        echo("<br>------stdData------<br>");
        for($i=0;$i<count($stdData); $i++) {
            print_r($stdData[$i]);
            echo("<br>22");
        }
        echo("<br>------------------<br>");
        
    ?>

    <!-- content -->
    <div class="container-fluid">
        <!-- -->
        <div class="container text-center">
            <h3>Generate</h3>
        </div>

        <div class="container" style="border: 1px solid black;">
            <p class="text-center">Data</p>
            <p class="text-center"><b>Class:</b> <?php echo($classTitle); ?></p>
            <p>Student register: <?php echo($nStd); ?></p>
            <p>Studen per group: <?php echo($perGroup); ?></p>
            <p>Total group: <?php echo($nStd/$perGroup) ?></p>
            <p>Student in class: </p>

            <?php
                echo("<br>------Grouping Result : จัดกลุ่ม------<br>");
                //เก็บลง buffer พอได้โครงสร้างข้อมูลตามที่ต้องการ ก็เอาข้อมูลจาก buffer ไปใส่ group[$i]
                //เสร็จแล้วก็ล้าง buffer เพื่อเตรียมเก็บข้อมูลสำหรับกลุ่มต่อไป
                $group = [];
                $buffer = array();
                //$n = count($stdData); 

                for($i=0; $i<count($stdData); $i++) { 
                    array_push($buffer, $stdData[$i]);
                    if( (($i+1) % $perGroup == 0) && ($i != 0)) {
                        array_push($group, $buffer);
                        $buffer = [];
                    }
                }
                
                echo("N group : ". count($group). "<br>");

                for($i=0; $i<(count($group));$i++){
                    echo("{$i} : ");
                    echo(json_encode($group[$i]));
                    //print_r($group[$i]);
                    echo("<br>");
                    echo("----------");
                    echo("<br>");
                }

                /*---------------------------------------------------* */
                echo("<br>------ หาค่าเฉลี่ยของแต่ละวิชา ในแต่ละกลุ่ม------<br>");
                echo("เช่น กลุ่มที่ 1 มี 4คน คนที่1 คะแนน[7, 3, 2.0] | คนที่2 คะแนน[7, 5, 3.0] | คนที่3 คะแนน[8, 1, 4.0] | คนที่4 คะแนน[9, 2, 4.0] <br>");


                //$group[กลุ่มที่][คนที่][field'score'][ลำดับคะแนนที่]
                //$group[$i][$k]['score'][$j]

                $nScore = count($group[0][0]['score']);     //จำนวนแถวของคะแนน
                $nGroup = count($group);                    //จำนวนกลุ่มทั้งหมด
                $bufferScore = 0;       //ตัวแปรเก็บข้อมูลคะแนนเพื่อเตรียมเอาไปหาค่าเฉลี่ย
                $bufferAvg = [];        //ตัวแปรเก็บข้อมูลคะแนนเฉลี่ย เพื่อเตรียมเอาไปบันทึกลง $dataAvg
                $dataAvg = [];          //ตัวแปรเก็บข้อมูลคะแนนเฉลี่ย โดยแบ่งเป็นกลุ่ม

                //วนรอบตามจำนวนกลุ่ม
                for($i=0; $i<$nGroup;$i++){
                    echo("<b>Group :</b> {$i} <br>");
                    //วนรอบตามจำนวนคะแนน
                    for($j=0; $j<$nScore; $j++) {
                        //วนรอบตามจำนวนคน
                        echo("<b>คะแนนวิชาตำแหน่งที่ :</b> {$j} <br>");
                        for( $k=0; $k<(count($group[$i])); $k++){
                            //เอาคะแนนวิชา[$j]ของคนในกลุ่มมารวมกัน
                            $bufferScore += $group[$i][$k]['score'][$j];
                            
                            echo("{$group[$i][$k]['score'][$j]} | ");
                        }
                        //เอาค่าเฉลี่ยของคะแนน[$j] ของคนในกลุ่มมาเก็บไว้ในตัวแปร $bufferAvg
                        array_push($bufferAvg, $bufferScore / count($group[$i]));
                        echo("<br>");
                        echo("sumScore in group : {$bufferScore} <br>");
                        
                        //echo("Average score is : {$bufferAvg}");
                        echo("array เก็บข้อมูลค่าเฉลี่ย : ". json_encode($bufferAvg));
                        //Clear bufferScore
                        $bufferScore = 0;
                        echo("<br><br>");
                        //ผลลัพธ์ที่ได้หลังวน loop เสร็จ คือ array ค่าเฉลี่ยของแต่ละวิชาในกลุ่ม
                        //Array ( [0] => 7.5 [1] => 10 [2] => 3.25 [3] => 3.25 ) 
                    }
                    //push array ค่าเฉลี่ยคะแนนของแต่ละวิชาลงโดย $dataAvg โดยแยกเป็นกลุ่ม
                    //ตัวอย่าง ข้อมูลที่ได้ [[7.5,10,3.25,3.25],[5,10,3.25,2.5]]
                    array_push($dataAvg, $bufferAvg);
                    $bufferAvg = [];
                    echo("<br>------------<br>");
                }

                echo("array เก็บข้อมูลค่าเฉลี่ยของแต่ละวิชา แยกตามกลุ่ม : " . json_encode($dataAvg) . "<br><br>");
                
                /**--------------------------------- */
                function generateScore($data) {
                    //$data[กลุ่มที่][คะแนนที่]
                    echo(json_encode($data));

                    $nScore = count($data[0]);
                    $nGroup = count($data);
                    echo("<br>----จำนวนกลุ่ม : {$nGroup} กลุ่ม----<br>");
                    $sumPow = 0;        //สำหรับเก็บผลลัพธ์การเปรียบเทียบคะแนน และนำไปยกกำลัง
                    $bufferPow = [];    //สำหรับเก็บ stack ผลลัพธ์ของเลขยกกำลัง เพื่อเตรียมเอาไป sqrt ต่อไป
                    $scoreGen = 0;      //สำหรับเก็บผลลัพธ์การ sqrt ทุกรอบ และสุดท้ายจะเป็นคำตอบของสมการ
                    echo("[0][0]-[1][0] | [0][1]-[1][1] | [0][2]-[1][2] <br>");
                    echo("[0][0]-[2][0] | [0][1]-[2][1] | [0][2]-[2][2] <br>");
                    echo("[1][0]-[2][0] | [1][1]-[2][1] | [1][2]-[2][2] <br>");
                    echo("<br><br>");
                    
                    // ----- Calculate Algo -----
                    for($i=0; $i<$nGroup-1; $i++) {
                        //echo("i <br>");
                        for($j=1; $j<$nGroup; $j++) {
                            for($k=0; $k<$nScore; $k++) {
                                if(!($i>=$j) && ($k==$k)) {
                                    //echo("[{$i}][{$k}] - [{$j}][{$k}] | ");
                                    $sumPow = pow(($data[$i][$k] - $data[$j][$k]), 2);
                                    array_push($bufferPow, $sumPow);
                                    echo("{$sumPow}<br>");
                                }                   
                            }
                            if(!($i==$j) ) {
                                echo("----<br>");
                                $scoreGen += sqrt(array_sum($bufferPow));
                                echo(json_encode($bufferPow));
                                echo("<br>----<br>");
                                $bufferPow = [];
                            }   
                        }
                    }
                    // ----- Calculate Algo -----
                    echo("<br>-------------<br>");
                    echo($scoreGen);
                    
                    
                    echo("<br>-------------<br>");

                }
                echo("<br>-----Generate Score-----<br>");
                generateScore($dataAvg)
                


            ?>
        </div>

    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>