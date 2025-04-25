<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

if(isset($_GET['cid']) and isset($_GET['pid']) and isset($_GET['tid']) and isset($_GET['aid'])){
	$course_id = $_GET['cid'];
	$period_id = $_GET['pid'];
	$type_id = $_GET['tid'];
	$assignment_id = $_GET['aid'];
	$sql = "SELECT * FROM lms_courses WHERE id = $course_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$course_name = $row['name'];
	$sql = "SELECT * FROM grading_periods WHERE id = $period_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$period_name = $row['name'];
	$sql = "SELECT * FROM assignment_types WHERE id = $type_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$type_name = $row['name'];
	$sql = "SELECT * FROM assignments WHERE id = $assignment_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$assignment_name = $row['name'];

	//pre-populate gradebook table
	$sql = "SELECT * FROM lms_enrollees WHERE course_id = $course_id";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$student_id = $row['user_id'];
			$add_sql = "INSERT INTO gradebook ( assignment_id, student_id ) VALUES ( $assignment_id, $student_id )";
			$add_result = $conn->query($add_sql);
		}
	}
}

?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: {
				url: "gradebook4.php",
				type: "POST",
				data: {
					id: <?php echo $assignment_id ?>
				}
			},
		table: "#example",
		fields: [ {
				label: "Grade:",
 				name:  "gradebook.grade",
			}
		]
	} );

    // Activate an inline edit on click of a table cell
    $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this, {
            onBlur: 'submit'
        } );
    } );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: {
				url: "gradebook4.php",
				type: "POST",
				data: {
					id: <?php echo $assignment_id ?>
				}
			},
		columns: [
			{ data: "gradebook.id" },
            { data: null, render: function ( data, type, row ) {
                // Combine the first and last names into a single table field
                return data.enrollment_list.lastname+', '+data.enrollment_list.firstname+' '+data.enrollment_list.middlename;
            } },
			{ data: "gradebook.grade" }
		],
		select: true,
		buttons: [
			//{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			//{ extend: "remove", editor: editor },
            <?php
				$sql = "SELECT * FROM assignments WHERE id = $assignment_id";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$lms_id = $row['lms_id'];
					if ( $lms_id != null ) {
						echo "{
							text: 'Get Grades from LMS',
							action: function ( dt ) {
								window.open('gradebook4.1.php?cid=$course_id&pid=$period_id&tid=$type_id&aid=$assignment_id&lms_id=$lms_id', '_self');
							}
						}";
					}
				}
			?>
		]
	} );
} );








	</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<!-- <h1><span>You're logged in as <?php echo $current_user_name; ?></span></h1> -->
			<h1>
				<span>
					<a href = "gradebook0_list.php"><?php echo $course_name; ?></a> |
					<a href = "gradebook1_list.php?id=<?php echo $course_id; ?>"><?php echo $period_name; ?></a> |
					<a href = "gradebook2_list.php?cid=<?php echo $course_id; ?>&pid=<?php echo $period_id; ?>"><?php echo $type_name; ?></a> |
					<a href = "gradebook3_list.php?cid=<?php echo $course_id; ?>&pid=<?php echo $period_id; ?>&tid=<?php echo $type_id; ?>"><?php echo $assignment_name; ?></a> |
					<b>Gradebook</b>
				</span>
			</h1>
			<br>
			<div class="demo-html"></div>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Student</th>
						<th>Score</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Student</th>
						<th>Score</th>
					</tr>
				</tfoot>
			</table>
			<!--
			<a href="enrollment_list.php" class="btn btn-default" role="button">Return to Enrollment List</a>
			-->
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
