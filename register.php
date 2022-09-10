<?php require_once 'DB_connection.php';
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){
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
 		<div id="message">
 			<?php 
 				if(isset($_SESSION['message'])){
 					echo $_SESSION['message'];
 					unset($_SESSION['message']);
 				}
 			?>
 		</div>
 		<form action="process.php" method="POST" name="registeration" id="registeration" onSubmit="return RegisterformValidation();">
 			<div class="names">
 				<label>First Name:</label>
 				<input type="text" name="first_name" id="first_name"><br>

 				<label>Last Name:</label>
 				<input type="text" name="last_name" id="last_name"><br>

 				<label>Username:</label>
 				<input type="text" name="username" id="username"><br>
 			</div>

 			<div class="contact">
 				<label>Email:</label>
 				<input type="email" name="email" id="email"><br>

 				<label>Phone:</label>
 				<input type="number" name="phone" id="phone"><br>
 			</div>

 			<div class="passwords">
 				<label>Password:</label>
 				<input type="password" name="password" id="password"><br>

 				<label>Confirm Password:</label>
 				<input type="password" name="c_password" id="c_password"><br>
 			</div>

 			<div class="submit">
 				<input type="submit" name="register" id="register" value="Register">
 			</div>

 		</form>
 	</div>
 	<script type="text/javascript" src="script.js"></script>
 </body>
 </html>