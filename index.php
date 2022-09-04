<?php 
 	require_once('DB_Connect.php');
 	$user_id = 1; // session['user_id']
	$question_set = executeQueryWithoutParams($mysqli, $query_for_question_set);
	$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div id="main">
		<div id="message"><?= $message ?></div>
		<table>
			<thead>
				<tr>
					<td>Question Set</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>
				<?php if($question_set){
					while ($row = $question_set->fetch_assoc()) {
					$check_question_set = executeQueryWithParams($mysqli, $query_for_count_question_set_questions, [$row['id'], $user_id]);
					$count_question_results = $check_question_set->num_rows;
				 	if($count_question_results != 0){
					   $check_question_set_questions = executeQueryWithParams($mysqli, $query_for_questions_for_question_set, [$row['id']]);
				 	   if($check_question_set_questions->num_rows == $count_question_results){
				 	   	 $link = 'Attempted';
				 	   	 $link .= '/<a href="retake.php?question_set='.$row['id'].'">Re-take quiz</a> ';
				 	   	 $link .= '/<a href="result.php?question_set='.$row['id'].'">See Results</a>';
				 	   }else{
				 	   	 $link = '<a href="quiz.php?question_set='.$row['id'].'">Resume Quiz</a>';
				 	   }	
				 	}else{
				 		$link = '<a href="quiz.php?question_set='.$row['id'].'">Start quiz</a>';
				 	}

				?>
				<tr>
					<td><?= $row['question_set_name']; ?></td>
					<td><?= $link; ?></td>
				</tr>
				<?php }} ?>
			</tbody>
		</table>
	</div>
</body>
</html>