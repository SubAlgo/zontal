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
    <title>Document</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div class="container">
        <?php
            $classid = $_GET['c_id'];
            $teacherEmail = $_SESSION['email'];
            $checkOwnerClass = checkOwnerClass($conn, $teacherEmail, $classid);
            //echo($checkOwnerClass);

            //check Teacher is own this class
            function checkOwnerClass($conn, $teacherEmail, $classid) {
                $sql = "SELECT class.id FROM class WHERE teacher_email = '{$teacherEmail}' AND id = '{$classid}'";
                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        ?>

        <?php
            //ถ้าไม่ใช่อาจารย์เจ้าของ class จะไม่มีสิทธิลบ class
            if(!$checkOwnerClass) {
                //header("Location: ./teacher_dashboard.php");
                //die();
                echo (" <script LANGUAGE='JavaScript'>
                            window.alert('คุณไม่มีสิทธิในการลบ class นี้');
                            window.location.href='./teacher_dashboard.php';
                        </script>");
            }
        ?>

        <div class="row" style="margin-top:10px;">
            <div class="col-md-12 text-center">
                <p>ต้องการลบ class: <?php echo($classid); ?> ใช่ หรือ ไม่?</p>
                <input type="hidden" id='classid' value='<?php echo($classid); ?>'>
                <p>
                    <span class='btn btn-primary' id='confirm'>ใช่</span>
                    <span class='btn btn-danger' id='cancel'>ไม่</span>
                </p>
            </div>
        </div>
    </div>

    <script type='text/javascript'>
        $(document).ready(function () {
            $('#confirm').on('click', ()=> {
                let c_id = $("#classid").val()

                let data = {'classID' : c_id};
                
                $.ajax({
                        url: 'ajax_del_class.php',
                        type: 'post',
                        data: data,
                        success: function(result) {
                            if(result.trim() === '0') {
                                alert("ลบข้อมูลสำเร็จ")
                                location.href = "teacher_dashboard.php"
                            } else if(result.trim() != '0') {
                                alert('ลบข้อมูลไม่สำเร็จ โปรดติดต่อผู้พัฒนา')
                                location.href = "teacher_dashboard.php"
                            }
                        }
                    });
            })

            $('#cancel').on('click', ()=> {
                location.href = "teacher_dashboard.php"
            })
        })
    </script>

    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>