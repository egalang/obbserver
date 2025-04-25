<?php
include('enrollment_config.php');
include('../wp-load.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$wp = wp_get_current_user();
$wp_id = $wp->ID;
$wp_email = $wp->user_email;
$wp_firstname = $wp->first_name;
$wp_lastname = $wp->last_name;
// $message = "";
// $target_dir = "uploads/";
// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// $uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
//
// // Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//   $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//   if($check !== false) {
//     $message = $message."File is an image - " . $check["mime"] . ". ";
//     $uploadOk = 1;
//   } else {
//     $message = $message."File is not an image. ";
//     $uploadOk = 0;
//   }
// }
//
// // Check if file already exists
// if (file_exists($target_file)) {
//   $message = $message."The file already exists. ";
//   $uploadOk = 0;
// }
//
// // Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//   $message = $message."The file is too large. ";
//   $uploadOk = 0;
// }
//
// // Allow certain file formats
// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
// && $imageFileType != "gif" ) {
//   $message = $message."Only JPG, JPEG, PNG & GIF files are allowed. ";
//   $uploadOk = 0;
// }
//
// // Check if $uploadOk is set to 0 by an error
// if ($uploadOk == 0) {
//   $message = $message."Sorry, your file was not uploaded. ";
// // if everything is ok, try to upload file
// } else {
//   if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//     $message = $message."The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. ";
//   } else {
//     $message = $message."Sorry, there was an error uploading your file. ";
//   }
// }
//
// if ($uploadOk <> 0) {
  //$books = $_POST['books'];
  $books = 'Y';
  if(!isset($_POST['id'])){
    $old_id = 0;
  } else {
    $old_id = $_POST['id'];
  }
  $sy = $_POST['sy'];
  $wlrn = $_POST['wlrn'];
  $lrn = $_POST['lrn'];
  $psa = $_POST['psa'];
  $lastname = $_POST['lastname'];
  $firstname = $_POST['firstname'];
  $middlename = $_POST['middlename'];
  $extname = $_POST['extname'];
  $birth = $_POST['birth'];
  $age = $_POST['age'];
  $sex = $_POST['sex'];
  $ip = $_POST['ip'];
  $iptext = $_POST['iptext'];
  $mt = $_POST['mt'];
  $add1 = $_POST['add1'];
  $add2 = $_POST['add2'];
  $add3 = $_POST['add3'];
  $zip = $_POST['zip'];
  $father = $_POST['father'];
  $mother = $_POST['mother'];
  $guardian = $_POST['guardian'];
  $phone1 = $_POST['phone1'];
  $phone2 = $_POST['phone2'];
  $phone3 = $_POST['phone3'];
  $lastgradelevel = $_POST['lastgradelevel'];
  $lastSYcompleted = $_POST['lastSYcompleted'];
  $schoolname = $_POST['schoolname'];
  $schoolid = $_POST['schoolid'];
  $schooladd = $_POST['schooladd'];
  $semester = $_POST['semester'];
  $track = $_POST['track'];
  $strand = $_POST['strand'];
  $level = $_POST['level'];
  $terms = 0;//$_POST['terms'];
  $vax = $_POST['vax'];
  $vax1 = $_POST['vax1'];
  if($vax1==''){
    $vax1='1900-01-01';
  }
    $vax2 = $_POST['vax2'];
    if($vax2==''){
      $vax2='1900-01-01';
    }
    // $attachment = basename( $_FILES["fileToUpload"]["name"]);
  $type = $_POST['type'];
  $sql = "INSERT INTO enrollment_list(wp_id,
                    				  wp_email,
                                      wp_firstname,
                                      wp_lastname,
                    				  sy,
                                      wlrn,
                                      lrn,
                                      psa,
                                      lastname,
                                      firstname,
                                      middlename,
                                      extname,
                                      birth,
                                      age,
                                      sex,
                                      ip,
                                      iptext,
                                      mt,
                                      add1,
                                      add2,
                                      add3,
                                      zip,
                                      father,
                                      mother,
                                      guardian,
                                      phone1,
                                      phone2,
                                      phone3,
                                      lastgradelevel,
                                      lastSYcompleted,
                                      schoolname,
                                      schoolid,
                                      schooladd,
                                      semester,
                                      track,
                                      strand,
                                      level,
                                      terms,
				                              old_id,
                                      books,
                                      vax,
                                      vax1,
                                      vax2,
                                      reserved,
                                      type)
                              VALUES ($wp_id,
                    				  '$wp_email',
                                      '$wp_firstname',
                                      '$wp_lastname',
                    				  '$sy',
                    				  '$wlrn',
                                      '$lrn',
                                      '$psa',
                                      '$lastname',
                                      '$firstname',
                                      '$middlename',
                                      '$extname',
                                      '$birth',
                                      $age,
                                      '$sex',
                                      '$ip',
                                      '$iptext',
                                      '$mt',
                                      '$add1',
                                      '$add2',
                                      '$add3',
                                      $zip,
                                      '$father',
                                      '$mother',
                                      '$guardian',
                                      '$phone1',
                                      '$phone2',
                                      '$phone3',
                                      '$lastgradelevel',
                                      '$lastSYcompleted',
                                      '$schoolname',
                                      '$schoolid',
                                      '$schooladd',
                                      '$semester',
                                      '$track',
                                      '$strand',
                                      $level,
                                      $terms,
                        				      $old_id,
                                      '$books',
                                      '$vax',
                                      '$vax1',
                                      '$vax2',
                                      'Y',
                                      '$type')";

  if ($conn->query($sql) === TRUE) {



	//send email start
	
	//get school settings
	$settings="select * from school_settings";
	$settings_result=$conn->query($settings);
	$settings_row=$settings_result->fetch_assoc();	
	
	//$to = $wp_email.",".$settings_row['email']; // temporary disable email to school
  $to = $wp_email; 
	$subject = 'Reservation Request Received';

	$body = "<p>Dear ".$wp_firstname." ".$wp_lastname.",
			<br><br>
      <p>Good day and God bless!</p><p><br></p><p>We have received your request. To confirm your reservation, please pay the amount of Two Thousand Pesos (P2,000.00) immediately through the following modes of payment:</p><p><br></p><p>1. At LJCS Office, Monday to Friday, 8am to 3pm. Credit cards are now accepted.</p><p><br></p><p>2. Online bank transfer:</p><ul><li>Bank of the Philippine Islands</li><li>Account Name: Lord&apos;s Jewels Christian School Inc.</li><li>Account # 4185 8043 43</li></ul><p><br></p><p>Kindly screen shot your payment details and send to&nbsp;<a href='mailto:office_ljcsiaccounts@yahoo.com' target='_blank' style='color: rgb(17, 85, 204);'><strong>office_ljcsiaccounts@yahoo.com</strong></a>.</p><p>Please write the following information:</p><ul><li>Full Name</li><li>Grade Level</li><li>Purpose of Payment</li></ul><p><br></p><p>We look forward to hearing from you. Good day and God bless.</p><p><br></p><p>Please contact us if you have any questions or concerns.</p><ul><li>Land Line No: 02 8532 8695&nbsp;</li><li>Mobile No: 0928 857 3000&nbsp;</li><li>Messenger through our Facebook account</li></ul><p><br></p><p>PLEASE DO NOT REPLY TO THIS EMAIL.</p><p><a href='http://x8mur.mjt.lu/lnk/AV0AADyYvv4AAAAAAAAAAAG3iygAAAAAva0AAAAAABeSbwBmHOzntYadH8fqRgiAnjocZpD4DwAXHIo/1/Js4bbeAETwlV_lSv11dPmg/aHR0cDovL2xqY3NpLm9iYnNlcnZlci5jb20vaW5kZXgucGhwL2xvZ2lu' target='_blank' style='background-color: rgb(255, 255, 255); color: rgb(25, 106, 212);'>Login to your account to edit your application or upload files.</a>Always check your email for important information regarding your child&apos;s enrollment.</p><p><br></p><p>We look forward to hearing from you.</p><p><br></p><p>Thank you very much.</p><p><br></p><p><span style='color: rgb(34, 34, 34);'>Anna C. Aquino</span></p><p><span style='color: rgb(34, 34, 34);'>Registrar</span></p><p><span style='color: rgb(34, 34, 34);'>Lord`s Jewels Christian School</span></p>";

	$headers = array('Content-Type: text/html; charset=UTF-8');

	//echo $body;
	if (wp_mail( $to, $subject, $body, $headers )){
		//header("Location: enrollment_list.php?message=6");
		//echo "Email Sent.";
		header("Location: reserve_list.php?message=1");
	} else {
		echo "Something went wrong. Email not sent.";
	}
	//send email end
    
	
	
	
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
// }
//header("Location: enrollment_form.php?success=$uploadOk&message=$message");
//header("Location: enrollment_form.php");
$conn->close();
?>