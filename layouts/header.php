<!--
    <div class="container-fluid text-right" style="background-color:#ef9292; color:white; margin-bottom:0px; ">
    <span>Email: pongsathon.jong@bumail.com <br>Support: 0979944942</span>
</div>
    

<div class="container-fluid text-right bg-primary text-white">
    <span>Email: pongsathon.jong@bumail.com <br>Support: 0979944942</span>
</div>
-->

<!--
    style="background-color:#db6262; height:100px; margin:0px; color:white;"
 -->   

<div class="container-fluid bg-primary text-white">
    <div class="row" style="height:100px;">
        <div class="col-md-3" style="margin:auto;">
            <a style="color: inherit;" href="./index.php">Group US by Learning </a>
            
        </div>
    
        <div class="col-md-9 text-right" style="margin:auto;">
            <?php
                if(isset($_SESSION['email'])) {
                    echo ("Email : {$_SESSION['email']} <br>");
                }
            
                if(isset($_SESSION['permission'])) {
                    switch($_SESSION['permission']) {
                        case 1 :
                            echo "Status : Admin";
                            break;
                        case 2 :
                            echo "Status : Teacher";
                            break;
                        case 3 :
                            echo "Status : Student";
                            break;
                    }
                }
            ?>
        </div>
    </div>
    

</div>