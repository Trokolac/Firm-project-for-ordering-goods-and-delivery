<!DOCTYPE html>
<?php require_once './Helper.class.php'; ?>
<html lang="en">
<head>
	<title>Copy House</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" type="image/jpg" href="./IMG/favi.jpg"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./FONTS/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="./CSS/util.css">
	<link rel="stylesheet" type="text/css" href="./CSS/main.css">
    <link rel="stylesheet" href="./CSS/bootstrap.min.css" />
   
<!--===============================================================================================-->
</head>
<body>
	
	<?php require_once './User.class.php';
	
	if( isset($_POST['login']) ) {
		$u = new User();
		$u->email = $_POST['email'];
		$u->password = $_POST['password'];
		if( $u->login() ) {
			header("Location: ./index.php");
			die();
		} else {
			header("Location: ./login.php");
			die();
		}
	}

	$us = new User();
	$us = $us->isLoggedIn();

	if($us){
		header("location: ./index.php");
		die();
	}
	
	?>


	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-b-160 p-t-50">
				<form class="login100-form validate-form" action="./login.php" method="post"> 

                    <span class="login100-form-title p-b-43">
						<img src="./IMG/logo.png">
					</span>

					<div class="wrap-input100 rs1 validate-input" data-validate = "Username is required">
						<input class="input100" type="text" name="email">
						<span class="label-input100">E-mail</span>
					</div>
					
					
					<div class="wrap-input100 rs2 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="password">
						<span class="label-input100">Password</span>
					</div>

					<div class="container-login100-form-btn">
						<button name="login" class="login100-form-btn">
							Prijavi se
						</button>
					</div>
					
				</form>

				

                <?php if(Helper::ifError()) { ?>
                    <div class="alert alert-danger">
                    <strong>Greska!</strong> <?php echo Helper::getError(); ?>
                    </div>
                <?php } ?>

                <?php if(Helper::ifMessage()) { ?>
                    <div class="alert alert-dark">
                    <strong>Uspesno!</strong> <?php echo Helper::getMessage(); ?>
                    </div>
                <?php } ?>
			</div>
		</div>
	</div>
	
	

	<script src="./VENDOR/jquery/jquery-3.2.1.min.js"></script>
	<script src="./JS/main.js"></script>
	<script src="./JS/index.js"></script>


</body>
</html>