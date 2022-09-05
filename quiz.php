<?php 
 	require_once('DB_Connect.php');
 	// $question_set_id = $_GET['question_set'];
 	$user_id = 1; // session['user_id']
 	$question_set_id = !isset($_GET['question_set']) ? executeQueryWithParams($mysqli, $query_for_question_set, [$user_id])->fetch_assoc()['id'] : $_GET['question_set'];
	$question_set = executeQueryWithParams($mysqli, $query_for_question_set_questions, [$question_set_id, $user_id]);
 	if($question_set->num_rows == 0){
 		header("location: index.php?message=No questions available in this question set");
 	}
 	$check_question_set_questions_count = executeQueryWithParams($mysqli, $query_for_questions_for_question_set, [$question_set_id]);
 	$check_question_set_count = executeQueryWithParams($mysqli, $query_for_count_question_set_questions, [$question_set_id, $user_id]);
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="quiz.css">
	<title></title>
</head>
<body>
	<div id="main">
		<form method="post" >
			<?php while ($row = $question_set->fetch_assoc()) {
			?>
			<label id="question_label"><?= $row['question_text']; ?></label>
			<br>
			<!-- <input type="text" name="question" class="questions"> -->
			<textarea type="text" name="question" class="questions" placeholder="Answer this question" id="question"></textarea>
			<input type="hidden" name="id" value="<?= $row['id'];?>" id="id">
			<input type="hidden" name="question_set_id" id="question_set_id" value="<?= $question_set_id;?>">
			<?php } ?>
			<button type="button" name="quiz_submit" id="submit">Save</button><br>
			<div id="question_count"><?= $check_question_set_count->num_rows ?>/<?= $check_question_set_questions_count->num_rows ?> </div><br>
			<div id="validation"></div>

		</form>
	</div>


<?php require_once('footer.php') ?>

<script type="text/javascript" src="script.js"></script>
</body>
</html>