<?php 
 	require_once('DB_Connect.php');
	$question_set_id = $_GET['question_set'];
 	$user_id = 1; // session['user_id']
	$query_for_retake = "update
							question_results
						set
							question_answer_is_deleted = 1
						where
							question_set_id = ?
							and user_id = ?	";
	$question_set = executeQueryWithParams($mysqli, $query_for_retake, [$question_set_id, $user_id]);
	header("Location: quiz.php?question_set=".$question_set_id."");
?>