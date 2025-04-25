<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
$id = $_GET['id'];

$payment = "select * from enrollment_list where id=$id";
$payment_result=$conn->query($payment);
$payment_row=$payment_result->fetch_assoc();
$name = $payment_row['lastname'].", ".$payment_row['firstname']." ".$payment_row['middlename'];

//get school settings
$settings = "select * from school_settings";
$settings_result=$conn->query($settings);
$settings_row=$settings_result->fetch_assoc();
if(!isset($_GET["sy"])){
$school_year = $settings_row['sy'];
} else {
	$school_year = $_GET["sy"];
}

?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		//ajax: "payments.php",
		ajax: {
				url: "payments.php",
				type: "POST",
				data: {
					id: <?php echo $id ?>
				}
			},
		table: "#example",
		fields: [ {
 				type:  "readonly",
 				label: "Name:",
 				name:  "counter1",
 				def:   "<?php echo $name ?>"
			}, {
				type:    "hidden",
				name:    "payment_list.enrollment_id",
				default: "<?php echo $id ?>"
			}, {
				type:    "hidden",
				name:    "payment_list.level_id",
				default: "<?php echo $payment_row['level'] ?>"
			}, {
				type:    "hidden",
				name:    "payment_list.sy",
				default: "<?php echo $school_year; ?>"
			}, {
				label:   "Term",
				type:    "select",
				name:    "payment_list.term_id",
				placeholder: "Select payment term",
				options: [
					<?php 
						$levels_query = "SELECT * FROM payment_terms";
						$levels_result = $conn->query($levels_query);
						if ($levels_result->num_rows > 0) {
							// output data of each row
							while($levels_row = $levels_result->fetch_assoc()) {
								echo '{label: "' . $levels_row['name'] . '", value: ' . $levels_row['id'] . '},';
							}
						}
					?>
					//{label: "Others", value: 0},
					]
				//default: "<?php echo $payment_row['terms'] ?>"
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
			}, {
				type: "textarea",
				label: "Comment:",
				name: "payment_list.comments"
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		//ajax: "payments.php",
		ajax: {
				url: "payments.php",
				type: "POST",
				data: {
					id: <?php echo $id ?>
				}
			},
		columns: [
			{ data: "payment_list.id" },
            { data: null, render: function ( data, type, row ) {
                // Combine the first and last names into a single table field
                return data.enrollment_list.lastname+', '+data.enrollment_list.firstname+' '+data.enrollment_list.middlename;
            } },
			{ data: "grade_levels.name" },
			{ data: "payment_terms.name" },
			{ data: "payment_list.tranche" },
			{ data: "payment_list.due_date" },
			{ data: "payment_list.paid_date" },
			{ data: "payment_list.amount" },
			{ data: "payment_list.billed" },
			{ data: "payment_list.paid" },
			{ data: "payment_list.ecom" },
			{ data: "payment_list.comments" },
			{ data: null, render: function ( data, type, row, meta ) {
				return  '<a class="btn btn-info btn-xs" href="soa.php?id=' + data.payment_list.id + '">View SOA</a> ' +
								'<a class="btn btn-success btn-xs" href="paid.php?id=' + data.payment_list.id + '">Mark as Paid</a>';
				}
			}
		],
		order: [[5,"asc"]],
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
						<th>ID</th>
						<th>Name</th>
						<th>Level</th>
						<th>Term</th>
						<th>Tranche</th>
						<th>Due Date</th>
						<th>Date Paid</th>
						<th>Amount</th>
						<th>Billed</th>
						<th>Paid</th>
						<th>eCom</th>
						<th>Comments</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Level</th>
						<th>Term</th>
						<th>Tranche</th>
						<th>Due Date</th>
						<th>Date Paid</th>
						<th>Amount</th>
						<th>Billed</th>
						<th>Paid</th>
						<th>eCom</th>
						<th>Comments</th>
						<th>Actions</th>
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
