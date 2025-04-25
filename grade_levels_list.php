<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "grade_levels.php",
		table: "#example",
		fields: [ {
				label: "Level:",
				name: "grade_levels.name"
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "grade_levels.php",
		columns: [
			{ data: "grade_levels.id" },
			{ data: "grade_levels.name" }
		],
		select: true,
		pageLength: 38,
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
						<th>Level</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Level</th>
					</tr>
				</tfoot>
			</table>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
