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
$settings="select * from school_settings";
$settings_result=$conn->query($settings);
$settings_row=$settings_result->fetch_assoc();
//get $enrollee data
$enrollee_id=$_GET['id'];
$enrollee="SELECT * FROM enrollment_list where id=$enrollee_id";
$enrollee_result = $conn->query($enrollee);
$enrollee_row = $enrollee_result->fetch_assoc();
//get comment
$comment=$_GET['comment'];

$to = $enrollee_row['wp_email'];
$subject = 'Request for Additional Information (or Documents)';

$body = "<p>Dear ".$enrollee_row['firstname']." ".$enrollee_row['lastname'].",
        <br><br>".$settings_row['request_message']."<br><br>$comment<br><br>
        <a href='http://".$settings_row['domain']."/index.php/login'>Login to your account to edit your application or upload files.</a>
        <br><br><br>Thanks,<br><br>".$settings_row['contact']."<br>".$settings_row['position']."<br>".$settings_row['name']."</p>";

$headers = array('Content-Type: text/html; charset=UTF-8');

//echo $body;
if (wp_mail( $to, $subject, $body, $headers )){
  header("Location: enrollment_list.php?message=6");
  //echo "Email Sent.";
} else {
  echo "Something went wrong. Email not sent.";
}
$conn->close();
?>
