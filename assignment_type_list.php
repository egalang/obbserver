<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "assignment_types.php",
		table: "#example",
		fields: [ {
				label: "Course:",
 				name:  "assignment_types.course_id",
				type: "select",
				placeholder: "Select course"
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
		ajax: "assignment_types.php",
		columns: [
			{ data: "assignment_types.id" },
			{ data: "lms_courses.code" },
			{ data: "lms_courses.name" },
			{ data: "assignment_types.name" },
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
			<div class="demo-html"></div>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Code</th>
						<th>Course</th>
						<th>Name</th>
						<th>Weight</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Code</th>
						<th>Course</th>
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
