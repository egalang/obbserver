<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$balance="update enrollment_list set balance=0";
$balance_result = $conn->query($balance);

$balance="SELECT enrollment_id,sum(amount) totalamount from payment_list where paid='N' group by enrollment_id order by enrollment_id";
$balance_result = $conn->query($balance);

if ($balance_result->num_rows > 0) {
  // output data of each row
  while($balance_row = $balance_result->fetch_assoc()) {
	$enrollee_id=$balance_row['enrollment_id'];
	$new_balance=$balance_row['totalamount'];
    //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
	$enrollee="update enrollment_list set balance=$new_balance where id=$enrollee_id";
	$conn->query($enrollee);
  }
} else {
  //echo "0 results";
}

?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "student_master.php",
		table: "#example",
		fields: [ {
	        label: "Images:",
	        name: "files[].id",
	        type: "uploadMany",
	        display: function ( fileId, counter ) {
	            return '<img src="'+editor.file( 'files', fileId ).web_path+'"/>';
	        },
	        noFileText: 'No images'
			}, {
		type: "textarea",
                label: "Comments:",
                name: "enrollment_list.comments"
            }
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "student_master.php",
		columns: [
			{ data: "enrollment_list.sy" },
			{ data: null, render: function ( data, type, row ) {
				return data.enrollment_list.lastname + ', ' + data.enrollment_list.firstname + ' ' + data.enrollment_list.middlename;
				}
			},
			{ data: "grade_levels.name" },
			{ data: "enrollment_list.lrn" },
			{ data: "enrollment_list.psa" },
			{ data: "enrollment_list.birth" },
			{ data: "enrollment_list.age" },
			{ data: "enrollment_list.sex" },
			{ data: "enrollment_list.mt" },
			{ data: "enrollment_list.add1" },
			{ data: "enrollment_list.add2" },
			{ data: "enrollment_list.add3" },
			{ data: "enrollment_list.zip" },
			{ data: "enrollment_list.father" },
			{ data: "enrollment_list.mother" },
			{ data: "enrollment_list.guardian" },
			{ data: "enrollment_list.wp_email" },
			{ data: "enrollment_list.phone1" },
			{ data: "enrollment_list.phone2" },
			{ data: "enrollment_list.phone3" },
			{ data: "enrollment_list.comments" }
		],
		select: true,
		pageLength: 38,
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
						echo "<strong>Notice:</strong> Student is already accepted.";
						echo "</div>";
					}
					if($_GET['message']==1){
						echo "<div class='alert alert-success'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Success!</strong> Review is complete. Student can be accepted.";
						echo "</div>";
					}
					if($_GET['message']==2){
						echo "<div class='alert alert-danger'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Alert!</strong> Something went wrong while updting a record.";
						echo "</div>";
					}
					if($_GET['message']==3){
						echo "<div class='alert alert-warning'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Notice:</strong> The student information is still pending review.";
						echo "</div>";
					}
					if($_GET['message']==4){
						echo "<div class='alert alert-success'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Success!</strong> Application updated.";
						echo "</div>";
					}
					if($_GET['message']==5){
						echo "<div class='alert alert-success'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Success!</strong> Student accepted and billing statements created.";
						echo "</div>";
					}
					if($_GET['message']==6){
						echo "<div class='alert alert-success'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Success!</strong> Email request sent.";
						echo "</div>";
					}
                                        if($_GET['message']==7){
                                                echo "<div class='alert alert-success'>";
                                                echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                                echo "<strong>Success!</strong> Enrollment application deleted.";
                                                echo "</div>";
                                        }
				}
			?>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>SY</th>
						<th>Name</th>
						<th>Level</th>
						<th>LRN</th>
						<th>PSA</th>
						<th>Birthday</th>
						<th>Age</th>
						<th>Sex</th>
						<th>Mother Tongue</th>
						<th>Address 1</th>
						<th>Address 2</th>
						<th>Address 3</th>
						<th>Zip</th>
						<th>Father's Name</th>
						<th>Mother's Name</th>
						<th>Guardian's Name</th>
						<th>Email</th>
						<th>Phone 1</th>
						<th>Phone 2</th>
						<th>Phone 3</th>
                                                <th>Comments</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>SY</th>
						<th>Name</th>
						<th>Level</th>
						<th>LRN</th>
						<th>PSA</th>
						<th>Birthday</th>
						<th>Age</th>
						<th>Sex</th>
						<th>Mother Tongue</th>
						<th>Address 1</th>
						<th>Address 2</th>
						<th>Address 3</th>
						<th>Zip</th>
						<th>Father's Name</th>
						<th>Mother's Name</th>
						<th>Guardian's Name</th>
						<th>Email</th>
						<th>Phone 1</th>
						<th>Phone 2</th>
						<th>Phone 3</th>
                                                <th>Comments</th>
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
