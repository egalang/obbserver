<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

if(isset($_GET['id'])){
	$course_id = $_GET['id'];
	$sql = "SELECT * FROM lms_courses WHERE id = $course_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$course_name = $row['name'];
}

?>

var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "grading_periods.php",
		table: "#example",
		fields: [ {
				label: "Code:",
 				name:  "code",
			}, {
				label: "Name:",
 				name:  "name",
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "grading_periods.php",
		columns: [
			{ data: "id" },
			{ data: null, render: function ( data, type, row, meta ) {
				return '<a href="gradebook2_list.php?cid=<?php echo $course_id; ?>&pid='
						+ data.id 
						+ '">' + data.name + '</a>';
				}
			},
			{ data: "code" }
		],
		select: true,
		buttons: [
			//{ extend: "create", editor: editor },
			//{ extend: "edit",   editor: editor },
			//{ extend: "remove", editor: editor }
		]
	} );
} );

	</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<!-- <h1><span>You're logged in as <?php echo $current_user_name; ?></span></h1> -->
			<h1><span><a href = "gradebook0_list.php"><?php echo $course_name; ?></a> | <b>Grading Periods</b></span></h1>
			<br>
			<div class="demo-html"></div>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Code</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Code</th>
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
