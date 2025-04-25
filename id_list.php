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
			}, {				
				label: "Email:",
				name: "enrollment_list.email2",
			}, {				
				/* label: "Barcode:",
				name: "enrollment_list.barcode",
			}, { */
                label: "Images:",
                name: "files[].id",
                type: "uploadMany",
                display: function ( fileId, counter ) {
                    return '<img src="'+editor.file( 'files', fileId ).web_path+'"/>';
                },
                noFileText: 'No images'
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
			{ data: "sections.name", editField: "enrollment_list.section_id" },
			{ data: "enrollment_list.email2", editField: "enrollment_list.email2"  },
			{ data: "enrollment_list.barcode", editField: "enrollment_list.barcode" },
            { data: "files",
                render: function ( d ) {
                    return d.length ?
                    d.length+' image(s)' :
                    'No image';
                },
                title: "Image"
            },
            { data: null, render: function ( data, type, row, meta ) {
				return '<a class="btn btn-default btn-xs" href="id/front.php?id=' + data.enrollment_list.id + '" target="_blank">Front</a> ' +
                '<a class="btn btn-default btn-xs" href="id/back.php?id=' + data.enrollment_list.id + '" target="_blank">Back</a>';
				}
			}
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
						<th>Email</th>
						<th>Barcode</th>
						<th>Image</th>
						<th>Show ID</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Grade Level</th>
						<th>Section</th>
						<th>Email</th>
						<th>Barcode</th>
						<th>Image</th>
						<th>Show ID</th>
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
