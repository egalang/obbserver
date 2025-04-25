<?php
include('enrollment_config.php');
include('../wp-load.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//get school settings
$settings = "select * from school_settings";
$settings_result=$conn->query($settings);
$settings_row=$settings_result->fetch_assoc();
$school_year = $settings_row['sy'];

$message="";
$balance=0;
//get enrollee data
$enrollee_id = $_GET['id'];
$enrollee="select * from enrollment_list where id=$enrollee_id";
$enrollee_result = $conn->query($enrollee);
$enrollee_row = $enrollee_result->fetch_assoc();
//check if card already available
if($enrollee_row['card']=='Y'){
//set card status
$enrollee="update enrollment_list set card='N' where id=$enrollee_id";
  // $message = "The selected student's card is already published!";
  // header("Location: enrollment_list.php?message=8");
  // exit();
} else {
//set card status
$enrollee="update enrollment_list set card='Y' where id=$enrollee_id";
}

$enrollee_result = $conn->query($enrollee);
if(!$enrollee_result){
    $message = "Something went wrong while updating a record.";
    header("Location: enrollment_list.php?message=2");
    exit();
  }
  
header("Location: enrollment_list.php?message=4");

$conn->close();
?>
