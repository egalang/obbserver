<?php
include('header.php');
//if ( !is_user_logged_in() ) {
//	header("Location: /index.php/login");
//}
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
			<form action="help_login.php">
				<div class="form-group">
					<label for="email">Email address:</label>
					<input type="email" class="form-control" id="email" name="email" required>
				</div>
				<div class="form-group">
					<label for="issue">Issue:</label>
					<input type="text" class="form-control" id="issue" name="issue" required>
				</div>
				<div class="form-group">
					<label for="description">Description:</label>
					<textarea class="form-control" rows="5" id="description" name="description" placeholder="Please describe your issue" required></textarea>
				</div>
				<input type="text" name="alert" value="2" hidden/>
				<!-- <div class="checkbox">
					<label><input type="checkbox"> Remember me</label>
				</div> -->
				<button type="submit" class="btn btn-default">Submit</button>
				<a href="help_login.php" class="btn btn-default">Cancel</a>
			</form>
			<?php
				if($alert==1){
					echo "<br><div class='alert alert-warning alert-dismissible'>
						  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						  Email address or ticket number does not exist.
						  </div>";
				}
			?>
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
