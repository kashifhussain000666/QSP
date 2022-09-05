<?php 
 	require_once('DB_Connect.php');
	$question_set_id = $_GET['question_set'];
 	$user_id = 1; // session['user_id']
 	$query_for_question_set = "select
 									*
 							   from 
 							   		questions_set
 							   where 
 							   		id = ?	";
 	$question_set_result = executeQueryWithParams($mysqli, $query_for_question_set, [$question_set_id]);
 	if($question_set_result->num_rows){
 	  $category = $question_set_result->fetch_assoc()['question_set_category'];
 	  if($category == 1){
		$question_set = executeQueryWithParams($mysqli, $query_for_retake, [$question_set_id, $user_id]);
 	  }
 	}
	header("Location: quiz.php");
?>