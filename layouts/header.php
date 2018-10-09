<div>Header</div>

<div class="container-fluid text-right" style="margin-right: 5px;">
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