<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	$('#example tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' )
    } );
		editor = new $.fn.dataTable.Editor( {
		ajax: "tuition_matrix.php",
		table: "#example",
		fields: [ {
//				label: "ID:",
//				name: "tuition_matrix.id",
//			}, {
//				label: "Level:",
//				name: "tuition_matrix.level_id",
//			}, {
				label: "Level:",
				name: "tuition_matrix.level_id",
				type: "select",
				placeholder: "Select a grade level"
		 	}, {
				label: "Terms:",
				name: "tuition_matrix.term_id",
				type: "select",
				placeholder: "Select Terms"
			}, {
				label: "Tranche:",
				name: "tuition_matrix.tranche",
			}, {
				label: "Amount:",
				name: "tuition_matrix.amount",
			}, {
				label:"Date:",
				name: "tuition_matrix.date",
				type:"date"
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "tuition_matrix.php",
		columns: [
//			{ data: "tuition_matrix.id" },
//			{ data: "tuition_matrix.level_id" },
			{ data: "grade_levels.name" },
//			{ data: "tuition_matrix.term_id" },
			{ data: "payment_terms.name" },
			{ data: "tuition_matrix.tranche" },
			{ data: "tuition_matrix.amount" },
			{ data: "tuition_matrix.date" }
		],
		select: true,
		pageLength: 38,
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			{ extend: "remove", editor: editor }
		]
				
	} );
		
// Setup - add a text input to each footer cell
$('#example tfoot th').each( function () {
    var title = $('#example thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" size="8" />' );
} );
 
// DataTable
var table = $('#example').DataTable();
 
// Apply the filter
table.columns().every( function () {
    var column = this;
 
    $( 'input', this.footer() ).on( 'keyup change', function () {
        column
            .search( this.value )
            .draw();
    } );
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
						<th>Terms</th>
						<th>Tranche</th>
						<th>Amount</th>
						<th>Date</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Name</th>
						<th>Terms</th>
						<th>Tranche</th>
						<th>Amount</th>
						<th>Date</th>
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
