<?php
include('enrollment_config.php');

$course_id = $_GET['cid'];
$period_id = $_GET['pid'];
$type_id = $_GET['tid'];
$lms_id = $_GET['lms_id'];
$title = $_GET['title'].'&nbsp;<span class="label label-default">LMS</span>';
$desc = $_GET['desc'];
$ts = $_GET['ts'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO assignments (type_id, period_id, lms_id, name, description, top_score)
        VALUES ($type_id, $period_id, $lms_id, '$title', '$desc', $ts)";

if ($conn->query($sql) === TRUE) {
  //echo "New record created successfully";
  header("Location: gradebook3_list.php?cid=$course_id&pid=$period_id&tid=$type_id&alert=0");
  "";
} else {
  //echo "Error: " . $sql . "<br>" . $conn->error;
  header("Location: gradebook3_list.php?cid=$course_id&pid=$period_id&tid=$type_id&alert=1");
}

$conn->close();
?>