<?php
include('header.php');

if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$wp = wp_get_current_user();
$wp_id = $wp->ID;
$wp_email = $wp->user_email;
$wp_firstname = $wp->first_name;
$wp_lastname = $wp->last_name;

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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

?>
var editor;

$(document).ready(function() {
   $('#example').DataTable(
	{
		dom: "Bfrtip",
		buttons: [
			{
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }
			]
	}
   );
} );


</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<?php
			if($_GET['message']==1){
				echo "<div class='alert alert-success'>";
				echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "<strong>Success!</strong> Students were successfully synchronized.";
				echo "</div>";
			}
			?>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
			<thead>
			<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Level</th><th>UserID</th><th>Email</th><th>Moodle</th><th>Action</th></tr>
			</thead>
			<tbody>
			<?php
			// start sync students to sis and lms
			$students = "select enrollment_list.id,enrollment_list.wp_email,enrollment_list.firstname,".
				    "enrollment_list.lastname,enrollment_list.middlename,grade_levels.name from enrollment_list ".
				    "left join grade_levels on grade_levels.id=enrollment_list.level where accepted='Y' and deleted='N' ".
					"and sy='2024-2025'";
			$students_result = $conn->query($students);

			if ($students_result->num_rows > 0) {
			  // output data of each row
			  while($students_row = $students_result->fetch_assoc()) {
				//$userID=str_replace( ' ','',strtolower( substr( $students_row["firstname"],0,2 ) . substr( $students_row["firstname"],-2 ) . $students_row["lastname"] ) );
				//$userID=str_replace( ' ','',strtolower( substr( $students_row["firstname"],0,1 ) . substr( $students_row["firstname"],-1 ) . $students_row["lastname"] ) );
				//$userID=str_replace( ' ','',strtolower( substr( $students_row["firstname"],0,1 ) . substr( trim( $students_row["firstname"] ),-1 ) . $students_row["lastname"] ) );
				//$userID=str_replace( ' ','',strtolower( substr( $students_row["firstname"],0,$first ) . substr( trim( $students_row["firstname"] ),$last ) . $students_row["lastname"] ) );
				$userID=str_replace( ' ','',strtolower( $students_row["lastname"] . $students_row["id"] ) );
				echo "<tr><td>" . $students_row["id"]. "</td><td>" . $students_row["firstname"] .
				     "</td><td>" . $students_row["lastname"] . "</td><td>" . $students_row["name"] .
				     "</td><td>$userID</td><td>" . $students_row["wp_email"] . "</td>";
				//check if the student already exists
				//$exists = "select * from $dbname_sis.students where last_name='".$students_row['lastname']."' and first_name='".$students_row['firstname'].
				//          "' and middle_name='".$students_row['middlename']."' and name_suffix='".$students_row['extname']."'";
				//$exists="select * from $dbname_sis.login_authentication where username='$userID'";
				// $exists="select * from $dbname_sis.students where alt_id='".$students_row['id']."'";
				// $exists_result = $conn->query($exists);
				// if ($exists_result->num_rows > 0) {
				//   $exists_row = $exists_result->fetch_assoc();
				//   echo "<td>Synced</td>";
				// } else {
				//   $exists_row['student_id']='none';
				//   echo "<td>Ready</td>";
				// }
				$existsLMS="select * from ljcsi_lms.mdl_user where idnumber='".$students_row["id"]."'";
				$existsLMS_result = $conn->query($existsLMS);
				if ($existsLMS_result->num_rows > 0) {
				  echo "<td>Synced</td><td><a href='reset_password.php?id=$userID' class='btn btn-danger'>Reset Password</a></td></tr>";
				} else {
				  echo "<td>Ready</td><td>&nbsp;</td></tr>";
				}
			  }
			}
			?>
			</tbody></table>
			<p><a href='sync_students.php?first=<?php echo $first; ?>&last=<?php echo abs($last); ?>' class='btn btn-default'>Synch Students Now</a></p>
		</section>
	</div>
</body>
</html>
<?php $conn->close(); ?>
