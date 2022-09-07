<?php 
 	require_once('DB_Connect.php');
 	$user_id = 1; // session['user_id']
	// $question_set = executeQueryWithoutParams($mysqli, $query_for_question_set);
	$question_set_check_results = executeQueryWithParams($mysqli, $query_for_question_set_check_results, [$user_id]);
	$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="quiz.css">
</head>
<body>
	<div id="main">
		<div id="message"><?= $message ?></div>
		<a href="quiz.php">Start Quiz</a><br><br><br>
		<hr>
		<h3>Question Set Results:</h3>
		<table>
			<thead>
				<tr>
					<th>Question Set</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					while ($row = $question_set_check_results->fetch_assoc()) {
				 	   if($row['attempted'] == 1){
				 	   	 $link = 'Attempted';
				 	   	 $link .= '/<a href="result.php?question_set='.$row['id'].'">See Results</a>';
				 	   }else{
				 	   	 $link = 'N/A';
				 	   }

				?>
				<tr>
					<td><?= $row['question_set_name']; ?></td>
					<td><?= $link; ?></td>
				</tr>
			   <?php } ?>
			</tbody>
		</table>
	</div>

	<a href="questions_crud.php">Add Question</a>
	<a href="question_set_crud.php">Add Question Set</a>
</body>
</html>