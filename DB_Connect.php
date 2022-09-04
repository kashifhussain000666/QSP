<?php 
$mysqli = new mysqli("localhost","root","","quiz");

if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
// require_once('queries.php');
$query_for_question_set_questions =  "select
								*
							from
								questions_set qs
							inner join questions q on
								q.question_set_id = qs.id
							where
								qs.id = ?
								and qs.question_set_is_deleted = 0
								and q.question_is_deleted = 0
								and (
										select
											count(*)
										from
											question_results qr
										where
											qr.question_id = q.id
											and qr.user_id = ?
											and question_answer_is_deleted = 0
									) = 0
							order by
								rand()
							limit 1";
$query_for_count_question_set_questions =  "select
								*
							from
								questions_set qs
							inner join questions q on
								q.question_set_id = qs.id
							where
								qs.id = ?
								and qs.question_set_is_deleted = 0
								and q.question_is_deleted = 0
								and (
										select
											count(*)
										from
											question_results qr
										where
											qr.question_id = q.id
											and qr.user_id = ?
											and question_answer_is_deleted = 0
									) = 1";							
$query_for_question_set =  "select *
							from questions_set qs
							order by id asc";		
$query_for_results =   "select
							*
						from
							question_results qr
						inner join questions q on
							q.id = qr.question_id
						inner join questions_set qs on
							qs.id = qr.question_set_id
						where
							qr.question_set_id = ?
							and qr.user_id = ?
							and qr.question_answer_is_deleted = 0
						order by q.id asc";	
$query_for_questions_for_question_set = "select 
											 * 
										 from 
										 	questions 
										 where 
										 	question_set_id = ?";															
function executeQueryWithParams($mysqli, $query, $parameters)
{
    $stmt = $mysqli->stmt_init();
    if ($stmt->prepare($query)) {
        $types = str_repeat("s", count($parameters));
        if ($stmt->bind_param($types, ...$parameters)) {
            if ($stmt->execute()) {
                return $stmt->get_result();
            }
        }
    }
    return ['error' => $stmt->error];
}

function executeQueryWithoutParams($mysqli, $query)
{
    $stmt = $mysqli->stmt_init();
    if ($stmt->prepare($query)) {
        if ($stmt->execute()) {
            return $stmt->get_result();
        }
    }
    return ['error' => $stmt->error];
}
// $r = executeQueryWithParams($mysqli, "select * from questions where id = ?", [9]);
// print_r($r);
// if($r){
// 	while ($row = $r->fetch_assoc()) {
// 	   echo $row['id'];
// 	}
// }
 ?>

