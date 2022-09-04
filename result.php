<?php 
 	require_once('DB_Connect.php');
	$question_set_id = $_GET['question_set'];
 	$user_id = 1; // session['user_id']
	$question_set = executeQueryWithParams($mysqli, $query_for_results, [$question_set_id, $user_id]);
	$message = isset($_GET['message']) ? $_GET['message'] : '';
	
	// print_r($question_set->num_rows);
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="quiz.css">
	<title></title>
</head>
<body>
	<div id="main">
		<div id="message"><?= $message ?></div>
		<table>
			<thead>
				<tr>
					<td>Question</td>
					<td>Answer</td>
					<td>Actions</td>
				</tr>
			</thead>
			<tbody>
				<?php if($question_set){
					while ($row = $question_set->fetch_assoc()) {
					$question_set_name = $row['question_set_name'];
				?>
				<tr>
					<td><?= $row['question_text']; ?></td>
					<td><?= $row['question_answer'];  ?></td>
					<td></td>
				</tr>
				<?php }} ?>

			</tbody>
		</table>
		<br>
		<label>Qestion Set: <?= $question_set_name ?></label>
		<label><a href="retake.php?question_set=<?= $question_set_id; ?>">Re-take</a></label>

	</div>
	<?php require_once('footer.php') ?>
</body>
</html>