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
$conn -> set_charset("utf8");

//check sy settings
$sy = "select sy from school_settings";
$sy_result = $conn->query($sy);
$sy_row = $sy_result->fetch_assoc();
//$school_year = $sy_row['sy'];
$school_year = "2024-2025";
if(isset($_GET['sy'])){
	$school_year = $_GET['sy'];
}


$balance="update enrollment_list set balance=0 where sy='$school_year'";
$balance_result = $conn->query($balance);

$balance="SELECT enrollment_id,sum(amount) totalamount from payment_list where paid='N' and sy='$school_year' group by enrollment_id order by enrollment_id";
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
		ajax: "students.php?id=<?php echo $school_year ?>",
		// ajax: {
			// 		url: "students.php",
			// 		type: "POST",
			// 		data: {
				// 			id: "<?php echo $school_year ?>"
				// 		}
				// 	},
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
		ajax: "students.php?id=<?php echo $school_year ?>",
		// ajax: {
		//		url: "students.php",
		//		type: "POST",
		//		data: {
		//			id: "<?php echo $school_year ?>"
		//		}
		//	},
		columns: [
			{ data: "enrollment_list.sy" },
			{ data: null, render: function ( data, type, row ) {
				return data.enrollment_list.lastname + ', ' + data.enrollment_list.firstname + ' ' + data.enrollment_list.middlename;
				}
			},
			{ data: "grade_levels.name" },
			{ data: "payment_terms.name" },
			{ data: null, render: function ( data, type, row, meta ) {
				return '<a href="payment_list.php?id=' + data.enrollment_list.id + '&sy=<?php echo $school_year; ?>">' + data.enrollment_list.balance + '</a>';
				}
			},
			{ data: "enrollment_list.reviewed" },
			{ data: "enrollment_list.accepted" },
			{ data: "enrollment_list.reserved" },
			{
          data: "files",
          render: function ( d ) {
              return d.length ?
                  d.length+' image(s)' :
                  'No image';
          },
          title: "Image"
      },
			{ data: null, render: function ( data, type, row, meta ) {
				if ( data.enrollment_list.card=="N" ) {
						return '<a class="btn btn-info btn-xs" href="review.php?id=' + data.enrollment_list.id + '">Review</a> ' +
							   '<a class="btn btn-success btn-xs" href="accept.php?id=' + data.enrollment_list.id + '">Accept</a> ' +
							   '<a class="btn btn-danger btn-xs" href="delete.php?id=' + data.enrollment_list.id + '">Delete</a> ' + 
							   '<a class="btn btn-info btn-xs" href="card.php?id=' + data.enrollment_list.id + '">Publish Card</a>';
					} else {
						return '<a class="btn btn-info btn-xs" href="review.php?id=' + data.enrollment_list.id + '">Review</a> ' +
							   '<a class="btn btn-success btn-xs" href="accept.php?id=' + data.enrollment_list.id + '">Accept</a> ' +
							   '<a class="btn btn-danger btn-xs" href="delete.php?id=' + data.enrollment_list.id + '">Delete</a> ' + 
							   '<a class="btn btn-info btn-xs" href="card.php?id=' + data.enrollment_list.id + '">Unpublish Card</a>';
					}
				}
			},
                        { data: "enrollment_list.comments" }
		],
		//serverSide: true,
		select: true,
		pageLength: 38,
		//order: [ 0, 'desc' ],
		buttons: [
			//{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			//{ extend: "remove", editor: editor }
            //{
            //    text: 'SY 2020-2021',
            //    action: function ( dt ) {
            //        window.open("enrollment_list.php?sy=2020-2021", "_self");
            //    }
            //}
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
						echo "<strong>Alert!</strong> Something went wrong while updating a record.";
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
					if($_GET['message']==8){
						echo "<div class='alert alert-warning'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Notice:</strong> The selected student's card is already published!";
						echo "</div>";
				}
			}
			?>
			<form class="form-inline" action="enrollment_list.php">
				<div class="form-group">
					<label for="schoolyear">School Year</label>
					<select class="form-control" id="schoolyear" name="sy">
						<option <?php if(($school_year=="2020-2021")){ echo "selected"; } ?>>2020-2021</option>
						<option <?php if(($school_year=="2021-2022")){ echo "selected"; } ?>>2021-2022</option>
						<option <?php if(($school_year=="2022-2023")){ echo "selected"; } ?>>2022-2023</option>
						<option <?php if(($school_year=="2023-2024")){ echo "selected"; } ?>>2023-2024</option>
						<option <?php if(($school_year=="2024-2025")){ echo "selected"; } ?>>2024-2025</option>
					</select>
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form><hr>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>SY</th>
						<th>Name</th>
						<th>Level</th>
						<th>Terms</th>
						<th>Balance</th>
						<th>Reviewed</th>
						<th>Accepted</th>
						<th>Reserved</th>
						<th>Files</th>
						<th>Actions</th>
                        <th>Comments</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>SY</th>
						<th>Name</th>
						<th>Level</th>
						<th>Terms</th>
						<th>Balance</th>
						<th>Reviewed</th>
						<th>Accepted</th>
						<th>Reserved</th>
						<th>Files</th>
						<th>Actions</th>
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
