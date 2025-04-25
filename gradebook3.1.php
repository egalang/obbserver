<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

if(isset($_GET['cid']) and isset($_GET['pid']) and isset($_GET['tid'])){
	$course_id = $_GET['cid'];
	$period_id = $_GET['pid'];
	$type_id = $_GET['tid'];
	$sql = "SELECT * FROM lms_courses WHERE id = $course_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$course_name = $row['name'];
	$sql = "SELECT * FROM grading_periods WHERE id = $period_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$period_name = $row['name'];
	$sql = "SELECT * FROM assignment_types WHERE id = $type_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$type_name = $row['name'];
}

?>
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<!-- <h1><span>You're logged in as <?php echo $current_user_name; ?></span></h1> -->
			<h1>
				<span>
					<a href = "gradebook0_list.php"><?php echo $course_name; ?></a> |
					<a href = "gradebook1_list.php?id=<?php echo $course_id; ?>"><?php echo $period_name; ?></a> |
					<a href = "gradebook2_list.php?cid=<?php echo $course_id; ?>&pid=<?php echo $period_id; ?>"><?php echo $type_name; ?></a> |
					<a href = "gradebook3_list.php?cid=<?php echo $course_id; ?>&pid=<?php echo $period_id; ?>&tid=<?php echo $type_id; ?>">Activities</a> |
					<b>LMS Activities</b>
				</span>
			</h1>
			<br><br>
			<div class="demo-html"></div>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>Course ID</th>
						<th>Course Code</th>
						<th>Quiz Title</th>
						<th>Max Score</th>
						<th style='text-align:right'>Action</th>
					</tr>
				<thead>
				<tbody>
				<?php 
					$sql = "SELECT  test_lms.mdl_quiz.id, name, intro, idnumber, shortname, grade
							FROM test_lms.mdl_quiz 
							left join test_lms.mdl_course on test_lms.mdl_course.id = test_lms.mdl_quiz.course 
							where test_lms.mdl_course.idnumber = $course_id";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$lms = FALSE;
							$lms_sql = "SELECT * FROM `assignments` WHERE lms_id = " . $row['id'];
							$lms_result = $conn->query($lms_sql);
							if ($lms_result->num_rows > 0) {
								$lms = TRUE;
							}
							echo "<tr><td>".$row['idnumber']."</td><td>";
							echo $row['shortname']."</td><td>".$row['name']."</td><td>".number_format($row['grade']);
							echo "</td><td style='text-align:right'>";
							if ($lms == FALSE){
								echo "<a class='btn btn-success btn-xs' href='gradebook3.2.php?cid=$course_id&pid=$period_id&";
								echo "tid=$type_id&title=".$row['name']."&desc=".$row['intro']."&ts=".$row['grade'];
								echo "&lms_id=".$row['id']."'>Add</a>";
							}else{
								echo "Already added";
							}
							echo "</td></tr>";
						}
					}
				?>
				</tbody>
				<tfoot>
					<tr>
						<th>Course ID</th>
						<th>Course Code</th>
						<th>Quiz Title</th>
						<th>Max Score</th>
						<th style='text-align:right'>Action</th>
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
