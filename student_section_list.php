<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
?>

var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "student_section.php",
		table: "#example",
		fields: [ {				
				label: "Section:",
				name: "enrollment_list.section_id",
				type: "select",
				placeholder: "Select section"
			}
		]
	} );

    // Activate an inline edit on click of a table cell
    $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this, {
            onBlur: 'submit'
        } );
    } );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "student_section.php",
		columns: [
			{ data: "enrollment_list.id" },
            { data: null, render: function ( data, type, row ) {
                // Combine the first and last names into a single table field
                return data.enrollment_list.lastname+', '+data.enrollment_list.firstname+' '+data.enrollment_list.middlename;
            } },
			{ data: "grade_levels.name" },
			{ data: "sections.name", editField: "enrollment_list.section_id" }
		],
		select: true,
		buttons: [
			//{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			//{ extend: "remove", editor: editor }
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
						<th>Name</th>
						<th>Grade Level</th>
						<th>Section</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Grade Level</th>
						<th>Section</th>
					</tr>
				</tfoot>
			</table>
			<p><a href='student_add_section.php' class='btn btn-default'>Auto Add Sections</a></p>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
