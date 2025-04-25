<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "files.php",
		table: "#example",
		fields: [ {
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

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "files.php",
		columns: [
			{ data: "enrollment_list.sy" },
    { data: null, render: function ( data, type, row ) {
        return data.enrollment_list.lastname + ', ' + data.enrollment_list.firstname + ' ' + data.enrollment_list.middlename;
        }
      },
			{ data: null, render: function ( data, type, row, meta ) {
				// var btns = '<a class="btn btn-success btn-xs" href="client_payment_list.php?id=' + data.enrollment_list.id + '">View Bills</a>';
				var btns = 'SY must be 2023-2024';
				if (data.enrollment_list.sy == "2023-2024") {
					btns = ' <a class="btn btn-warning btn-xs" href="reservation_form.php?id=' + data.enrollment_list.id + '">Get Data</a>';
				}
				return btns;
			}
		}
	],
    searching: false,
    select: true,
		pageLength: 38,
		buttons: [
			//{ extend: "create", editor: editor },
			//{ extend: "edit",   editor: editor },
			//{ extend: "remove", editor: editor },
			{
                text: 'Cancel',
                action: function ( dt ) {
                    window.history.go(-1); return false;
                }
            }
		]
	} );
} );








	</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<div class="demo-html"></div>
			<?php
			if(isset($_GET['message'])){
				if($_GET['message']==0){
					echo "<div class='alert alert-success'>";
					echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo "<strong>Success:</strong> Application updated.";
					echo "</div>";
				}
				if($_GET['message']==1){
					echo "<div class='alert alert-success'>";
					echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo "<strong>Success:</strong> Application created.";
					echo "</div>";
				}
				if($_GET['message']==2){
					echo "<div class='alert alert-danger'>";
					echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo "<strong>Attention:</strong> Student already enrolled.";
					echo "</div>";
				}
			}
			?>
			<p>Select a child record:</p>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>SY</th>
            			<th>Name</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>SY</th>
						<th>Name</th>
						<th>Actions</th>
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
