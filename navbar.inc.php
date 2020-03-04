<?php require_once './User.class.php'; ?>
<?php require_once './Products.class.php'; ?>

<?php if( User::isLoggedIn() ) { ?>

<?php
    $loggedInUser = new User();
    $loggedInUser->loadLoggedInUser();

    
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
                
            <div class="logo">
                <a href="./index.php"> 
                    <img src="./IMG/logo.png" >
                </a>
            </div>

        </div>
        <div class="col-md-7">
            <div class="bar">
                
            <i class="fas fa-truck-loading"></i>
                <span> <i class="far fa-user" style="color:#c7231a;"></i>&ensp;<?php echo $loggedInUser->name; ?> </span> &emsp;
                <a class="slatimail" href="./cart.php"> <span> <i class="far fa-envelope fa-lg" style="color:#c7231a;"></i>&ensp; Poslati mail</span>
                
                <?php 
                $pro = new Product();
                $products = $pro->All();
                ?>
     
 
               
                       

                &emsp; </a>
                <a href="./logout.php"><button class="btn btn-outline-danger btn-sm">Odjavi se</button></a>

            </div>
        </div>
    </div>
</div>
<?php } ?>