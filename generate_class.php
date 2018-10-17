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
        $classid = "class1";

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
                    "v"     => '',
                    "a"     => '',
                    "k"     => '',
                    "score" => []
                 ];

        $dbStd = checkWhoRegister($conn, $classid);
        
        for($i=0; $i<count($dbStd); $i++){

            $myArr['name']  = $dbStd[$i]['name'];
            $myArr['v']     = $dbStd[$i]['v'];
            $myArr['a']     = $dbStd[$i]['a'];
            $myArr['k']     = $dbStd[$i]['k'];

            $dbScore = getScore($conn, $classid, $dbStd[$i]['email']);
            for($j=0;$j<count($dbScore);$j++){
                array_push($myArr['score'], $dbScore[$j]['score']);
            }
            
            //print_r($myArr);
            array_push($stdData, $myArr);
            $myArr['score'] = [];
            //echo("<br>");
        }

        echo("<br>");
        for($i=0;$i<count($stdData); $i++) {
            print_r($stdData[$i]);
            echo("<br>");
        }
        
        
        
        
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
        </div>

    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>