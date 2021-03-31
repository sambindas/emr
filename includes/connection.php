<?php
	

	if ($env ==1) {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "bu";
	} else {
		$servername = "localhost";
		$username = "bindprge_bindas";
		$password = "patience56";
		$database = "bindprge_bu";
	}

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $database);
?> 