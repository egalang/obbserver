<?php
include('../../enrollment_config.php');
include('../wp-load.php');

//connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// today's date
date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d");

//get employee
$wp = wp_get_current_user();
$wp_id = $wp->ID;

$status = "SELECT * FROM employee_logs
		   where userid=$wp_id and datetime like '$date%'
		   ORDER BY datetime DESC LIMIT 1";
$status_result = $conn->query($status);
if ($status_result->num_rows > 0) {
	$status_row = $status_result->fetch_assoc();
	//echo "Your current status is: ".$status_row['action'];
	if($status_row['action']=='IN'){
		echo 'You are currently IN.';
	}
	if($status_row['action']=='OUT'){
		echo 'You are currently OUT.';
	}
	if($status_row['action']=='TEACHING'){
		echo 'You are currently TEACHING.';
	}
	if($status_row['action']=='TEACHING STOPPED'){
		echo 'You are currently NOT TEACHING.';
	}
}else{
	echo 'You have not yet timed in for today.';
}


$conn->close();
?>
