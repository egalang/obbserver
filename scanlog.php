<?php
include 'enrollment_config.php';
include 'attendance_functions.php';

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if( (isset($_GET['barcode'])) && ($_GET['barcode']!='-1') ){
//if( isset($_GET['barcode']) ){
    $barcode = $_GET['barcode'];
    $logtype = $_GET['logtype'];
    $wpID = $_GET['wpID'];
    $date = date("Y-m-d");
    // check for duplicate
    $duplicate = "SELECT * FROM scanlog WHERE logtype = '$logtype' AND barcode = '$barcode' AND date LIKE '$date%'";
    $duplicate_result = $conn->query($duplicate);
    if ($duplicate_result->num_rows > 0) {
        echo "Record already exists.<br>";
    } else {
        $sql = "INSERT INTO scanlog (logtype,barcode,wp_id) VALUES ('$logtype','$barcode','$wpID')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully.<br>";
            $emailed = "INSERT INTO sent_attendance_emails (logtype,barcode,`date`) VALUES ('$logtype','$barcode','$date')";
            if ($conn->query($emailed) === TRUE) {
                echo "New record created successfully.<br>";
            if ( ($logtype=="SI") || ($logtype=="SO") ) {
                $email_result = send_email($barcode,$logtype);
                echo $email_result."<br>";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
} else {
    exit('no data');
}
?>
