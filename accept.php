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
//$school_year = $settings_row['sy'];
$school_year = "2024-2025";

$message="";
$balance=0;
//get enrollee data
$enrollee_id = $_GET['id'];
$enrollee="select * from enrollment_list where id=$enrollee_id";
$enrollee_result = $conn->query($enrollee);
$enrollee_row = $enrollee_result->fetch_assoc();
//check if already accepted
if($enrollee_row['accepted']=='Y'){
  $message = "The selected student is already accepted!";
  header("Location: enrollment_list.php?message=0");
  exit();
}
if($enrollee_row['reviewed']=='N'){
  $message = "The selected student is pending review!";
  header("Location: enrollment_list.php?message=3");
  exit();
}
//get payment matrix data
$matrix_level=$enrollee_row['level'];
$matrix_terms=$enrollee_row['terms'];
$matrix="select * from tuition_matrix where level_id=$matrix_level and term_id=$matrix_terms";
$matrix_result = $conn->query($matrix);


if ($matrix_result->num_rows > 0) {
  //write data to payments table
  while($matrix_row = $matrix_result->fetch_assoc()) {
    $tranche = $matrix_row['tranche'];
    $due_date = $matrix_row['date'];
    $amount = $matrix_row['amount'];
    //echo $tranche.' '.$due_date.' '.$amount;
    $payment="insert into payment_list (enrollment_id,
                                        level_id,
                                        term_id,
                                        tranche,
                                        due_date,
                                        amount,
                                        sy)
                                values ($enrollee_id,
                                        $matrix_level,
                                        $matrix_terms,
                                        $tranche,
                                        '$due_date',
                                        $amount,
                                        '$school_year')";
    if ($conn->query($payment) === TRUE) {
      $message = $message."New record created successfully";
      $balance = $balance + $amount;
    } else {
      $message = $message."Error: " . $sql . "<br>" . $conn->error;
    }
  }
}

//check if with books
/* if($enrollee_row['books']=='Y'){
  $matrix_terms = 11;
  $matrix="select * from tuition_matrix where level_id=$matrix_level and term_id=$matrix_terms";
  $matrix_result = $conn->query($matrix);
  if ($matrix_result->num_rows > 0) {
    //write data to payments table
    while($matrix_row = $matrix_result->fetch_assoc()) {
      $tranche = $matrix_row['tranche'];
      $due_date = $matrix_row['date'];
      $amount = $matrix_row['amount'];
      //echo $tranche.' '.$due_date.' '.$amount;
      $payment="insert into payment_list (enrollment_id,
                                          level_id,
                                          term_id,
                                          tranche,
                                          due_date,
                                          amount,
                                          comments,
                                          sy)
                                  values ($enrollee_id,
                                          $matrix_level,
                                          $matrix_terms,
                                          $tranche,
                                          '$due_date',
                                          $amount,
                                          'Books',
                                          '$school_year')";
      if ($conn->query($payment) === TRUE) {
        $message = $message."New record created successfully";
        $balance = $balance + $amount;
      } else {
        $message = $message."Error: " . $sql . "<br>" . $conn->error;
      }
    }
  }
} */

//set accepted status
$enrollee="update enrollment_list set accepted='Y',balance=$balance where id=$enrollee_id";
$enrollee_result = $conn->query($enrollee);

//send acceptance letter
$wp = wp_get_current_user();
$wp_id = $wp->ID;
$wp_email = $wp->user_email;
$wp_firstname = $wp->first_name;
$wp_lastname = $wp->last_name;
$payment_id = $_GET['id'];
$soa = "select *,grade_levels.name as level_name from enrollment_list
		left join grade_levels on enrollment_list.level=grade_levels.id
		where enrollment_list.id = $payment_id";
$soa_result=$conn->query($soa);
$soa_row=$soa_result->fetch_assoc();

//$wp_firstname=$soa_row['wp_firstname'];
//$wp_lastname=$soa_row['wp_lastname'];
$lastname=$soa_row['lastname'];
$firstname=$soa_row['firstname'];
$middlename=$soa_row['middlename'];
$level=$soa_row['level_name'];
$terms=$soa_row['terms'];
$tranche=$soa_row['tranche'];
$amount=number_format($soa_row['amount'],2,".",",");
$due_date=$soa_row['due_date'];
$enrollee_id=$soa_row['id'];

$contact=$settings_row['contact'];
$position=$settings_row['position'];
$name=$settings_row['name'];
$accept_message=$settings_row['accept_message'];

$to = $soa_row['wp_email'].",".$settings_row['email'];
$subject = 'Your Enrollment Application is Accepted';

$body = "<p>Dear ".$soa_row['wp_firstname']." ".$soa_row['wp_lastname'].",
        <br><br>$accept_message<br><br>
        <table border='1'>
        <tr><td>Student Name</td><td>$lastname, $firstname $middlename</td></tr>
        <tr><td>Grade Level</td><td>$level</td></tr>
        </table>
        <br>
        <a href='http://".$settings_row['domain']."'>Login to your account to check your enrollment status.</a>
        <br><br><br>Thanks,<br><br>$contact<br>$position<br>$name</p>";

$headers = array('Content-Type: text/html; charset=UTF-8');


echo $to."<br>".$body;
if (wp_mail( $to, $subject, $body, $headers )){
	//echo "Email sent";
	header("Location: enrollment_list.php?message=5");
} else {
	echo "Something went wrong. Email not sent.";
}

$conn->close();
?>
