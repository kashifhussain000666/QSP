<?php require_once 'DB_connection.php';
 	
	if (isset($_POST['register'])) {
		extract($_POST);
		$activation_code = substr(str_shuffle("0123456789abcfefghijklmnopqrstuvwxyz"), 0, 100);
		$check_username = executeQueryWithParams($mysqli,"select count(*) as count from propusers where username = ?", [$username])->fetch_assoc()['count'];
		$check_email = executeQueryWithParams($mysqli,"select count(*) as count from propusers where email = ?", [$email])->fetch_assoc()['count'];
		$check_phone = executeQueryWithParams($mysqli,"select count(*) as count from propusers where phone = ?", [$phone])->fetch_assoc()['count'];
		if($check_username > 0){
			$_SESSION['message'] = 'Username already exist';
			header("Location: register.php");
			exit();
		}elseif ($check_email > 0) {
			$_SESSION['message'] = 'Email already exist';
			header("Location: register.php");
			exit();
		}elseif ($check_phone > 0) {
			$_SESSION['message'] = 'Phone already exist';
			header("Location: register.php");
			exit();
		}elseif($password != $c_password){
			$_SESSION['message'] = 'Password does not match';
			header("Location: register.php");
			exit();
		}else{
			$expiry = date('Y-m-d H:i:s',  time() + (3600*24)); //1 day expiry
			$password_hash = password_hash($password, PASSWORD_BCRYPT);
			$query = ' insert
					   into
						`user`.propusers
						(
							uid_prefix,
							`role`,
							firstName,
							lastName,
							email,
							phone,
							userName,
							pwd,
							creationDate,
							email_activation_code,
							email_activation_expiry
						)
						values (?,?,?,?,?,?,?,?,?,?,?);';
			$result = executeQueryWithParams($mysqli, $query, ['prefix','role',$first_name, $last_name, $email, $phone, $username, $password_hash, date('Y-m-d H:i:s'), $activation_code, $expiry]);
			if(!isset($result['error'])){
				echo send_activation_email($email, $activation_code);

				// Uncomment below line when email works fine
				// header("Location: register.php?message=User registered successfully. Please check your email account and activate your account");
			}
		}
		

	}

	function send_activation_email($email, $activation_code)
	{
		// create the activation link
    	$activation_link = APP_URL . "/activate.php?email=".$email."&activation_code=".$activation_code."";
    	// email functionality goes here
    	// set email subject & body
	    $subject = 'Please activate your account';
	    // email header
	    $header = "From:" . SENDER_EMAIL_ADDRESS;
	    $message = "Hi,
		            Please click the following link to activate your account:
		            ".$activation_link."";

	    // send the email
	    // mail($email, $subject, nl2br($message), $header);
	    return $activation_link;        
	}

	function send_password_reset_email($token)
	{
		// create the activation link
    	$password_reset_link = APP_URL . "/password_reset.php?reset_token=".$token."";
    	// email functionality goes here
    	// set email subject & body
	    $subject = 'Please reset your password';
	    // email header
	    $header = "From:" . SENDER_EMAIL_ADDRESS;
	    $message = "Hi, Please click the following link to reset your password:".$password_reset_link."";

	    // send the email
	    // mail($email, $subject, nl2br($message), $header);
	    return $password_reset_link;        
	}


	if (isset($_POST['login'])) {
		extract($_POST);
		$check_login = executeQueryWithParams($mysqli,"select *  from propusers where username = ?", [$username]);
		$result = $check_login->fetch_assoc();
		if($check_login->num_rows == 0){
        	$_SESSION['message'] = 'Invalid Username';
        	$_SESSION['logged_in'] = false;
			header("Location: login.php");
			exit();
		}elseif(!password_verify($password, $result['pwd'])){
        	$_SESSION['message'] = 'Invalid Password';
        	$_SESSION['logged_in'] = false;
			header("Location: login.php");
			exit();
		}elseif($result['active'] == 0){
			$_SESSION['message'] = 'User is not activated, Please contact admin to activate your account';
			$_SESSION['logged_in'] = false;
			header("Location: login.php");
			exit();
		}else{
			$datetime = date('Y-m-d H:i:s'); 
			$password_hash = password_hash($password, PASSWORD_BCRYPT);
			$query = ' update
						`user`.propusers
					   set 
					     lastLog = ?
					   where
					     username = ?   ';
			$update_result = executeQueryWithParams($mysqli, $query, [$datetime, $username]);
			if(!isset($update_result['error'])){
				$_SESSION['message'] = 'User logged in successfully';
				$_SESSION['logged_in'] = true;
				$_SESSION['user_id'] = $result['uid'];
				header("Location: index.php");
			}else{
				$_SESSION['message'] = 'Something went wrong, Please contact administrator';
				header("Location: login.php");
			}
		}
		

	}

	if (isset($_POST['password_reset'])) {
		extract($_POST);
		$check_email = executeQueryWithParams($mysqli,"select *  from propusers where email = ?", [$email]);
		if($check_email->num_rows == 0){
        	$_SESSION['message'] = 'No user is registered with this email';
			header("Location: forgot_password.php");
			exit();
		}else{
		    $result = $check_email->fetch_assoc();
			$expFormat = mktime(date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
			$expDate = date("Y-m-d H:i:s",$expFormat);
			// $key = md5(2418*2."".$email);
			$addKey = substr(md5(uniqid(rand(),1)),3,30);
			$token = $addKey;
			$query = ' update
						`user`.propusers
					   set 
					     forgot_password_token = ?,
					     forgot_password_token_expiry = ?
					   where
					     uid = ?   ';
			$update_result = executeQueryWithParams($mysqli, $query, [$token, $expDate, $result['uid']]);
			if(!isset($update_result['error'])){
				echo send_password_reset_email($token); // Change according to your requirements
				
				// Uncomment below code according to your requirements 
				// $_SESSION['message'] = 'A password reset link is sent to your email. Please check your email. The link will be expired in one day.';
				// header("Location: login.php");
			}else{
				$_SESSION['message'] = 'Something went wrong, Please contact administrator';
				header("Location: forgot_password.php");
			}
		}
		

	}


	if (isset($_POST['rest_password'])) {
		extract($_POST);
		$check_username = executeQueryWithParams($mysqli,"select count(*) as count from propusers where uid = ?", [$uid])->fetch_assoc()['count'];
		if($check_username == 0){
			$_SESSION['message'] = 'Invalid User, Please contact administrator';
			header("Location: login.php");
			exit();
		}elseif($new_password == "" || $c_new_password == ""){
			$_SESSION['message'] = 'All the fields are required';
			header("Location: login.php");
			exit();
		}elseif($new_password != $c_new_password){
			$_SESSION['message'] = 'Password does not match';
			header("Location: login.php");
			exit();
		}else
			$password_hash = password_hash($new_password, PASSWORD_BCRYPT);
			$query = ' update
						`user`.propusers
					   set 
					     pwd = ?
					   where
					     uid = ?';
			$result = executeQueryWithParams($mysqli, $query, [$password_hash, $uid]);
			if(!isset($result['error'])){
				$query = ' update
							`user`.propusers
						   set 
						     forgot_password_token = ?
						   where
						     uid = ?';
				executeQueryWithParams($mysqli, $query, [null, $uid]);
				$_SESSION['message'] = 'Password changed successfully';
				header("Location: login.php");
				exit();
			}else{
				$_SESSION['message'] = 'Something Went Wrong';
				header("Location: login.php");
				exit();
			}
		}
	
 ?>