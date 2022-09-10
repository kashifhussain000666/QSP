<?php session_start();
const APP_URL = 'http://localhost/user';
const SENDER_EMAIL_ADDRESS = 'no-reply@email.com';
$mysqli = new mysqli("localhost","root","","user");

if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

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
