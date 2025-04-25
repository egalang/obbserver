<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$id = $_GET['id'];
$sec = $_GET['sec'];

$course_query = "SELECT * FROM lms_courses WHERE id = $id";
$course_result = $conn->query($course_query);
$course_row = $course_result->fetch_assoc();

?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: {
				url: "course_students.php",
				type: "POST",
				data: {
					id: <?php echo $id ?>,
				}
			},
		table: "#example",
		fields: [ {
				type: "readonly",
				label: "Course:",
				name: "counter1",
				default: "<?php echo $course_row['name'] ?>",
			}, {
				type: "hidden",
				name: "lms_enrollees.course_id",
				default: "<?php echo $id ?>",
			}, {
				label: "Enrollee:",
				name: "lms_enrollees.user_id",
				type: "select",
				placeholder: "Select enrollee",
				options: [
					<?php 
						$section_query = "SELECT * FROM enrollment_list WHERE accepted = 'Y' AND deleted = 'N' AND section_id = $sec AND sy = '2023-2024' ORDER BY lastname ASC";
						$section_result = $conn->query($section_query);
						if ($section_result->num_rows > 0) {
							// output data of each row
							while($section_row = $section_result->fetch_assoc()) {
								echo '{label: "' . $section_row['lastname'] . ', ' . $section_row['firstname'] . '", value: ' . $section_row['id'] . '},';
							}
						}
					?>
					]
			}, {
				label: "Role:",
				name: "lms_enrollees.role_id",
				type: "select",
				placeholder: "Select role",
				options: [
					{label:'Student',value:'1'},
					{label:'Teacher',value:'2'},
				],
				default: "1"
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: {
				url: "course_students.php",
				type: "POST",
				data: {
					id: <?php echo $id ?>
				}
			},
		columns: [
			{ data: "lms_enrollees.id" },
            { data: null, render: function ( data, type, row ) {
                // Combine the first and last names into a single table field
                return data.enrollment_list.lastname+', '+data.enrollment_list.firstname+' '+data.enrollment_list.middlename;
            	} 
			},
			{ data: "lms_courses.name" },
			{
				data: "lms_enrollees.role_id",
				render: function (val,type,row) {
					return val == 1 ? "Student" : "Teacher";
				}
			}
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
						<th>Enrollee</th>
						<th>Course</th>
						<th>Role</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Enrollee</th>
						<th>Course</th>
						<th>Role</th>
					</tr>
				</tfoot>
			</table>
			<a href="course_list.php" class="btn btn-default" role="button">Return to Course List</a>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
