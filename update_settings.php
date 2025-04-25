<?php
include('enrollment_config.php');
// include('../wp-load.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}

$id=$_POST['id'];
$name=$_POST['name'];
$domain=$_POST['domain'];
$address=$_POST['address'];
$city=$_POST['city'];
$contact=$_POST['contact'];
$position=$_POST['position'];
$email=$_POST['email'];
$enroll_message=$_POST['enroll_message'];
$request_message=$_POST['request_message'];
$accept_message=$_POST['accept_message'];
$soa_message=$_POST['soa_message'];
$payment_received=$_POST['payment_received'];
$logo=basename( $_FILES["fileToUpload"]["name"]);
$bc_series = $_POST['bc_series'];
$bc_digits = $_POST['bc_digits'];
$bc_count = $_POST['bc_count'];
if($logo==''){
	$settings="UPDATE school_settings
				  SET id=$id,
					  name='$name',
					  domain='$domain',
					  address='$address',
					  city='$city',
					  contact='$contact',
					  position='$position',
					  email='$email',
					  enroll_message='$enroll_message',
					  request_message='$request_message',
					  accept_message='$accept_message',
					  soa_message='$soa_message',
					  payment_received='$payment_received',
					  bc_series='$bc_series',
					  bc_digits='$bc_digits',
					  bc_count='$bc_count'";
} else {
	$settings="UPDATE school_settings
				  SET id=$id,
					  name='$name',
					  domain='$domain',
					  address='$address',
					  city='$city',
					  contact='$contact',
					  position='$position',
					  enroll_message='$enroll_message',
					  request_message='$request_message',
					  accept_message='$accept_message',
					  soa_message='$soa_message',
					  payment_received='$payment_received',
					  bc_series='$bc_series',
					  bc_digits='$bc_digits',
					  bc_count='$bc_count',
					  logo='$logo'";
}
//echo $settings;
if ($conn->query($settings) === TRUE) {
  header("Location: settings_form.php?message=0");
  //echo "Record updated successfully";
} else {
  header("Location: settings_form.php?message=1");
  //echo "Error updating record: " . $conn->error;
}

?>
