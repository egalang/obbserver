<?php
include('../wp-load.php');
include('header.php');
//if ( !is_user_logged_in() ) {
//	header("Location: /index.php/login");
//}
if(isset($_GET['alert'])){
	$alert = $_GET['alert'];
} else {
	$alert = 0;
}
?>
	</script>
	<style type="text/css" class="init">
	.responsive {
		width: 24%;
		height: auto;
	}
	</style>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<div class="demo-html"></div>
			<h3><img src="tickets.png" class="responsive"> My Tickets</h3>
			<br>
			<?php
				if($alert==1){
					echo "<br><div class='alert alert-warning alert-dismissible'>
						  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						  Email address or ticket number does not exist.
						  </div>";
				}
				if($alert==2){
					$title = $_GET['issue'];
					$email = $_GET['email'];
					$description = $_GET['description'];
					$ticket_id = rand(100000,999999);
					$sql="insert into help (title,email,description,ticket_id) values ('$title','$email','$description','$ticket_id')";
					$result = $conn->query($sql);
					$to = $email;
					$subject = "Ticket No. ".$ticket_id." created";
					$body = "Hi, 
							<br><br>We have received your support request. We will get back to you as soon as possible. 
							<br>To check your email status, please use this email address and ticket number: 
							<br><br>Email: $email 
							<br>Ticket Number: $ticket_id
							<br><br><br>Thanks and best regards, 
							<br><br>OBBS Co. Support Team";
					$headers = array('Content-Type: text/html; charset=UTF-8','From: OBBS Co. Support <support@obbsco.com>','Cc: support@obbsco.com');
					if (wp_mail( $to, $subject, $body, $headers )){
						echo "<br><div class='alert alert-success alert-dismissible'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						Ticket created. Please check your email.
						</div>";
					} else {
						echo "<br><div class='alert alert-warning alert-dismissible'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						Something went wrong.
						</div>";
					}
				}
			?>
			<a href="help_new.php" class="btn btn-default">Create a new ticket</a>
			<br><br>
			<p>or view your existing ticket/s by providing the information below:</p>
			<form action="help_list.php">
				<div class="form-group">
					<label for="email">Email address:</label>
					<input type="email" class="form-control" id="email" name="email" required>
				</div>
				<div class="form-group">
					<label for="ticket_no">Ticket number:</label>
					<input type="text" class="form-control" id="ticket_no" name="id" required>
				</div>
				<!-- <div class="checkbox">
					<label><input type="checkbox"> Remember me</label>
				</div> -->
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
