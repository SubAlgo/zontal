<div class="container-fluid text-right" style="background-color:#ef9292; color:white; margin-bottom:0px; ">
    <span>Email: pongsathon.jong@bumail.com <br>Support: 0979944942</span>
</div>

<div class="container-fluid row" style="background-color:#db6262; height:100px; margin:0px; color:white;">
    <div class="col-md-3" style="margin:auto;">
        ZONTAL + 
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