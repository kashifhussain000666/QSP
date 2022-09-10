<?php require_once 'DB_connection.php';
	// unset($_SESSION);
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true ){
		header('Location: index.php');
	}
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
 		<form action="process.php" method="POST">
 			<div class="username">
 				<label>Username:</label>
 				<input type="text" name="username" id="username" required=""><br>
 			</div>

 			<div class="password">
 				<label>Password:</label>
 				<input type="password" name="password" id="password" required=""><br>
 			</div>

 			<div class="submit">
 				<input type="submit" name="login" id="login" value="Login">
 			</div>
 			<div class="forgot">
 				<a href="forgot_password.php">Forgot Password</a>
 			</div>
 			<div class="register">
 				<a href="register.php">Sign up</a>
 			</div>
 		</form>
 	</div>
 </body>
 </html>