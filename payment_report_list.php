<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
$id = $_GET['id'];
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "payment_report.php",
		//ajax: {
		//		url: "payment_report.php",
		//		type: "POST",
		//		data: {
		//			id: <?php echo $id ?>
		//		}
		//	},
		table: "#example",
		fields: [ {
                label: "Name:",
                name: "payment_list.enrollment_id",
                type: "select",
                placeholder: "Select a student"
			}, {
                label: "Level:",
                name: "payment_list.level_id",
                type: "select",
                placeholder: "Select a lavel"
			}, {
                label: "Term:",
                name: "payment_list.term_id",
                type: "select",
                placeholder: "Select a term"
			}, {
				label: "Tranche:",
				name: "payment_list.tranche"
			}, {
				label: "Due on:",
				name: "payment_list.due_date",
				type:   'datetime',
				def:    function () { return new Date(); },
				format: 'YYYY-MM-DD'
			}, {
				label: "Amount:",
				name: "payment_list.amount"
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "payment_report.php",
		//ajax: {
		//		url: "payment_report.php",
		//		type: "POST",
		//		data: {
		//			id: <?php echo $id ?>
		//		}
		//	},
		columns: [
			//{ data: "payment_list.id" },
            { data: null, render: function ( data, type, row ) {
                // Combine the first and last names into a single table field
                return data.enrollment_list.lastname+', '+data.enrollment_list.firstname+' '+data.enrollment_list.middlename;
            } },
			{ data: "payment_list.amount" },
			{ data: "payment_list.due_date" },
			{ data: "payment_list.paid_date" },
			{ data: "payment_list.ecom" },
			{ data: "payment_list.billed" },
			{ data: "payment_list.paid" },
			{ data: "grade_levels.name" },
			{ data: "payment_terms.name" },
			{ data: "payment_list.tranche" }
		],
		select: true,
		pageLength: 38,
		colReorder: true,
        buttons: [
            //{ extend: 'create', editor: editor },
            //{ extend: 'edit',   editor: editor },
            //{ extend: 'remove', editor: editor },
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }
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
			<?php
			if(isset($_GET['message'])){
				if($_GET['message']==0){
					echo "<div class='alert alert-warning'>";
					echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo "<strong>Notice:</strong> This entry is already paid.";
					echo "</div>";
				}
				if($_GET['message']==1){
					echo "<div class='alert alert-success'>";
					echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo "<strong>Success!</strong> This entry is set to paid.";
					echo "</div>";
				}
				if($_GET['message']==2){
					echo "<div class='alert alert-success'>";
					echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo "<strong>Success!</strong> SOA email sent.";
					echo "</div>";
				}
			}
			?>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>Name</th>
						<th>Amount</th>
						<th>Due Date</th>
						<th>Date Paid</th>
						<th>e-Com</th>
						<th>Billed</th>
						<th>Paid</th>
						<th>Level</th>
						<th>Term</th>
						<th>Tranche</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Name</th>
						<th>Amount</th>
						<th>Due Date</th>
						<th>Date Paid</th>
						<th>e-Com</th>
						<th>Billed</th>
						<th>Paid</th>
						<th>Level</th>
						<th>Term</th>
						<th>Tranche</th>
					</tr>
				</tfoot>
			</table>
			<!--<a href="enrollment_list.php" class="btn btn-default" role="button">Return to Enrollment List</a>-->
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
