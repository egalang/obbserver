<?php
  include('header.php');

  if ( !is_user_logged_in() ) {
        header("Location: /index.php/login");
  }

  $wp = wp_get_current_user();
  $wp_id = $wp->ID;
  $wp_email = $wp->user_email;
  $wp_firstname = $wp->first_name;
  $wp_lastname = $wp->last_name;

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  //echo "Connected to editor database. <br>";
}

if(isset($_GET['search'])){
	$search="where mdl_user.lastname like '%".$_GET['search']."%' ".
		"or mdl_user.firstname like '%".$_GET['search']."%' ".
		"or mdl_course.fullname like '%".$_GET['search']."%' ".
		"or mdl_quiz.name like '%".$_GET['search']."%'";
}else{
	$search="";
}

$grades="use $dbname_lms";
$conn->query($grades);
$grades="select mdl_quiz_grades.userid,mdl_user.lastname,mdl_user.firstname,".
        "mdl_user.idnumber as sis_id,mdl_quiz_grades.grade,mdl_quiz.course,mdl_course.fullname,".
        "mdl_quiz.name from $dbname_lms.mdl_quiz_grades ".
        "left join mdl_user on mdl_user.id=mdl_quiz_grades.userid ".
        "left join mdl_quiz on mdl_quiz.id=mdl_quiz_grades.quiz ".
        "left join mdl_course on mdl_course.id=mdl_quiz.course ".
	$search;
//	"where mdl_user.idnumber<>''";
$grades_result = $conn->query($grades);
?>
</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
                <section>
		<?php
		if($_GET['message']==1){
			echo "<div class='alert alert-success'>";
			echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			echo "<strong>Success!</strong> Grades were successfully synchronized.";
			echo "</div>";
		}
		?>
		<form action="sync_grades_list.php" method="get">
	        <div class="row">
	          <div class="col-sm-4">
		    <div class="form-group">
	              <input type="text" class="form-control" id="search" name="search" placeholder="Type a filter keyword and press enter">
		    </div>
	          </div>
		  <div class="col-sm-8">
<!--
		    <div class="form-group">
		      <input class="btn btn-default" type="submit" value="Search">
		    </div>
-->
		  </div>
		</div>
		</form>
		<table class='table table-bordered table-striped table-hover'>
		<tr><th colspan='7'>Moodle Data Source</th><th colspan='9'>OpenSIS Data Destination</th></tr>
		<tr><th colspan='4'>Student Information</th><th>Course Name</th><th>Quiz Name</th><th>Raw Score</th>
		<th colspan='3'>Student Informetion</th><th colspan='3'>Course Information</th><th colspan='3'>Assignment</th></tr>
		<?php
		if ($grades_result->num_rows > 0) {
		  // output data of each row
		  while($grades_row = $grades_result->fetch_assoc()) {
		    $status='<span class="glyphicon glyphicon-ok text-success"></span>';
		    $students="select * from $dbname_sis.students where student_id=".$grades_row['sis_id'];
		    $students_result = $conn->query($students);
		    if ($students_result->num_rows > 0) {
		      $students_row = $students_result->fetch_assoc();
		    }else{
		      $students_row['student_id']='<span class="glyphicon glyphicon-remove text-danger"></span>';
		      $students_row['last_name']='<span class="glyphicon glyphicon-remove text-danger"></span>';
		      $students_row['first_name']='<span class="glyphicon glyphicon-remove text-danger"></span>';
		      $status='<span class="glyphicon glyphicon-remove text-danger"></span>';
		    }
		    $course="select * from $dbname_sis.course_periods where title='".$grades_row['fullname']."'";
		    $course_result = $conn->query($course);
		    if ($course_result->num_rows > 0) {
		      $course_row = $course_result->fetch_assoc();
		    }else{
		      $course_row['course_period_id']='<span class="glyphicon glyphicon-remove text-danger"></span>';
		      $course_row['title']='<span class="glyphicon glyphicon-remove text-danger"></span>';
		      $status='<span class="glyphicon glyphicon-remove text-danger"></span>';
		    }
                    $period="select * from $dbname_sis.course_period_var where course_period_id='".$course_row['course_period_id']."'";
                    $period_result = $conn->query($period);
                    if ($period_result->num_rows > 0) {
                      $period_row = $period_result->fetch_assoc();
                    }else{
                      $period_row['period_id']='<span class="glyphicon glyphicon-remove text-danger"></span>';
                      $status='<span class="glyphicon glyphicon-remove text-danger"></span>';
                    }
                    $assignment="select * from $dbname_sis.gradebook_assignments where title='".$grades_row['name']."'";
                    $assignment_result = $conn->query($assignment);
                    if ($assignment_result->num_rows > 0) {
                      $assignment_row = $assignment_result->fetch_assoc();
                    }else{
		      $assignment_row['assignment_id']='<span class="glyphicon glyphicon-remove text-danger"></span>';
		      $assignment_row['title']='<span class="glyphicon glyphicon-remove text-danger"></span>';
		      $status='<span class="glyphicon glyphicon-remove" text-danger></span>';
		    }
		    echo '<tr><td>'.$grades_row['userid'].'</td><td>'.$grades_row['lastname'].'</td><td>'.$grades_row['firstname'].
			 '</td><td>'.$grades_row['sis_id'].'</td><td>'.$grades_row['fullname'].'</td><td>'.$grades_row['name'].
			 '</td><td>'.$grades_row['grade'].'</td><td>'.$students_row['student_id'].'</td><td>'.$students_row['last_name'].
			 '</td><td>'.$students_row['first_name'].'</td><td>'.$period_row['period_id'].'</td><td>'.$course_row['course_period_id'].
			 '</td><td>'.$course_row['title'].'</td><td>'.$assignment_row['assignment_id'].'</td><td>'.$assignment_row['title'].
			 '</td><td>'.$status.'</td></tr>';
		  }
		}
		?>
		</table>
		<p><a href='sync_grades.php' class='btn btn-default'>Synch Grades Now</a></p>
		</section>
	</div>
</body>
</html>
<?php $conn->close(); ?>
