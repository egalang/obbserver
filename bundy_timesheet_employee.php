<?php
include('../custom/header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

// employee details
$wp = wp_get_current_user();
$wp_id = $wp->ID;
$wp_email = $wp->user_email;
$wp_firstname = $wp->first_name;
$wp_lastname = $wp->last_name;

// today's date
date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d");

?>
var editor;

$(document).ready(function() {
    $('#example').DataTable();
} );

</script>
</head>
<body>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
		<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
		<thead>
		<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>&nbsp;</th></tr>
		</thead>
		<tbody>
		<?php

		$timesheet = "select * from employee_logs";
		$timesheet_result = $conn->query($timesheet);

		if ($timesheet_result->num_rows > 0) {
			// output data of each row
			while($timesheet_row = $timesheet_result->fetch_assoc()) {
				$employee = "select * from enrollment_list where wp_id=".$timesheet_row['userid'];
				$employee_result = $conn->query($employee);
				$employee_row = $employee_result->fetch_assoc();
				echo '<tr><td>'.$timesheet_row['userid'].'</td><td>'.$employee_row['wp_firstname'].
				     '</td><td>'.$employee_row['wp_lastname']."</td><td><a href='bundy_timesheet_employee.php?id=".
				     $timesheet_row['userid']."' class='btn btn-xs btn-default'>Open</a></td></tr>";
			}
		}

		?>
		</tbody>
		</table>
		</section>
	</div>
</body>
</body>
</html>
