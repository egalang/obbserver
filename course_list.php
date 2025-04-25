<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "courses.php",
		table: "#example",
		fields: [ {
				label: "Code:",
 				name:  "lms_courses.code",
			}, {
				label: "Sort Order:",
 				name:  "lms_courses.sort_order",
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
		ajax: "courses.php",
		columns: [
			{ data: "lms_courses.sort_order" },
			{ data: "lms_courses.id" },
			{ data: "lms_courses.code" },
			{ data: "lms_courses.name" },
			{ data: "grade_levels.name" },
			{ data: "sections.name" },
			{ data: null, render: function ( data, type, row ) {
				return data.teachers.lastname + ', ' + data.teachers.firstname + ' ' + data.teachers.middlename;
				}
			},
			{ data: null, render: function ( data, type, row, meta ) {
				return '<a class="btn btn-info btn-xs" href="course_student_list.php?id=' + data.lms_courses.id + '&sec=' + data.lms_courses.section_id + '">Show Students</a>';
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
					<th>Sort Order</th>
						<th>ID</th>
						<th>Code</th>
						<th>Name</th>
						<th>Grade Level</th>
						<th>Section</th>
						<th>Teacher</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
					<th>Sort Order</th>
						<th>ID</th>
						<th>Code</th>
						<th>Name</th>
						<th>Grade Level</th>
						<th>Section</th>
						<th>Teacher</th>
						<th>Actions</th>
					</tr>
				</tfoot>
			</table>
			<p><a href='course_add_sections.php' class='btn btn-default'>Auto Add Students</a></p>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
