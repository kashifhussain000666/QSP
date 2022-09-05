<?php 
 	require_once('DB_Connect.php');
 	$user_id = 1; // session['user_id']
	$questions_set = executeQueryWithoutParams($mysqli, $query_for_question_set_add_page);
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
		<div class="add">
			<h3>Add Question Set</h3>
			<form action="php_script.php" method="post">
				<label>Question: </label>
				<input type="text" name="add_question_set" id="add_question_set" placeholder="Please add question set">
				<br>
				<div id="validation"></div>
				<br>
				<label>Question Set Category:</label>
				<select name="question_set_category">
					<option value="1">Start Again</option>
					<option value="2">Move to Next</option>
				</select>
				<br>
				<!-- <button type="submit" name="add_question_submit" id="add_question_submit">Submit</button> -->
				<input type="submit" name="add_question_set_submit" id="add_question_set_submit" value="Save">
			</form>
		</div>
		<br>
		<hr>
		<h3>All Question Set</h3>
		<table>
			<thead>
				<tr>
					<th>Question Set</th>
					<th>Question Type</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = $questions_set->fetch_assoc()) {
					    $category = $row['question_set_category'] == 1 ? "Start Again" : "Move to Next";
				?>
				<tr>
					<td><?= $row['question_set_name']; ?></td>
					<td><?= $category;  ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>


	<?php require_once('footer.php') ?>
	<script type="text/javascript" src="script.js"></script>
</body>
</html>