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
    
    <title>Class Description</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- FUNCTION -->
    <?php
        //ถ้าไม่ใช่ อาจารย์เจ้าของวิชาจะเข้าดูไม่ได้
        function checkPermissionCanView($conn, $email, $c_id) {
            $sql = "SELECT
                        class.id
                    FROM
                        class
                    WHERE
                        class.teacher_email = '{$email}' AND id = '{$c_id}'";
            $result = mysqli_query($conn, $sql);
            $r = mysqli_num_rows($result);

            if($r > 0) {
                return true;
            } else {
                header( "location: ./teacher_dashboard.php" );
                exit(0);
            }
        }

        function getClassData($conn, $c_id) {
            $data = [];
            $sql = "SELECT
                        class.id,
                        class.title,
                        class.description,
                        class.pergroup,
                        class.n_group,
                        class.student_limit
                    FROM
                        class
                    WHERE class.id = '{$c_id}'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result)) {
                    $data['id']        = $row['id'];
                    $data['title']          = $row['title'];
                    $data['description']    = $row['description'];
                    $data['pergroup']       = $row['pergroup'];
                    $data['nGroup']         = $row['n_group'];
                    $data['stdLimit']       = $row['student_limit'];
                }
            }
            return $data;

        }

        function getStudentData($conn, $c_id) {
            $data = [];
            $sql = "SELECT
                        stdScore.std_email,
                        users.name
                    FROM
                        student_score AS stdScore
                    LEFT JOIN users ON stdScore.std_email = users.email
                    WHERE
                        stdScore.class_id = '{$c_id}'
                    GROUP BY
                        stdScore.std_email";

            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result)) {
                    array_push($data, $row);
                }
            } 
            return $data;
        }

        function getGroupedData ($conn, $c_id) {
            $sql = "SELECT result FROM result_grouped WHERE classid = '{$c_id}'";
            $result = mysqli_query($conn, $sql);
            $data = 0;
                if (mysqli_num_rows($result) > 0) {
                    while($row = $result->fetch_assoc()) {
                        $data = $row['result'];
                    } 
                } else {
                    $arr = array("error");
                    $myJSON = json_encode($arr);
                }

                $data_decode = json_decode($data);
                return $data_decode;
               
        }


    ?>
    
    <!-- DATA -->
    <?php
        $c_id = $_GET['c_id'];
        $email = $_SESSION['email'];
        $classData = getClassData($conn, $c_id);
        $stdData = getStudentData($conn, $c_id);
        $nStd = count($stdData);
        $groupedData = getGroupedData($conn, $c_id);
    ?>

    <?php
        checkPermissionCanView($conn, $email, $c_id);
    ?>


    <!-- content -->

    <?php
        /**
         * ข้อมูล
         * 1. รายละเอียดของคลาสที่สร้าง [id, title, desc, perGroup, n_Group, student_limit]
         * 2. จำนวนนักศึกษาที่ลงทะเบียนแล้ว / limit
         * 3. รายชื่อนักศึกษาที่ลงทะเบียนแล้ว
         * 4. ข้อมูลการจัดกลุ่ม ถ้ามีการจัดกลุ่มแล้วจะมีการแสดงผลการจัดกลุ่ม แต่ยัง จะมีข้อความว่า "ยังไม่มีการจัดกลุ่ม"
         *  //ในการลงทะเบียน ถ้าคลาสเต็มแล้วนักศึกษาจะลงทะเบียนในคลาสนั้นไม่ได้
         */

    ?>
    <div class="container">
        <div class="row" style="margin-top:10px;">
            <div class="col-md-12">
                <table class="table table-bordered" style="width: 80%; margin:auto;">
                    <thead class='text-center'>
                        <tr>
                            <th colspan="2"><b>Class ID :</b> <?php echo($classData['id']); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td style="width: 40%"><b>ชื่อคลาส:</b></td>
                            <td style="width: 60%"><?php echo($classData['title']); ?></td>
                        </tr>

                        <tr>
                            <td><b>รายละเอียดคลาส:</b></td>
                            <td><?php echo($classData['description']); ?></td>
                        </tr>

                        <tr>
                            <td><b>จำนวนนักศึกษาต่อกลุ่ม:</b></td>
                            <td><?php echo($classData['pergroup']); ?> คน/กลุ่ม</td>
                        </tr>

                        <tr>
                            <td><b>จำนวนกลุ่ม:</b></td>
                            <td><?php echo($classData['nGroup']); ?> กลุ่ม</td>
                        </tr>

                        <tr>
                            <td><b>นักศึกษาที่ลงทะเบียนแล้ว/นักศึกษาที่รับได้:</b></td>
                            <td><?php echo("{$nStd}/{$classData['stdLimit']}"); ?>คน</td>
                        </tr>

                        <tr>
                            <td><b>รายชื่อนักศึกษาที่ลงทะเบียน:</b></td>
                            <td>
                                <?php
                                    $i = 1;
                                    foreach($stdData as $val) {
                                        echo("<b>{$i}:</b> {$val['name']} <br>");
                                        $i++;
                                    }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <p><b>ผลลัพธ์การจัดกลุ่ม:</b></p>
                            </td>
                            <td>
                                <?php
                                    if($groupedData == 0) {
                                        echo("ยังไม่มีการจัดกลุ่ม");
                                    } elseif(count($groupedData) > 0) {
                                        $groupNo = 1;
                                       
                                        foreach($groupedData as $val) {
                                            echo("<b>สมาชิกกลุ่มที่:</b> {$groupNo}: ");
                                            $groupNo++;
                                            foreach($val as $v) {
                                                    echo($v->name);
                                                echo(" | ");
                                            }
                                            echo("<br>");
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
        
        

    </div>
    <div style="height:50px;">
    </div>

    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>
</body>
</html>