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
    <title>Generate Class</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div>
        <div class="container text-center">
            <h3>Generate Class</h3>
            <p>หน้านี้สำหรับ Generate Class</p>
            <form action="" method="get">
                <input type="text" name="cid" id="cid">
                <input type="submit" value="submit">
            </form>
        </div>


        <?php
            if(isset($_GET['cid'])) {
                $sql = "SELECT pre_data.class_id, pre_data.std_email, pre_data.score, class.pergroup, users.name 
                        FROM `pre_data` LEFT JOIN class ON class.id = pre_data.class_id 
                        JOIN users ON users.email = pre_data.std_email
                        WHERE class_id = '{$_GET['cid']}'";
               

                $result = mysqli_query($conn, $sql);
                $mem_join = mysqli_num_rows($result); //จำนวนนักศึกษาที่เข้า join class ทั้งหมด
                $std = [];
                
                $perGroup;
                $std_score; //เก็บข้อมูลคะแนนของนักศึกษาแต่ละคน
                $std_email = [];

                if (mysqli_num_rows($result) > 0) {
                    $i = 0;
                    while($row = $result->fetch_assoc()) {
                        
                        $std[$i] = $row;
                        $std_name[$i] = $row['name'];
                        
                        $perGroup = $row['pergroup'];
                        $std_score[$i] = json_decode($row['score']);
                        print_r($std_score[$i]);
                        echo("<br>");
                        $i++;
                        //print_r($std_score[$i]);
                        //echo("<br>");

                    } 
                }
                // $perGroup คือ จำนวนสมาชิกต่อกลุ่ม
                echo("จำนวนสมากชิกต่อกลุ่ม: {$perGroup} คน / กลุ่ม<br>");   
                //$mem_join คือ จำนวนนักศึกษาทั้งหมด       
                echo("จำนวนนักศึกษาที่ลงทะเบียน: {$mem_join} คน <br>");
                //nGroup คือ จำนวนกลุ่มทั้งหมด
                $nGroup = $mem_join / $perGroup;
                echo("จำนวนกลุ่มทั้งหมดมี: {$nGroup} กลุ่ม <br>");


                echo("Email นักศึกษาคนที่1 :  {$std_name[0]} <br>");
                echo("Email นักศึกษาคนที่2 :  {$std_name[1]} <br>");
                echo("Email นักศึกษาคนที่3 :  {$std_name[2]} <br>");
                echo("Email นักศึกษาคนที่4 :  {$std_name[3]} <br>");
                
                //แบ่งกลุ่ม
                /*
                $group;
                for($i=0; $i<$nGroup; $i++){
                    $group[$i] = "xx";
                }
                print_r($group[0]);
                echo("<br>");
                print_r($group[1]);
                echo("<br>");
                */
                echo("<br>");
                echo("<br>");
                $g = array(
                            "G1"=>"{7,0,10,3.5,3.8}",
                            "G2"=>"{8,0,10,2,2.2}"    
                        );
                print_r($g);
                echo($g['G1']);
                echo("<br>");
                echo("<br>");
                
                //echo("สมาชิกกลุ่ม ที่1");

                $nSubject = count($std_score[0]);
                echo($nSubject);
                //echo("<br>");
                //print_r($std_score[0]);
                echo("<br>");

                for($i=0; $i < $nSubject; $i++) {   //วนรอบสมาชิกทั้งหมด
                    for($j=0; $j < $mem_join; $j++){
                        echo("{$std_score[$j][$i]} | ");
                    } 
                    echo("<br>");
                }
                //print_r($std[0]);
                //echo("<br>");
                //print_r($std[1]);
                //echo("<br>");
                //echo(var_dump(json_decode($std[0]['score'])));

                echo("<br>");
                $myar = json_decode($std[0]['score']);
                //echo($myar[2]);
                
                

            }
            
        ?>

    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>