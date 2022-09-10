<?php require_once 'DB_connection.php';

if (!isset($_GET['reset_token'])) {
	$_SESSION['message'] = 'Invalid';
	header("Location: login.php");
}else{
		extract($_GET);
		$check_token = executeQueryWithParams($mysqli,"select *  from propusers where forgot_password_token = ?", [$reset_token]);
		$result = $check_token->fetch_assoc();
		$current_date = date("Y-m-d H:i:s");
		if($check_token->num_rows == 0){
        	$_SESSION['message'] = 'Invalid token!';
			header("Location: login.php");
			exit();
		}elseif($result['forgot_password_token_expiry'] < $current_date){
			$_SESSION['message'] = 'Token is expired!';
			header("Location: login.php");
			exit();
		}else{
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Login</title>
 </head>
 <body>
 	<div class="main">
 		<div class="message">
 			<?php 
 				if(isset($_SESSION['message'])){
 					echo $_SESSION['message'];
 					unset($_SESSION['message']);
 				}
 			?>
 		</div>

 		<h3>Login</h3>
 		<hr>
 		<form action="process.php" method="POST" id="rest_password_form" onSubmit="return PasswordRestformValidation()">

 			<div class="passwords">
 				<label>New Password:</label>
 				<input type="password" name="new_password" id="new_password" required=""><br>
 				<label>Confirm New Password:</label>
 				<input type="password" name="c_new_password" id="c_new_password" required=""><br>
 			</div>

 			<div class="submit">
 				<input type="hidden" name="uid" value="<?= $result['uid'];?>">
 				<input type="submit" name="rest_password" id="rest_password" value="Change Password">
 			</div>
 			<div class="forgot">
 				<a href="login.php">login</a>
 			</div>
 		</form>
 	</div>
 	<script type="text/javascript" src="script.js"></script>
 </body>
 </html>

 <?php }} ?>