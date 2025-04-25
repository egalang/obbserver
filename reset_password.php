<?php
include('enrollment_config.php');
//include('../wp-load.php');

//if ( !is_user_logged_in() ) {
//	header("Location: /index.php/login");
//}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  //echo "Connected to editor database. <br>";
}

$password='$2y$10$X/oIOnXPDHsQYUzNmwasXurxru79ZPQZrvLiJaucsXKj12jpdrbou';
$userID = $_GET['id'];

//echo $userID . " - " . $password;

$user="UPDATE ljcsi_lms.mdl_user SET `password` = '$password' WHERE username = '$userID'";
if( $conn->query($user) === TRUE ){
    $studentID = "select * from ljcsi_lms.mdl_user where username = '$userID'";
    $studentID_result = $conn->query($studentID);
    $studentID_row = $studentID_result->fetch_assoc();
    $preference = "insert into ljcsi_lms.mdl_user_preferences (userid,name,value) values (".$studentID_row['id'].",'auth_forcepasswordchange','1')";
    $conn->query($preference);
    echo "Password reset successful. <br>";
} else {
    echo "Error: " . $conn->error . "<br>";
}

?>