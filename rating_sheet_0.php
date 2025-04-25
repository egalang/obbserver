<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

?>
</script>
</head>
<body class="dt-example dt-example-bootstrap">
    <div class="container">
	<div class="row">
		<form target="_blank" action="rating_sheet_1.php">
		<div class="col-sm-4">&nbsp;</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="section">Section:</label>
				<select class="form-control" id="section" name="section" required>
					<option value="">select section...</option>
					<?php
						$sql = "SELECT * FROM sections";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}
						} else {
							echo "<option>0 results</option>";
						}
					?>
				</select>
			</div>
			<div class="form-group">
				<label for="period">Grading Period:</label>
				<select class="form-control" id="period" name="period" required>
					<option value="">select grading period...</option>
					<?php
						$sql = "SELECT * FROM grading_periods";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}
						} else {
							echo "<option>0 results</option>";
						}
					?>
				</select>
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
		<div class="col-sm-4">&nbsp;</div>
		</form>
	</div>
</div>
</body>
</html>