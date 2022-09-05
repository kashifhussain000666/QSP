<?php 
$mysqli = new mysqli("localhost","root","","quiz");

if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
// require_once('php_script.php');

// All quries used in the app
// ==========================


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
$query_for_question_set =  "select 
								 *
							from 
								questions_set qs
							where
								(
								select
									count(*)
								from
									question_results qr
								where
									qr.user_id = ?
									and qr.question_set_id  = qs.id
									and qr.question_answer_is_deleted  = 0
								) != (
										select
									       count(*)
										from
											questions q
										where 
										    q.question_set_id  = qs.id
										    and question_is_deleted = 0
									 )
							order by id asc
							limit 1";		
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
$query_for_retake = "update
						question_results
					 set
						question_answer_is_deleted = 1
					 where
						question_set_id = ?
						and user_id = ?	";
$query_for_all_question_set = "select
								    *
							   from 
							   	   questions_set
							   order by id asc	";		
$query_for_question_set_check_results =  "select
											  qs.id, 
											  qs.question_set_name,
											  case 
												  when (
														select
															count(*)
														from
															question_results qr
														where
															qr.user_id = ?
															and qr.question_set_id  = qs.id
															and qr.question_answer_is_deleted  = 0
													   ) != (
															select
														       count(*)
															from
																questions q
															where 
															    q.question_set_id  = qs.id
															    and question_is_deleted = 0
														 )
												then false
												else true
											end as attempted
									     from 
										 questions_set qs";		
$query_for_question_set_add_page = 'select
								*
						   from
							    questions_set qs
						   order by id asc';										 					   				
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

 ?>

