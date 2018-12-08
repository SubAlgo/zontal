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
    <title>แสดงผลลัพธ์การจัดกลุ่ม</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div>
        <?php
            /** DATA **/
            $classId = ($_GET['c_id']);
            $email = $_SESSION['email'];
            $name = checkName($conn, $email);
            $data_decode = getData($conn, $classId);
            $group;

        ?>

        <?php
            /** FUNCTION **/
            function checkName($conn, $email) {
                $sql = "SELECT users.name FROM users WHERE users.email = '{$email}'";
                $result = mysqli_query($conn, $sql);
                while($row = $result->fetch_assoc()) {
                    $data = $row['name'];
                }
                return $data;
            }

            function getData($conn, $classId) {
                $sql = "SELECT result from result_grouped where classid = '{$classId}'";

                $result = mysqli_query($conn, $sql);
                $data;
                if (mysqli_num_rows($result) > 0) {
                    while($row = $result->fetch_assoc()) {

                        $data = $row['result'];
                    
                        echo("<br>");
                    } 
                } else {
                    $arr = array("error");
                    $myJSON = json_encode($arr);
                    echo $myJSON;
                }
                $data_decode = json_decode($data);

                return $data_decode;
            }
        ?>


        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3>คลาสไอดี: <?php echo($_GET['c_id']); ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>ผลลัพธ์การจัดกลุ่ม</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-8"  style="border: 1px solid;">
                    <table class="table table-striped">
                        <thead class="text-center">
                            <tr>
                                <td><b>กลุ่มที่</b></td>
                                <td><b>สมาชิก</b></td>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                                $i = 1;
                                foreach($data_decode as $val) {
                                    echo("<tr>");
                                        echo("<td>{$i}</td>");
                                        $i++;

                                        echo("<td>");
                                            foreach($val as $v) {
                                                if($v->name != $name) {
                                                    echo($v->name);
                                                } else {
                                                    echo("<b><font color='blue'>{$v->name}</font></b>");
                                                    $group = $i;
                                                }
                                                echo(" | ");
                                            }
                                        echo("</td>");

                                    echo("</tr>");
                                }
                            ?>
                        </tbody>
                    </table>


                </div>
                <div class="col-md-2">
                </div>
            
            </div>

            <div class="row text-center">
                <div class="col-md-12">
                    <?php
                        $group = $group-1;
                        echo("<h5>คุณอยู่กลุ่ม: {$group}</h5>");
                    ?>
                </div>
            
            </div>
        
        </div>

    </div>

    
    <!-- Footer -->
    <?php
      include('./layouts/footer.php'); 
    ?>
</body>
</html>