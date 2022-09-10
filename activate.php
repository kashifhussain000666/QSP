<?php require_once 'DB_connection.php';
	if (isset($_GET['email']) && isset($_GET['activation_code'])) {
		
        $user = find_unverified_user($mysqli,$_GET['activation_code'], $_GET['email']);

        // if user exists and activate the user successfully
		print_r(activate_user($mysqli, $user['uid']));
		print_r($user);
        if ($user && activate_user($mysqli, $user['uid'])) {
        	$_SESSION['message'] = 'You account has been activated successfully. Please login here.';
            header('Location: login.php');
        }
	}

	function find_unverified_user($mysqli, string $activation_code, string $email)
	{

	    $sql = 'SELECT uid, email_activation_code, email_activation_expiry < now() as expired
	            FROM propusers
	            WHERE active = 0 AND email=?';

	    $user = executeQueryWithParams($mysqli, $sql, [$email]);
	    if ($user->num_rows > 0) {
	        // already expired, delete the in active user with expired activation code
	        $user = $user->fetch_assoc();
	        if ((int)$user['expired'] === 1) {
	            delete_user_by_id($mysqli, $user['id']);
	            return null;
	        }
	        // verify the password
	        if ($activation_code == $user['email_activation_code']) {
	            return $user;
	        }
	    }

	    return null;
	}

	function delete_user_by_id($mysqli, int $id)
	{
	    $sql = 'DELETE FROM propusers
	            WHERE uid = ? and active = 0';

	    return executeQueryWithParams($mysqli, $sql, [$id]);
	}

	function activate_user($mysqli, int $user_id): bool
	{
	    $sql = 'UPDATE propusers
	            SET active = 1,
	                email_activated_at = CURRENT_TIMESTAMP
	            WHERE uid = ?';
	    $result = executeQueryWithParams($mysqli, $sql, [$user_id]);
	    if(isset($result['error'])){
	       return false;
	    }
	    return true;
	}


?>

