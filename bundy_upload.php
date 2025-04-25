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
// new filename
$filename = 'pic_'.date('YmdHis') . '.jpeg';

$url = '';
if( move_uploaded_file($_FILES['webcam']['tmp_name'],'upload/'.$filename) ){
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/upload/' . $filename;
	$status = "SELECT * FROM employee_logs
			   where userid=$wp_id and datetime like '$date%'
			   ORDER BY datetime DESC";
	$status_result = $conn->query($status);
	if ($status_result->num_rows > 0) {
		$status_row = $status_result->fetch_assoc();
		if($status_row['action']=='IN'){
			$action='OUT';
			$record = "INSERT INTO employee_logs(userid, action, photo) VALUES ($wp_id,'$action','$url')";
			$conn->query($record);
		}
		if($status_row['action']=='OUT'){
			$action='IN';
			$record = "INSERT INTO employee_logs(userid, action, photo) VALUES ($wp_id,'$action','$url')";
			$conn->query($record);
		}
		if($status_row['action']=='TEACHING STOPPED'){
			$action='OUT';
			$record = "INSERT INTO employee_logs(userid, action, photo) VALUES ($wp_id,'$action','$url')";
			$conn->query($record);
		}
		if($status_row['action']=='TEACHING'){
			echo "Please STOP teaching first";
		}
	}else{
		$record = "INSERT INTO employee_logs(userid, action, photo) VALUES ($wp_id,'IN','$url')";
		$conn->query($record);
	}
}

// Return image url
echo $url;
$conn->close();
