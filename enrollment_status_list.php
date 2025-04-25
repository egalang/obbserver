<?php
include('header.php');
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "enrollment_status.php",
		table: "#example",
		fields: [ {
				label: "Status Name:",
				name: "enrollment_status.name"
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "enrollment_status.php",
		columns: [
			{ data: "enrollment_status.id" },
			{ data: "enrollment_status.name" }
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
						<th>Enrollment Status</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Enrollment Status</th>
					</tr>
				</tfoot>
			</table>
			<a href="enrollment_list.php" class="btn btn-default" role="button">Return to Enrollment List</a>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
