<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "grade_scales.php",
		table: "#example",
		fields: [ {
				label: "Initial Grade:",
 				name:  "scale",
			}, {
				label: "Transmuted Grade:",
 				name:  "grade",
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "grade_scales.php",
		columns: [
			{ data: "id" },
			{ data: "scale" },
			{ data: "grade" }
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
						<th>Initial Grade</th>
						<th>Transmuted Grade</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Initial Grade</th>
						<th>Transmuted Grade</th>
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
