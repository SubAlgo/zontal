<style>
    a:link {
    text-decoration: none;
    color: white;
    }

    a:visited {
        text-decoration: none;
        color: white;
    }

    a:hover {
        color: black;
    }

    a:active {

    }
</style>
<!--<div class="container-fluid text-center" style="border: 1px solid black; background-color:#3d3c3c; color:white;"> -->


<div class="container-fluid text-center bg-info text-white">


<?php
    if( (!isset($_SESSION['email'])) && (!isset($_SESSION['permission'])) ) {
        echo("<a href='http://localhost/zontal/login.php'>Login</a> | ");
        echo("<a href='http://localhost/zontal/student_register.php'>Student_Register</a> | ");
        echo("<a href='http://localhost/zontal/teacher_register.php'>Teacher_Register</a> | ");
    }

    if( (isset($_SESSION['email'])) && (isset($_SESSION['permission'])) ) {
        if($_SESSION['permission'] == 2) {
            echo("<a href='http://localhost/zontal/'>Teacher Dashboard</a> | ");
            echo("<a href='http://localhost/zontal/group_setting.php'>Create_Group</a> | ");
            //echo("<a href='http://localhost/zontal/generate.php'>Generate</a> | ");
            echo("<a href='http://localhost/zontal/logout.php'>Logout</a>");
        }

        if($_SESSION['permission'] == 3) {
            echo("<a href='http://localhost/zontal/student_dashboard.php'>Student Dashboard</a> | ");
            echo("<a href='http://localhost/zontal/student_join_class.php'>join class</a> | ");
            echo("<a href='http://localhost/zontal/vaktest.php'>VAK Test</a> | ");
            echo("<a href='http://localhost/zontal/logout.php'>Logout</a>");
        }
    }
?>
</div>


