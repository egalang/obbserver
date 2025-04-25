<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

if(isset($_GET['cid']) and isset($_GET['pid'])){
	$course_id = $_GET['cid'];
	$period_id = $_GET['pid'];
	$sql = "SELECT * FROM lms_courses WHERE id = $course_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$course_name = $row['name'];
	$sql = "SELECT * FROM grading_periods WHERE id = $period_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$period_name = $row['name'];
}

?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: {
				url: "gradebook2.php",
				type: "POST",
				data: {
					id: <?php echo $course_id ?>
				}
			},
		table: "#example",
		fields: [ {
 				name:  "assignment_types.course_id",
				default: <?php echo $course_id; ?>,
				type: "hidden",
			}, {
				label: "Name:",
 				name:  "assignment_types.name",
			}, {
				label: "Weight:",
 				name:  "assignment_types.weight",
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: {
				url: "gradebook2.php",
				type: "POST",
				data: {
					id: <?php echo $course_id ?>
				}
			},
		columns: [
			{ data: "assignment_types.id" },
			{ data: null, render: function ( data, type, row, meta ) {
				return '<a href="gradebook3_list.php?cid=<?php echo $course_id; ?>&pid=<?php echo $period_id; ?>&tid='
						+ data.assignment_types.id 
						+ '">' + data.assignment_types.name + '</a>';
				}
			},
			{ data: "assignment_types.weight" }
		],
		select: true,
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			{ extend: "remove", editor: editor }
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
					<b>Activity Types</b>
				</span>
			</h1>
			<br>
			<div class="demo-html"></div>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Weight</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Weight</th>
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
