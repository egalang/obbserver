<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "sections.php",
		table: "#example",
		fields: [ {
 				label: "Name:",
 				name:  "sections.name",
			}, {
 				type:  "select",
				label: "Grade Level:",
 				name:  "sections.level_id",
				placeholder: "Select grade level",
				options: [
					<?php 
						$levels_query = "SELECT * FROM grade_levels";
						$levels_result = $conn->query($levels_query);
						if ($levels_result->num_rows > 0) {
							// output data of each row
							while($levels_row = $levels_result->fetch_assoc()) {
								echo '{label: "' . $levels_row['name'] . '", value: ' . $levels_row['id'] . '},';
							}
						}
					?>
					]
			}, {
 				label: "Adviser:",
 				name:  "sections.adviser_id",
				type: "select",
				placeholder: "Select adviser"
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "sections.php",
		columns: [
			{ data: "sections.name" },
			{ data: "grade_levels.name" },
			{ data: null, render: function ( data, type, row ) {
				return data.teachers.lastname + ', ' + data.teachers.firstname + ' ' + data.teachers.middlename;
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
						<th>Name</th>
						<th>Grade Level</th>
						<th>Adviser</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Name</th>
						<th>Grade Level</th>
						<th>Adviser</th>
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
