<?php
include('header.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  //echo "Connected to editor database. <br>";
}

//get URL variables
if (isset($_GET['first'])) {
  $first = $_GET['first'];
} else {
  $first = 2;
}
if (isset($_GET['last'])) {
  $last = $_GET['last'] * -1;
} else {
  $last = -2;
}

?>
var editor;

$(document).ready(function() {
   $('#example').DataTable();
} );

</script>
</head>
<body class="dt-example dt-example-bootstrap">
<div class='container'>
<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
  <thead>
    <tr><th>ID</th><th>Name</th><th>Result</th></tr>
  </thead><tbody>
<?php
// start sync students to sis and lms
$students = "select * from enrollment_list where accepted='Y' and deleted='N' and sy='2024-2025'";
$students_result = $conn->query($students);

if ($students_result->num_rows > 0) {
  // output data of each row
  while($students_row = $students_result->fetch_assoc()) {
    $student_name = $students_row["lastname"].', '.$students_row["firstname"];
    //$userID=str_replace( ' ','',strtolower(  substr( $students_row["firstname"],0,$first ) . substr( trim( $students_row["firstname"] ),$last ) . $students_row["lastname"] ) );
    $userID=str_replace( ' ','',strtolower( $students_row["lastname"] . $students_row["id"] ) );
    $student_id = $students_row['id'];
    //ADD STUDENTS LMS START
    //start insert data to moodle mdl_user table
    $password='$2y$10$X/oIOnXPDHsQYUzNmwasXurxru79ZPQZrvLiJaucsXKj12jpdrbou';
    $user="INSERT INTO ljcsi_lms.mdl_user (`auth`, `confirmed`, `policyagreed`, `deleted`, `suspended`, `mnethostid`, `username`, `password`, `idnumber`, `firstname`, `lastname`, `email`, `emailstop`, `phone1`, `phone2`, `institution`, `department`, `address`, `city`, `country`, `lang`, `calendartype`, `theme`, `timezone`, `firstaccess`, `lastaccess`, `lastlogin`, `currentlogin`, `lastip`, `secret`, `picture`, `description`, `descriptionformat`, `mailformat`, `maildigest`, `maildisplay`, `autosubscribe`, `trackforums`, `timecreated`, `timemodified`, `trustbitmask`, `imagealt`, `lastnamephonetic`, `firstnamephonetic`, `middlename`, `alternatename`, `moodlenetprofile`) VALUES
    ('manual', 1, 0, 0, 0, 1, '$userID', '$password', '$student_id', '".$students_row["firstname"]."', '".$students_row["lastname"]."', '".$students_row['wp_email']."', 0, '', '', '', '', '', '', '', 'en', 'gregorian', '', '99', 0, 0, 0, 0, '', '', 0, '', 1, 1, 0, 2, 1, 0, 1625839648, 1626423104, 0, '', '', '', '', '', '')";
    //('manual', 1, 0, 0, 0, 1, '$userID', '$password', '$student_id', '".$students_row["firstname"]."', '".$students_row["lastname"]."', '".$student_row['wp_email']."', 0, '', '', '', '', '', '', '', 'en', 'gregorian', '', '99', 0, 0, 0, 0, '', '', 0, 'This user is a special user that allows read-only access to some courses.', 1, 1, 0, 2, 1, 0, 0, 1624796472, 0, NULL, NULL, NULL, NULL, NULL, NULL);";
    //$conn->query($user);
    echo "<tr>";
    if ($conn->query($user) === TRUE) {
      $studentID = "select * from ljcsi_lms.mdl_user where idnumber = $student_id";
      $studentID_result = $conn->query($studentID);
      $studentID_row = $studentID_result->fetch_assoc();
      $preference = "insert into ljcsi_lms.mdl_user_preferences (userid,name,value) values (".$studentID_row['id'].",'auth_forcepasswordchange','1')";
      $conn->query($preference);
      echo "<td>$student_id</td><td>$student_name</td><td>New record created successfully</td>";
    } else {
      echo "<td>$student_id</td><td>$student_name</td><td>Error: " . $sql . $conn->error . "</td>";
    }
    echo "</tr>";
    //end insert data to moodle mdl_user table
    //ADD STUDENTS LMS END
  }
} else {
  //echo "0 results";
}
$conn->close();
//header("Location: sync_students_list.php?message=1");
?>
</tbody></table>
<p><a href='sync_students_list.php?first=<?php echo $first; ?>&last=<?php echo abs($last); ?>' class='btn btn-default'>Return to List</a></p>
</div>
</body>
</html>