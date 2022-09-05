<?php 
 	require_once('DB_Connect.php');
 	$user_id = 1; // session['user_id']
 	$query =   'select
					*
				from
					questions q
				inner join questions_set qs on
				 qs.id = q.question_set_id
				order by q.question_set_id';
	$questions = executeQueryWithoutParams($mysqli, $query);
	$questions_set = executeQueryWithoutParams($mysqli, $query_for_all_question_set);
	$message = isset($_GET['message']) ? $_GET['message'] : '';
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
		<div class="add">
			<h3>Add Question</h3>
			<form action="php_script.php" method="post">
				<label>Question: </label>
				<textarea name="add_question" id="question" placeholder="Please add question"></textarea>
				<br>
				<div id="validation"></div>
				<label>Question Set:</label>
				<select name="question_set">
					<?php while ($record = $questions_set->fetch_assoc()) {?>
						<option value="<?= $record['id'];?>"><?= $record['question_set_name'];?></option>
					<?php } ?>
				</select>
				<br>
				<input type="submit" name="add_question_submit" id="add_question_submit" value="Save">
			</form>
		</div>
		<br>
		<hr>
		<h3>All Questions</h3>
		<table>
			<thead>
				<tr>
					<th>Question </th>
					<th>Question Set</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = $questions->fetch_assoc()) {

				?>
				<tr>
					<td><?= $row['question_text']; ?></td>
					<td><?= $row['question_set_name'];  ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>


	<?php require_once('footer.php') ?>
	<script type="text/javascript" src="script.js"></script>
</body>
</html>