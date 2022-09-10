<?php require_once 'DB_connection.php';
session_destroy();
$_SESSION['message'] = "Log out successfully";
header("Location: login.php");
?>