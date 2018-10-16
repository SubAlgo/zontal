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
                <span class="btn btn-primary">ปุ่ม</span>
            </form>

            
        </div>


        <?php
            if(isset($_GET['cid'])) {
                $nStd; //จำนวนนักศึกษาที่ลงทะเบียนทั้งหมดในคลาส
                $nGroup;
                $perGroup;

                $data;


                $sql = "SELECT 
                            pre_data.class_id, 
                            pre_data.std_email, 
                            pre_data.score, 
                            class.pergroup, 
                            users.name 
                        FROM `pre_data` LEFT JOIN class ON class.id = pre_data.class_id 
                        JOIN users ON users.email = pre_data.std_email
                        WHERE class_id = '{$_GET['cid']}'";
               

                $result = mysqli_query($conn, $sql);
                $nStd = mysqli_num_rows($result);

                if ($nStd > 0) {
                    $i = 0;
                    while($row = $result->fetch_assoc()) {
                        //print_r($row);
                        //echo("<br>");
                        $perGroup = $row['pergroup'];
                        $data[$i] = $row;
                        print_r($data[$i]);
                        echo("<br>");
                    } 
                }
                
                $nGroup = $nStd/$perGroup;


                /*---------------SELECT TEST---------------- */
                $sqlTest = "SELECT
                                    class.title as class_title,
                                    subject_req.title as subject_req,
                                    test.std_email,
                                    test.score
                            FROM
                                    subject_req
                            LEFT JOIN class ON subject_req.class_id = class.id
                            JOIN test ON test.sub_req_id = subject_req.id
                            WHERE
                                    class.title = 'CS411' AND test.std_email = 'std01@gmail.com'";

                $res = mysqli_query($conn, $sqlTest);

                $nRow = mysqli_num_rows($res);
                echo("Test row =  {$nRow} <br>");
                
                if($nRow>0) {
                    $i = 0;
                    while($r = $res->fetch_assoc()) {
                        print_r($r);
                        echo("<br>");
                        echo("{$r['score']} <br>");
                    
                    }
                }

               
            }
            
        ?>

        <div class="row">
            <div class="col-md-12">
            <?php
                $data1 = array(
                    'contact' => array(
                        'city' => 'New York',
                        'email' => 'my@mail.com'
                        
                    ),
                    'enabled' => true,
                    'firstName' => 'Robert',
                    'lastName' => 'Exer'
                );

                $data_string = json_encode($data1);

                print_r($data_string);
                echo("<br>");
                print_r($data1['contact']['city']);

               // $dc ={"contact":{"city":"New York","email":"my@mail.com"},"enabled":true,"firstName":"Robert","lastName":"Exer"};

            ?>
            
            </div>
        
        
        </div>

        <div class="container">
            <br>
            <div class="row text-center">
                <div class="col-md-12"><p>จำนวนนักศึกษาที่ลงทะเบียนในคลาส : <?php echo ("{$nStd}"); ?> คน</p></div>
            </div>

            <div class="row text-center">
                <div class="col-md-12"><p>จำนวนกลุ่มทั้งหมด : <?php echo ("{$nGroup}"); ?> กลุ่ม</p></div>
            </div>

            <div class="row text-center">
                <div class="col-md-12"><p>จำนวนนักศึกษาต่อกลุ่ม : <?php echo ("{$perGroup}"); ?> คน / กลุ่ม</p></div>
            </div>
            
            <?php 
                echo("<br>");
                print_r($data[0]);
                echo("<br>");
                echo($data[0]['score'] . "<br>");
                echo(var_dump($data[0]['score'][0]) . "<br>");
                
                for($i=0; $i<$nGroup; $i++){
                    
                }

                /***********************************************************************
                 * สิ่งที่เราจะทำ คือ เราจะปรับโครงสร้าง DB ใหม่
                 * และ เพิ่มโค้ดให้ตรวจสอบว่า นักศึกษาคนนั้นได้กรอกคะแนน หรือ ยัง 
                 * ถ้ายังไม่ได้กรอก โค้ดจะเป็นการ insert into แต่ถ้ากรอกแล้ว code จะเป็นการ update
                ***********************************************************************/
               
            ?>

            
        
        </div>

    </div>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>