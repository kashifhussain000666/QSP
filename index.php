<?php require_once 'DB_connection.php';
	if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == false){
		header('Location: login.php');
	}

?>

<?php 
	if(isset($_SESSION['message'])){
		echo $_SESSION['message'];
		unset($_SESSION['message']);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<a href="logout.php">logout</a>
</body>
</html>
