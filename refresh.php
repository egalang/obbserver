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
		   ORDER BY datetime ASC";
$status_result = $conn->query($status);
if ($status_result->num_rows > 0) {
	echo '<table class="table table-bordered table-hover">';
	while($status_row = $status_result->fetch_assoc()){
		echo '<tr><td>'.$status_row['action'].'</td><td>'.$status_row['datetime'].'</td></tr>';
	}
	echo '</table>';
}else{
	echo 'You have not yet timed in for today.';
}


$conn->close();
?>
