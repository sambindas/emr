<?php
$env = $_SERVER['REMOTE_ADDR']=='127.0.0.1' ? 1 : '';
$env=1;
session_start();
error_reporting($env);
if (!isset($_SESSION['email']))
	header('Location: login.php');
include 'includes/connection.php';

function getLoggedInUser($column = false) {
	$email = $_SESSION['email'];
	$where = array('email'=>$email);
	$record = runSelectQuery('users', $where);
	while ($row = mysqli_fetch_array($record)) {
		$data = $row;
	}

	if ($column)
		return $data[$column];
	else
		return $data;
}

function runSelectQuery($table, $where = false) {
	global $conn;
	$sql = "SELECT * FROM $table where 1=1";
	if ($where) {
		foreach ($where as $wkey => $wvalue) {
			$sql .= " and $wkey = '$wvalue'";
		}
	}
	$run_sql = mysqli_query($conn, $sql);
	return $run_sql;
}
?>