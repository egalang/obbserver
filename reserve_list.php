<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$payments= "select payment_list.id,payment_list.enrollment_id,payment_list.level_id,payment_list.term_id,enrollment_list.wp_email,
			payment_list.tranche,payment_list.amount,enrollment_list.lastname,enrollment_list.firstname,
			enrollment_list.wp_email,enrollment_list.wp_firstname,enrollment_list.wp_lastname,
			payment_terms.name as term_name,grade_levels.name as grade_name from payment_list
			left join enrollment_list on payment_list.enrollment_id = enrollment_list.id
			left join grade_levels on payment_list.level_id = grade_levels.id
			left join payment_terms on payment_list.term_id = payment_terms.id
			where billed='Y' and paid='N'";
$payments_result = $conn->query($payments);

if ($payments_result->num_rows > 0) {
  // output data of each row
  while($payments_row = $payments_result->fetch_assoc()) {
    //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
	$payment_id=$payments_row['id'];
	$paid_date=date("Y-m-d");
	$enrollee_id=$payments_row['enrollment_id'];
	//echo $payment_id.", ".$paid_date."<br>";
	$refno=$payments_row['id'].$payments_row['enrollment_id'].$payments_row['level_id'].$payments_row['term_id'].$payments_row['tranche'];
	$title=$payments_row['lastname'].", ".$payments_row['firstname']." - ".$payments_row['grade_name']." (".$payments_row['term_name']." Payment Ref. No. ".$refno.")";
	//echo $title."<br>";
	
	//look for payment record
	$post="select * from educaksyon.wp_posts where post_title='$title'";
	$post_result = $conn->query($post);
	if ($post_result->num_rows > 0) {
		//echo "payment found<br>";
		$paid="update payment_list set paid='Y', paid_date='$paid_date' where id=$payment_id";
		//echo $paid."<br>";
		$conn->query($paid);

        //send email start
        $wp = wp_get_current_user();
        $wp_id = $wp->ID;
        $wp_email = $wp->user_email;
        $wp_firstname = $wp->first_name;
        $wp_lastname = $wp->last_name;
        //get school settings
        $settings = "select * from school_settings";
        $settings_result=$conn->query($settings);
        $settings_row=$settings_result->fetch_assoc();
        $payment_received=$settings_row['payment_received'];
        $to = $payments_row['wp_email'].",".$settings_row['email'];
        $subject = 'Thank You for Your Payment - (Ref. No. '.$refno.')';
        $body = "<p>Dear ".$payments_row['firstname']." ".$payments_row['lastname'].",
                        <br><br>$payment_received<br><br>
                        <table border='1'>
                        <tr><td>Name</td><td>".$payments_row['lastname'].", ".$payments_row['firstname']."</td></tr>
                        <tr><td>Grade Level</td><td>".$payments_row['grade_name']."</td></tr>
                        <tr><td>Payment Schedule</td><td>".$payments_row['term_name']."</td></tr>
                        <tr><td>Payment No.</td><td>".$payments_row['tranche']."</td></tr>
                        <tr><td>Amount</td><td>".$payments_row['amount']."</td></tr>
                        <tr><td>Paid Date</td><td>$paid_date</td></tr>
                        </table>
                        <br>
                        <a href='http://".$settings_row['domain']."'>Login to your account for more details.</a>
                        <br><br><br>Thanks,<br><br>$contact<br>$position<br>$name</p>";
        $headers = array('Content-Type: text/html; charset=UTF-8');
        //echo $body;
        if (wp_mail( $to, $subject, $body, $headers )){
                //header("Location: client_payment_list.php?id=$enrollee_id&message=1");
                //echo "Email sent";
        } else {
                echo "Something went wrong. Email not sent.";
        }
        //send email end

	} else {
		//echo "0 results";
	}
  }
} else {
  //echo "0 results";
  //header("Location: files_list.php");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "reserved.php",
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
		ajax: "reserved.php",
		columns: [
			{ data: "enrollment_list.sy" },
    { data: null, render: function ( data, type, row ) {
        return data.enrollment_list.lastname + ', ' + data.enrollment_list.firstname + ' ' + data.enrollment_list.middlename;
        }
      },
			{ data: "enrollment_list.reserved" },
			{ data: "enrollment_list.type" }
	],
    searching: false,
    select: true,
		pageLength: 38,
		buttons: [
			//{ extend: "create", editor: editor },
			//{ extend: "edit",   editor: editor },
			//{ extend: "remove", editor: editor },
			{
                text: 'Add Another Child',
                action: function ( dt ) {
                    window.open("reservation_form.php", "_self");
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
					echo "<strong>Success:</strong> Reservation submitted. The Statement Of Account is sent to your email. Please pay the reservation fee of P2,000 immediately.";
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
			<!-- <p>Select an application and click the EDIT button to upload files.  Files must be smaller that 500KB.  Only JPG, JPEG, PNG and GIF images are accepted.</p> -->
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>SY</th>
            			<th>Name</th>
						<th>Reserved</th>
						<th>Type</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>SY</th>
						<th>Name</th>
						<th>Reserved</th>
						<th>Type</th>
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
