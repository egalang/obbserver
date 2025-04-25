<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

$teacher_query = "select * from teachers where wp_id = $current_user_id";
$teacher_result = $conn->query($teacher_query);
$teacher_row = $teacher_result->fetch_assoc();
$id = $teacher_row['id'];


?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: {
				url: "gradebook0.php",
				type: "POST",
				data: {
					id: <?php echo $id ?>
				}
			},
		table: "#example",
		fields: [ {
 				label: "Code:",
 				name:  "lms_courses.code",
			}, {
 				label: "Name:",
 				name:  "lms_courses.name",
			}, {
 				label: "Grade Level:",
 				name:  "lms_courses.level_id",
				type: "select",
				placeholder: "Select grade level"
			}, {
 				label: "Section:",
 				name:  "lms_courses.section_id",
				type: "select",
				placeholder: "Select section"
			}, {
 				label: "Teacher:",
 				name:  "lms_courses.teacher_id",
				type: "select",
				placeholder: "Select teacher"
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: {
				url: "gradebook0.php",
				type: "POST",
				data: {
					id: <?php echo $id ?>
				}
			},
		columns: [
			{ data: "lms_courses.id" },
			{ data: null, render: function ( data, type, row, meta ) {
				return '<a href="gradebook1_list.php?id=' 
						+ data.lms_courses.id 
						+ '">' + data.lms_courses.name + '</a>';
				}
			},
			{ data: "lms_courses.code" },
			//{ data: "lms_courses.name" },
			{ data: "grade_levels.name" },
			{ data: "sections.name" }
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
			<h1><span><b>Courses</b><span></h1>
			<br>
			<div class="demo-html"></div>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Code</th>
						<th>Grade Level</th>
						<th>Section</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Code</th>
						<th>Grade Level</th>
						<th>Section</th>
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
