<?php
	$conn = mysqli_connect('localhost', 'root', '', 'machine_test_db');
	
	if(!$conn){
		die("Error: Failed to connect to database!");
	}
	
?>