<?php
include('header.php');
//get school settings
$settings="select * from school_settings";
$settings_result=$conn->query($settings);
$settings_row=$settings_result->fetch_assoc();
//get $enrollee data
$enrollee_id=$_GET['id'];
$enrollee="SELECT * FROM enrollment_list where id=$enrollee_id";
$enrollee_result = $conn->query($enrollee);
$enrollee_row = $enrollee_result->fetch_assoc();



?>
</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<p>This is a preview of your request letter.</p><br>
			<form action="send_request.php">
			<p><button type="submit" class="btn btn-success">Send Now</button> <a href="enrollment_list.php" class="btn btn-danger">Cancel</a></p>
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
						<div class="panel-body">Request for Additional Information (or Documents)</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">MESSAGE:</div>
						<div class="panel-body">
							<p>Dear <?php echo $enrollee_row['wp_firstname'].' '.$enrollee_row['wp_lastname']; ?>,</p><br>
							<p><?php echo $settings_row['request_message']; ?></p><br>
							<input type="hidden" id="id" name="id" value="<?php echo $enrollee_id ?>">
							<div class="form-group">
								<label for="comment">Please give more information or submit the following:</label>
								<textarea class="form-control" rows="5" id="comment" name="comment"></textarea>
							</div>
							<br>
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
			</form>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>