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
		   ORDER BY datetime DESC";
$status_result = $conn->query($status);
if ($status_result->num_rows > 0) {
	$status_row = $status_result->fetch_assoc();
	if(($status_row['action']=='IN')or($status_row['action']=='TEACHING STOPPED')){
		$record = "INSERT INTO employee_logs(userid, action, photo) VALUES ($wp_id,'TEACHING','')";
		$conn->query($record);
		echo "You are currently TEACHING.";
	}
	if($status_row['action']=='TEACHING'){
		$record = "INSERT INTO employee_logs(userid, action, photo) VALUES ($wp_id,'TEACHING STOPPED','')";
		$conn->query($record);
		echo "You are currently NOT TEACHING.";
	}
	if($status_row['action']=='OUT'){
		echo "You cannot start teaching while your status is OUT";
	}
}else{
	echo "You cannot start teaching while your status is OUT";
}
$conn->close();
?>
