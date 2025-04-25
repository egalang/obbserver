<?php
include('header.php');
//get school settings
$settings="select * from school_settings";
$settings_result=$conn->query($settings);
$settings_row=$settings_result->fetch_assoc();
//get payment data
$payment_id = $_GET['id'];
$payment="SELECT * FROM payment_list where id=$payment_id";
$payment_result = $conn->query($payment);
$payment_row = $payment_result->fetch_assoc();
//get $enrollee data
$enrollee_id=$payment_row['enrollment_id'];
$enrollee="SELECT * FROM enrollment_list where id=$enrollee_id";
$enrollee_result = $conn->query($enrollee);
$enrollee_row = $enrollee_result->fetch_assoc();
//get enrollee grade level
$level_id = $payment_row['level_id'];
$level = "select * from grade_levels where id=$level_id";
$level_result = $conn->query($level);
$level_row = $level_result->fetch_assoc();
//get enrollee payment terms
$terms_id = $payment_row['term_id'];
$terms = "select * from payment_terms where id=$terms_id";
$terms_result = $conn->query($terms);
$terms_row = $terms_result->fetch_assoc();
?>
</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<p>This is a preview of the Statement of Account (SOA).</p><br>
			<p><a href="send_soa.php?id=<?php echo $payment_id; ?>" class="btn btn-success">Send Now</a> <a href="enrollment_list.php" class="btn btn-danger">Cancel</a></p>
			<div class="row">
				<div class="col-sm-4">
					<div class="panel panel-default">
						<div class="panel-heading">TO:</div>
						<div class="panel-body"><?php echo $enrollee_row['wp_email']; ?></div>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="panel panel-default">
						<div class="panel-heading">SUBJECT:</div>
						<div class="panel-body">Statement of Account (SOA-<?php echo date("Y-m-d"); ?>)</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">MESSAGE:</div>
						<div class="panel-body">
							<p>Dear <?php echo $enrollee_row['wp_firstname'].' '.$enrollee_row['wp_lastname']; ?>,</p><br>
							<p><?php echo $settings_row['soa_message']; ?></p><br>
							<table class="table table-hover">
							<tr><th>Name</th><td><?php echo $enrollee_row['lastname'].', '.$enrollee_row['firstname'].' '.$enrollee_row['middlename']; ?></td><th>Level</th><td><?php echo $level_row['name']; ?></td></tr>
							<tr><th>Payment Info</th><td><?php echo $terms_row['name'].' - '.$payment_row['comments']; ?></td><th>Payment Tranche</th><td><?php echo $payment_row['tranche']; ?></td></tr>
							<tr><th>Amount</th><td><?php echo number_format($payment_row['amount'],2,".",","); ?></td><th>Due Date</th><td><?php echo $payment_row['due_date']; ?></td></tr>
						</table><br>
							<!-- <p><img src="pay-now-button-lat.png" width="120"></p><br> -->
							<p>Best regards,</p><br>
							<p><?php echo $settings_row['contact']; ?></p>
							<p><?php echo $settings_row['position']; ?></p>
							<p><?php echo $settings_row['name']; ?></p>
							<br>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
