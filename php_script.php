<?php require_once('DB_Connect.php');
	if (isset($_POST['quiz_submit'])) {
		extract($_POST);
		$user_id = 1; // session['user_id']
		$created_at = date("Y:m:d:h:i:a");
		$result_query = "insert into quiz.question_results
						 (   
							question_id,
							question_answer,
							user_id,
							question_answer_created_at)
						values (?,?,?,?);";
		$question_set = executeQueryWithParams($mysqli, $result_query, [$id, $question, $user_id, $created_at ]);
		if (!$question_set["error"]) {
			header("Location: quiz.php");
		}				
	}

	if (isset($_POST['question_submit'])) {
		extract($_POST);
		$user_id = 1; // session['user_id']
		$created_at = date("Y:m:d:h:i:a");
		$result_query = "insert into quiz.question_results
						 (   
							question_id,
							question_answer,
							user_id,
							question_answer_created_at,
							question_set_id)
						values (?,?,?,?,?);";
		$question_set_results = executeQueryWithParams($mysqli, $result_query, [$id, $question, $user_id, $created_at, $question_set_id ]);
		if (false) {
			
		}else{
			$question_result = executeQueryWithParams($mysqli, $query_for_question_set_questions, [$question_set_id, $user_id]);
			$check_question_set_questions_count = executeQueryWithParams($mysqli, $query_for_questions_for_question_set, [$question_set_id]);
 		    $check_question_set_count = executeQueryWithParams($mysqli, $query_for_count_question_set_questions, [$question_set_id, $user_id]);
 		    $count_questions = $check_question_set_count->num_rows.'/'.$check_question_set_questions_count->num_rows;
 		    $result_array = ["result" => $question_result->fetch_assoc(), "count" => $count_questions];
			echo json_encode($result_array);
		}				
	}

	if (isset($_POST['add_question'])) {
		extract($_POST);
		$user_id = 1; // session['user_id']
		$created_at = date("Y:m:d:h:i:a");
		$add_query = "insert
						 into quiz.questions(
												question_set_id,
												question_text,
												question_is_deleted,
												question_created_at
											 )
						values(?, ?, ?, ?);";
		$question_set_results = executeQueryWithParams($mysqli, $add_query, [$question_set, $add_question, 0, $created_at]);
		if (!$question_set_results["error"]) {
        	header("Location: questions_crud.php?message=Record added successfully");		
		}else{
			header("Location: questions_crud.php?message=Failure ".$question_set_results["error"]);		
		}				
	}

	if (isset($_POST['add_question_set'])) {
		extract($_POST);
		$user_id = 1; // session['user_id']
		$created_at = date("Y:m:d:h:i:a");
		$add_query = "insert
						 into quiz.questions_set(
												question_set_name,
												question_set_category,
												question_set_is_deleted,
												question_set_created_at
											 )
						values(?, ?, ?, ?);";
		$question_set_results = executeQueryWithParams($mysqli, $add_query, [$add_question_set, $question_set_category, 0, $created_at]);
		if (!$question_set_results["error"]) {
        	header("Location: question_set_crud.php?message=Record added successfully");		
		}else{
			header("Location: question_set_crud.php?message=Failure ".$question_set_results["error"]);		
		}				
	}
 ?>

			




