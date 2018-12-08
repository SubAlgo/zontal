<!DOCTYPE html>
<html lang="en">
<?php
    include('config.php');
?>

<!-- Check permission access -->
<?php
    

    //ถ้าไม่ได้ login ให้ redirect ไปหน้า index
    if(!isset($_SESSION['permission'])) {
        header("Location: http://{$url}");
        die();
    }

    //ถ้าไม่ใช้ Student ให้ redirect ไปหน้า index
    if($_SESSION['permission'] != 3) {
        header("Location: http://{$url}");
        die();
    }

    $email =  $_SESSION['email'];
    $permission = $_SESSION['permission'];

    //check vak_score
    $sql_check = "SELECT v,a,k FROM users WHERE email = '{$email}'";
    $result = mysqli_query($conn, $sql_check);

    while ($row = mysqli_fetch_assoc($result)) {
        $test_v = $row["v"];
        $test_a = $row["a"];
        $test_k = $row["k"];
    }
    //echo("{$test_v} {$test_a} {$test_k}");
    if(($test_v + $test_a+ $test_k) > 0) {
        echo"ทำข้อสอบแล้ว";
    }
    
    

?>

<!-- Algo -->
<?php
    $v = 0;
    $a = 0;
    $k = 0;

    if($method == "POST") {
        
        $src = $_POST;

        function addScore($score) {
            global $v, $a, $k;
            switch ($score) {
                case 1:
                    $v++;
                    break;
                case 2:
                    $a++;
                    break;
                case 3:
                    $k++;
                    break;
            }
        }

        foreach($src as $key => $value) {
            addScore($value);
        }

        //UPDATE `users` SET `v` = '7', `a` = '7', `k` = '6' WHERE `users`.`email` = 'std01@gmail.com' 
       
        $sql = "UPDATE users SET v = {$v}, a = {$a}, k = $k WHERE email = '{$email}'";
        echo $sql;
        if (mysqli_query($conn, $sql)) {
            //echo "Updata Success!!";
            echo '<script type="text/javascript">alert("Update Success");</script>';
            header("Location: http://{$url}");
            die();
        } else {
            echo "Error: {$sql} <br> mysqli_error($conn)";
        }
        //echo("<br>");
        //echo("V: {$v} | A: {$a} | K: {$k}");

    }
?>
<?php
        //echo($_SESSION['email']);
        $checkVAK = checkVAKScore($_SESSION['email'], $conn);

        if($checkVAK > 0) {
            echo "<script>
                    alert('คุณได้ทำการทดสอบเรียบร้อยแล้ว');
                    window.location.href='http://localhost/zontal/';
                   </script>";
        }


        function checkVAKScore($email, $conn) {
            $sql = "SELECT v,a,k FROM users WHERE email = '{$email}'";
            $result = mysqli_query($conn, $sql);

            if(!(mysqli_num_rows($result) > 0)) {
                //echo "Login fail";
            
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    $v = $row["v"];
                    $a = $row['a'];
                    $k = $row['k'];
                }
            }
            $sum = $v + $a + $k;
            //echo($sum);
            return $sum;
        }
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
    <title>VAK Test</title>
</head>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div class="container-fluid">
        <div class="container text-center" style="margin-top:10px;">
            <h3>20 Questions | By Hypnosiswithvic </h3>
        </div>

        <div class="container">
        <label>Analyze your information processing style, and how you best learn. People have varying degrees of visual, auditory, and kinesthetic (body oriented) preferences.</label>
            <h4>Questions and Answers</h4>
            <hr size="10px">
            
            <form action="" method="post">
            
                <fieldset id="t1" required>
                    <label>1. If you were buying a new household appliance, you would</label><br>
                    <input type="radio" value="1" name="t1" required>Fiddle with the knobs and test it out<br>
                    <input type="radio" value="2" name="t1">Talk about it with others<br>
                    <input type="radio" value="3" name="t1">Read about the various models<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t2">
                    <label>2. Recalling __________ is usually easiest for me</label><br>
                    <input type="radio" value="1" name="t2" required>Faces<br>
                    <input type="radio" value="2" name="t2">Experiences<br>
                    <input type="radio" value="3" name="t2">Names<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t3">
                    <label>3. When having an issue with an item you bought, you more often</label><br>
                    <input type="radio" value="1" name="t3" required>Take the item back<br>
                    <input type="radio" value="2" name="t3" required>Write a letter to the company<br>
                    <input type="radio" value="3" name="t3">Call and complain<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t4">
                    <label>4. When you are showing someone else how to do a new task, you typically</label><br>
                    <input type="radio" value="1" name="t4" required>Show them how, and then let them give it a go<br>
                    <input type="radio" value="2" name="t4">Write out step by step directions<br>
                    <input type="radio" value="3" name="t4">Give them a verbal walk-through<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t5">
                    <label>5. When cooking a new type of meal, I prefer</label><br>
                    <input type="radio" value="1" name="t5" required>To call someone else to ask how it is prepared<br>
                    <input type="radio" value="2" name="t5">To follow a recipe<br>
                    <input type="radio" value="3" name="t5">To just start cooking, and feel my way through it<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t6">
                    <label>6. My very first memory is about</label><br>
                    <input type="radio" value="1" name="t6" required>Something I saw<br>
                    <input type="radio" value="2" name="t6">Something I did<br>
                    <input type="radio" value="3" name="t6">Hearing a familiar voice<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t7">
                    <label>7. When meeting someone new, my first impression is of</label><br>
                    <input type="radio" value="1" name="t7" required>What they say<br>
                    <input type="radio" value="2" name="t7">How they look<br>
                    <input type="radio" value="3" name="t7">Their posture or pose<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t8">
                    <label>8. When speaking, I more typically say</label><br>
                    <input type="radio" value="1" name="t8" required>I hear you<br>
                    <input type="radio" value="2" name="t8">I see what you're saying<br>
                    <input type="radio" value="3" name="t8">I understand how you feel<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t9">
                    <label>9. When you have spare time, which is your stronger preference</label><br>
                    <input type="radio" value="1" name="t9" required>Gardening or participating in a sport<br>
                    <input type="radio" value="2" name="t9">Talking with others or listening to music<br>
                    <input type="radio" value="3" name="t9">Going out and seeing new things<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t10">
                    <label>10. If you were to install a new doorknob for the first time, you would</label><br>
                    <input type="radio" value="1" name="t10" required>Ask someone else how to do it<br>
                    <input type="radio" value="2" name="t10">Read the directions<br>
                    <input type="radio" value="3" name="t10">Figure it out as you go<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t11">
                    <label>11. If attending a live music event, you would mostly</label><br>
                    <input type="radio" value="1" name="t11" required>Be moving to the music<br>
                    <input type="radio" value="2" name="t11">Be watching the performers and others<br>
                    <input type="radio" value="3" name="t11">Be listening to the lyrics<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t12">
                    <label>12. If you are nervous about something, you more often</label><br>
                    <input type="radio" value="1" name="t12" required>Find yourself fidgeting or unable to sit still<br>
                    <input type="radio" value="2" name="t12">Talk it over to yourself<br>
                    <input type="radio" value="3" name="t12">Picture possible outcomes of the situation<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t13">
                    <label>13. I enjoy spending my free time</label><br>
                    <input type="radio" value="1" name="t13" required>Talking with someone<br>
                    <input type="radio" value="2" name="t13">Doing something or making something<br>
                    <input type="radio" value="3" name="t13">Watching TV or movies<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t14">
                    <label>14. I would choose a new sofa because I liked</label><br>
                    <input type="radio" value="1" name="t14" required>How it looks, colors, patterns<br>
                    <input type="radio" value="2" name="t14">How it felt, texture and comfort<br>
                    <input type="radio" value="3" name="t14">The description the sales person offered<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t15">
                    <label>15. In choosing food from a menu, I prefer to</label><br>
                    <input type="radio" value="1" name="t15" required>Imagine how it will taste<br>
                    <input type="radio" value="2" name="t15">Look at the pictures<br>
                    <input type="radio" value="3" name="t15">Ask questions about different items<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t16">
                    <label>16. My connections with others are enhanced by</label><br>
                    <input type="radio" value="1" name="t16" required>How they look<br>
                    <input type="radio" value="2" name="t16">What they say to me<br>
                    <input type="radio" value="3" name="t16">How I feel around them<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t17">
                    <label>17. I most enjoy</label><br>
                    <input type="radio" value="1" name="t17" required>Talking on the phone, listening to the radio<br>
                    <input type="radio" value="2" name="t17">Doing something active, enjoying fine foods<br>
                    <input type="radio" value="3" name="t17">Watching TV, movies, or people<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t18">
                    <label>18. When waking from a dream that is quickly fading, I tend to recall</label><br>
                    <input type="radio" value="1" name="t18"required>Images and scenes<br>
                    <input type="radio" value="2" name="t18">How I was feeling<br>
                    <input type="radio" value="3" name="t18">Dialogue and words<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t19">
                    <label>19. When finding a traveling destination, I typically</label><br>
                    <input type="radio" value="1" name="t19" required>Ask for directions<br>
                    <input type="radio" value="2" name="t19">Follow a map<br>
                    <input type="radio" value="3" name="t19">Follow my instincts<br>
                </fieldset>
                <hr size="10px">

                <fieldset id="t20">
                    <label>20. I'm more likely to take time to</label><br>
                    <input type="radio" value="1" name="t20" required>Watch the sunset<br>
                    <input type="radio" value="2" name="t20">Listen to the birds sing<br>
                    <input type="radio" value="3" name="t20">Smell the flowers<br>
                </fieldset>
                <hr size="10px">
                
                <div class="text-center">
                    <input class="btn btn-primary" type="submit" value="Submit">
                    <span class="btn btn-danger">Back</span>          
                </div>
                
                
            </form>
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

