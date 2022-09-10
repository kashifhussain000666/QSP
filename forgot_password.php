<?php require_once 'DB_connection.php';
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

 		<h3>Forgot Password</h3>
 		<hr>
 		<form action="process.php" method="POST">
 			<div class="email">
 				<label>Email:</label>
 				<input type="text" name="email" id="email" required=""><br>
 			</div>

 			<div class="submit">
 				<input type="submit" name="password_reset" id="password_reset" value="Send me password reset link">
 			</div>
 			<div class="login">
 				<a href="login.php">login</a>
 			</div>
 		</form>
 	</div>
 </body>
 </html>