<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message='';

if(isset($_POST['form1'])) {
    $csrf->verifyRequest();
        
    if(empty($_POST['email']) || empty($_POST['password'])) {
        $error_message = 'Email and/or Password can not be empty<br>';
    } else {
		
		$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
		$password = $_POST['password'];

    	$statement = $pdo->prepare("SELECT * FROM tbl_user WHERE email=? AND status=?");
    	$statement->execute(array($email,'Active'));
    	$total = $statement->rowCount();    
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);    
        if($total==0) {
            $error_message .= 'Invalid email or password.<br>';
        } else {       
            $row = $result[0];
            $row_password = $row['password'];
        
            $password_valid = false;
            // Support modern bcrypt hashes as well as legacy MD5 auto-upgrade
            if (password_verify($password, $row_password)) {
                $password_valid = true;
                if (password_needs_rehash($row_password, PASSWORD_BCRYPT)) {
                    $new_hash = password_hash($password, PASSWORD_BCRYPT);
                    $up_stmt = $pdo->prepare("UPDATE tbl_user SET password=? WHERE id=?");
                    $up_stmt->execute(array($new_hash, $row['id']));
                }
            } elseif ($row_password === md5($password)) {
                $password_valid = true;
                // Automatically rehash MD5 to secure bcrypt
                $new_hash = password_hash($password, PASSWORD_BCRYPT);
                $up_stmt = $pdo->prepare("UPDATE tbl_user SET password=? WHERE id=?");
                $up_stmt->execute(array($new_hash, $row['id']));
            }

            if(!$password_valid) {
                $error_message .= 'Invalid email or password.<br>';
            } else {       
                session_regenerate_id(true);
				$_SESSION['user'] = $row;
                header("location: index.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Login</title>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/ionicons.min.css">
	<link rel="stylesheet" href="css/datepicker3.css">
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/AdminLTE.min.css">
	<link rel="stylesheet" href="css/_all-skins.min.css">

	<link rel="stylesheet" href="style.css">
</head>

<body class="hold-transition login-page sidebar-mini">

<div class="login-box">
	<div class="login-logo">
		<b>Admin Panel</b>
	</div>
  	<div class="login-box-body">
    	<p class="login-box-msg">Log in to start your session</p>
    
	    <?php 
	    if( (isset($error_message)) && ($error_message!='') ):
	        echo '<div class="error">'.$error_message.'</div>';
	    endif;
	    ?>

		<form action="" method="post">
			<?php $csrf->echoInputField(); ?>
			<div class="form-group has-feedback">
				<input class="form-control" placeholder="Email address" name="email" type="email" autocomplete="off" autofocus>
			</div>
			<div class="form-group has-feedback">
				<input class="form-control" placeholder="Password" name="password" type="password" autocomplete="off" value="">
			</div>
			<div class="row">
				<div class="col-xs-8"></div>
				<div class="col-xs-4">
					<input type="submit" class="btn btn-success btn-block btn-flat login-button" name="form1" value="Log In">
				</div>
			</div>
		</form>
	</div>
</div>


<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/select2.full.min.js"></script>
<script src="js/jquery.inputmask.js"></script>
<script src="js/jquery.inputmask.date.extensions.js"></script>
<script src="js/jquery.inputmask.extensions.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/icheck.min.js"></script>
<script src="js/fastclick.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script src="js/app.min.js"></script>
<script src="js/demo.js"></script>

</body>
</html>