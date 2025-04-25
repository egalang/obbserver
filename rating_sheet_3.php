<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

if(!isset($_GET['section']) and !isset($_GET['period'])){
	exit;
} else {
	$section_id = $_GET['section'];
	$period_id = $_GET['period'];
		//for LJCSI only
		if( $section_id < 4 OR $section_id == 15 OR $section_id == 18 OR $section_id == 19 OR $section_id == 20 ){
			header("Location: rating_sheet_2.php?section=$section_id&period=$period_id");
		}
}

?>
</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<?php
		$sql = "SELECT * FROM grading_periods WHERE id=$period_id";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$period_name = $row['name'];
		$sql = "SELECT * FROM sections WHERE id=$section_id";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$section_name = $row['name'];
		$level_id = $row['level_id'];
		$level_sql = "SELECT * FROM grade_levels WHERE id=$level_id";
		$level_result = $conn->query($level_sql);
		$level_row = $level_result->fetch_assoc();
		$level_name = $level_row['name'];
	?>
	<center>
	<h1><span><b><?php echo strtoupper($level_name." ".$section_name." ".$period_name." "); ?>RATING SHEET</b></span></h1>
	</center>
	<br>
	<table class="table table-bordered table-striped">
		<tr><th>STUDENT NAME</th>
			<?php
				$sql="SELECT DISTINCT lms_courses.code,lms_courses.sort_order 
					FROM report_card 
					LEFT JOIN enrollment_list on enrollment_list.id=report_card.student_id 
					LEFT JOIN lms_courses on lms_courses.id=report_card.course_id 
					WHERE report_card.period_id=$period_id AND lms_courses.section_id=$section_id
					ORDER BY lms_courses.sort_order ASC";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo "<th>".strtoupper($row['code']). "</th>";
				}
				} else {
				echo "0 results";
				}
			?>
			<th>GA</th><th>Comments</th>
		</tr>
		<?php
			$sql="SELECT DISTINCT report_card.student_id,enrollment_list.lastname,enrollment_list.firstname,enrollment_list.sex
				  FROM report_card 
				  LEFT JOIN enrollment_list on enrollment_list.id=report_card.student_id 
				  left JOIN lms_courses on lms_courses.id=report_card.course_id 
				  WHERE report_card.period_id=$period_id AND lms_courses.section_id=$section_id
				  ORDER BY enrollment_list.sex DESC, enrollment_list.lastname ASC;";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$mapeh = 0; $mapeh_count = 0; $non_mapeh = 0; $non_mapeh_count = 0;
				$student_id = $row['student_id'];
				echo "<tr><td><a href='report_card_g1.php?id=".$student_id."'>".strtoupper($row['lastname'].", ".$row['firstname']). "</a></td>";
				$grade = "SELECT report_card.student_id,lms_courses.sort_order,lms_courses.code,report_card.grade
						  FROM report_card 
						  LEFT JOIN enrollment_list on enrollment_list.id=report_card.student_id 
						  left JOIN lms_courses on lms_courses.id=report_card.course_id 
						  WHERE report_card.period_id=$period_id AND report_card.student_id=$student_id AND lms_courses.section_id=$section_id
						  ORDER BY lms_courses.sort_order;";
				$grade_result = $conn->query($grade);
				if ($grade_result->num_rows > 0) {
					while($grade_row = $grade_result->fetch_assoc()) {
						echo "<td>".$grade_row['grade']."</td>";
						if($grade_row['sort_order']>=10){
							$mapeh = $mapeh + $grade_row['grade'];
							$mapeh_count++;
						} else {
							$non_mapeh = $non_mapeh + $grade_row['grade'];
							$non_mapeh_count++;
						}
					}
				}
				//$mapeh = number_format($mapeh/4,2);
				$mapeh = number_format($mapeh/$mapeh_count,0); //changed from 2 to 0 decimal places
				//$ig = number_format(( $non_mapeh + $mapeh ) / ( $non_mapeh_count + 1 ),2);
				$ig = number_format(( $non_mapeh ) / ( $non_mapeh_count ),3); //changed from 2 to 3 decimal places
				//$qg_sql = "SELECT * FROM grade_scale where scale <= $ig LIMIT 1";
				//$qg_result = $conn->query($qg_sql);
				//$qg_row = $qg_result->fetch_assoc();
				//$qg = $qg_row['grade'];
				echo "<td>$ig</td>";
				//get comments
				if($period_id==1){
					$cmt_sql = "SELECT * FROM character_building WHERE student_id = $student_id AND value_id = 29";
				} elseif ($period_id==2){
					$cmt_sql = "SELECT * FROM character_building WHERE student_id = $student_id AND value_id = 30";
				} elseif ($period_id==3){
					$cmt_sql = "SELECT * FROM character_building WHERE student_id = $student_id AND value_id = 31";
				} else {
					$cmt_sql = "SELECT * FROM character_building WHERE student_id = $student_id AND value_id = 32";
				}
				$cmt_result = $conn->query($cmt_sql);
				$cmt_row = $cmt_result->fetch_assoc();
				$cmt = $cmt_row['value'];
				//end get comments
				echo "<td>".$cmt."</td>";
				echo "</tr>";
			}
			} else {
				echo "0 results";
			}
		?>
	</table>
</body>
</html>
