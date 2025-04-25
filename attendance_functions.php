<?php
include('../wp-load.php');


function send_email($barcode,$logtype) {
    date_default_timezone_set("Asia/Manila");
    $barcode_student = get_barcode_student($barcode);
    var_dump($barcode_student);
    //$to = $barcode_student['email2'];
    $to = $barcode_student['wp_email'];
    $subject = "Attendance Notification for ".$barcode_student['firstname']." ".$barcode_student['middlename']." ".$barcode_student['lastname'];
    if ( $logtype == 'SI') {
        $body = "Hi,<br><br>".
        "This is to inform you that ".$barcode_student['firstname'].
        " has entered our school premises on ".date("Y-m-d")." at ".date("h:i:sa").
        ".<br><br>Thanks and best regards.<br>";
    } else {
        $body = "Hi,<br><br>".
        "This is to inform you that ".$barcode_student['firstname'].
        " has left our school premises on ".date("Y-m-d")." at ".date("h:i:sa").
        ".<br><br>Thanks and best regards.<br>";
    }
    $headers = array('Content-Type: text/html; charset=UTF-8');
    if (wp_mail( $to, $subject, $body, $headers )){
        $result = "Email sent.";
    } else {
        $result = "Email not sent.";
      }      
    return $result;
}

function get_barcode_student($barcode) {
    include 'enrollment_config.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM enrollment_list WHERE barcode = '$barcode'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    return $row;
}

function check_email_sent($barcode,$logtype) {
    
}
?>