<?php
include('../../moodledata/enrollment_config.php');
$conn = new mysqli($servername, $username, $password, "editor");
$conn2 = new mysqli($servername, $username, $password, "wordpress");
$today=date("Y-m-d");
$payments= "select payment_list.id, enrollment_list.id as eid, payment_list.amount, enrollment_list.firstname,
            enrollment_list.lastname, payment_terms.name as pterm, enrollment_list.balance,
            grade_levels.name as glevel, payment_list.tranche from payment_list
            left join enrollment_list on payment_list.enrollment_id=enrollment_list.id
            left join grade_levels on payment_list.level_id=grade_levels.id
            left join payment_terms on payment_list.term_id=payment_terms.id
            where payment_list.paid='N'";
$payments_result = $conn->query($payments);
if ($payments_result->num_rows > 0) {
  while($payments_row = $payments_result->fetch_assoc()) {
    $post="select * from wp_posts where post_content like '%".$payments_row['amount'].'%'.$payments_row['lastname'].', '.$payments_row['firstname'].' - '.$payments_row['glevel'].' ('.$payments_row['pterm'].' Payment No. '.$payments_row['tranche'].")%'";
    //echo $post.'<br>';
    $post_result = $conn2->query($post);
    if ($post_result->num_rows > 0) {
      $post_row = $post_result->fetch_assoc();
      //echo $post_row['post_content'];
      $id=$payments_row['id'];
      $paid="update payment_list set paid_date='$today', paid='Y' where id=$id";
      if ($conn->query($paid) === TRUE) {
        //set new balance
        $new_balance = $payments_row['balance'] - $payments_row['amount'];
        $enrollee_id = $payments_row['eid'];
        $balance="update enrollment_list set balance=$new_balance where id=$enrollee_id";
        if ($conn->query($balance) === TRUE) {
          //header("Location: enrollment_list.php");
          //echo "Payment record updated.";
        } else {
          //echo " Error updating record: " . $conn->error;
        }
      } else {
        //echo " Error updating record: " . $conn->error;
      }
    }

  }
}


// $data="select post_content from wp_posts where post_type='wp_paypal_order' and post_status='publish'";
// $data_result = $conn->query($data);
// if ($data_result->num_rows > 0) {
//   // output data of each row
//   while($data_row = $data_result->fetch_assoc()) {
//     $string=$data_row['post_content'];
//     $string=strstr( $string, '[item_name1]' );
//     $strpos=strpos( $string, '[receiver_email]' );
//     $string=substr($string,0,$strpos);
//     echo $string.'<br>';
//   }
// }

header("Location: enrollment_list.php");
$conn->close();
?>
