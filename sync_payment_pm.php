<?php
include('../../enrollment_config.php');
include('../wp-load.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$payments= "select payment_list.id,payment_list.enrollment_id,payment_list.level_id,payment_list.term_id,
			payment_list.tranche,payment_list.amount,enrollment_list.lastname,enrollment_list.firstname,
			payment_terms.name as term_name,grade_levels.name as grade_name from payment_list
			left join enrollment_list on payment_list.enrollment_id = enrollment_list.id
			left join grade_levels on payment_list.level_id = grade_levels.id
			left join payment_terms on payment_list.term_id = payment_terms.id
			where billed='Y' and paid='N'";
$payments_result = $conn->query($payments);

if ($payments_result->num_rows > 0) {
  // output data of each row
  while($payments_row = $payments_result->fetch_assoc()) {
    //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
	$payment_id=$payments_row['id'];
	$paid_date=date("Y-m-d");
	$enrollee_id=$payments_row['enrollment_id'];
	//echo $payment_id.", ".$paid_date."<br>";
	$refno=$payments_row['id'].$payments_row['enrollment_id'].$payments_row['level_id'].$payments_row['term_id'].$payments_row['tranche'];
	$title=$payments_row['lastname'].", ".$payments_row['firstname']." - ".$payments_row['grade_name']." (".$payments_row['term_name']." Payment Ref. No. ".$refno.")";
	//echo $title."<br>";
	
	//look for payment record
	$post="select * from educaksyon.wp_posts where post_title='$title'";
	$post_result = $conn->query($post);
	if ($post_result->num_rows > 0) {
		//echo "payment found<br>";
		$paid="update payment_list set paid='Y', paid_date='$paid_date' where id=$payment_id";
		//echo $paid."<br>";
		$conn->query($paid);
		//send email start
		$wp = wp_get_current_user();
		$wp_id = $wp->ID;
		$wp_email = $wp->user_email;
		$wp_firstname = $wp->first_name;
		$wp_lastname = $wp->last_name;
		//get school settings
		$settings = "select * from school_settings";
		$settings_result=$conn->query($settings);
		$settings_row=$settings_result->fetch_assoc();
		$payment_received=$settings_row['payment_received'];
		$to = $settings_row['email'].",".$wp_email;
		$subject = 'Thank You for Your Payment';
		$body = "<p>Dear ".$payments_row['firstname']." ".$payments_row['lastname'].",
				<br><br>$payment_received<br><br>
				<table border='1'>
				<tr><td>Name</td><td>".$payments_row['lastname'].", ".$payments_row['firstname']."</td></tr>
				<tr><td>Grade Level</td><td>".$payments_row['grade_name']."</td></tr>
				<tr><td>Payment Schedule</td><td>".$payments_row['term_name']."</td></tr>
				<tr><td>Payment No.</td><td>".$payments_row['tranche']."</td></tr>
				<tr><td>Amount</td><td>".$payments_row['amount']."</td></tr>
				<tr><td>Paid Date</td><td>$paid_date</td></tr>
				</table>
				<br>
				<a href='http://".$settings_row['domain']."'>Login to your account to for more details.</a>
				<br><br><br>Thanks,<br><br>$contact<br>$position<br>$name</p>";
		$headers = array('Content-Type: text/html; charset=UTF-8');
		//echo $body;
		if (wp_mail( $to, $subject, $body, $headers )){
			header("Location: enrollment_list.php");
			//echo "Email sent";
		} else {
			echo "Something went wrong. Email not sent.";
		}
		//send email end
	} else {
		//echo "0 results";
	}
  }
} else {
  //echo "0 results";
  header("Location: enrollment_list.php");
}








echo 'Payment Successful!';


$conn->close();
?>