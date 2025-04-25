<?php
include('enrollment_config.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$id=$_GET['id'];
$delete="select * from enrollment_list where id=$id";
$delete_result = $conn->query($delete);

if ($delete_result->num_rows > 0) {
  // output data of each row
  while($delete_row = $delete_result->fetch_assoc()) {
    //echo "id: " . $delete_row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    $update="update enrollment_list set deleted='Y' where id=$id";
    $conn->query($update);
  }
} else {
  echo "0 results";
}

$conn->close();
header("Location: enrollment_list.php?message=7");

?>
