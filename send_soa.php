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
$payment_id = $_GET['id'];
$soa = "select *,payment_list.comments,enrollment_list.id,enrollment_list.wp_email,enrollment_list.wp_firstname,enrollment_list.wp_lastname,enrollment_list.lastname,
        enrollment_list.firstname,enrollment_list.middlename,grade_levels.name as level_name,
        payment_terms.name as terms,payment_list.tranche,payment_list.amount,payment_list.due_date from payment_list
        left join enrollment_list on payment_list.enrollment_id = enrollment_list.id
        left join grade_levels on payment_list.level_id = grade_levels.id
        left join payment_terms on payment_list.term_id = payment_terms.id where payment_list.id=$payment_id";
$soa_result=$conn->query($soa);
$soa_row=$soa_result->fetch_assoc();

$wp_firstname=$soa_row['wp_firstname'];
$wp_lastname=$soa_row['wp_lastname'];
$lastname=$soa_row['lastname'];
$firstname=$soa_row['firstname'];
$middlename=$soa_row['middlename'];
$level=$soa_row['level_name'];
$terms=$soa_row['terms'];
$tranche=$soa_row['tranche'];
$amount=number_format($soa_row['amount'],2,".",",");
$due_date=$soa_row['due_date'];
$enrollee_id=$soa_row['id'];
$comments = $soa_row['comments'];
$settings = "select * from school_settings";
$settings_result=$conn->query($settings);
$settings_row=$settings_result->fetch_assoc();

$contact=$settings_row['contact'];
$position=$settings_row['position'];
$name=$settings_row['name'];
$soa_message=$settings_row['soa_message'];

$refno=$soa_row['id'].$soa_row['enrollment_id'].$soa_row['level_id'].$soa_row['term_id'].$soa_row['tranche'];
$to = $soa_row['wp_email'].",".$settings_row['email'];
$subject = 'Statement of Account (SOA Ref. No. '.$refno.')';

$body = "<p>Dear $wp_firstname $wp_lastname,
        <br><br>$soa_message<br><br>
        <table border='1'>
        <tr><td>Name</td><td>$lastname, $firstname $middlename</td></tr>
        <tr><td>Grade Level</td><td>$level</td></tr>
        <tr><td>Payment Info</td><td>$terms - $comments</td></tr>
        <tr><td>Payment No.</td><td>$tranche</td></tr>
        <tr><td>Amount</td><td>$amount</td></tr>
        <tr><td>Due Date</td><td>$due_date</td></tr>
        </table>
        <br>
        <a href='http://".$settings_row['domain']."'>Login to your account for more details.</a>
        <br><br><br>Thanks,<br><br>$contact<br>$position<br>$name</p>";

$headers = array('Content-Type: text/html; charset=UTF-8');

//echo $body;
if (wp_mail( $to, $subject, $body, $headers )){
  //echo "Email Sent.";
  $billed = "update payment_list set billed='Y' where id=$payment_id";
  if ($conn->query($billed) === TRUE) {
    header("Location: payment_list.php?id=$enrollee_id&message=2");
  } else {
    echo " Error updating record: " . $conn->error;
  }
} else {
  echo "Something went wrong. Email not sent.";
}
$conn->close();
?>
