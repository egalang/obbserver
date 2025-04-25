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

$grades="use $dbname_lms";
$conn->query($grades);
$grades="select mdl_quiz_grades.userid,mdl_user.lastname,mdl_user.firstname,".
        "mdl_user.idnumber as sis_id,mdl_quiz_grades.grade,mdl_quiz.course,mdl_course.fullname,".
        "mdl_quiz.name from $dbname_lms.mdl_quiz_grades ".
        "left join mdl_user on mdl_user.id=mdl_quiz_grades.userid ".
        "left join mdl_quiz on mdl_quiz.id=mdl_quiz_grades.quiz ".
        "left join mdl_course on mdl_course.id=mdl_quiz.course ";
//	"where mdl_user.idnumber<>''";
$grades_result = $conn->query($grades);

		if ($grades_result->num_rows > 0) {
		  // output data of each row
		  while($grades_row = $grades_result->fetch_assoc()) {
		    $status='Data Sync Successful';
		    $students="select * from $dbname_sis.students where student_id=".$grades_row['sis_id'];
		    $students_result = $conn->query($students);
		    if ($students_result->num_rows > 0) {
		      $students_row = $students_result->fetch_assoc();
		    }else{
		      $students_row['student_id']='No Matching Data';
		      $students_row['last_name']='No Matching Data';
		      $students_row['first_name']='No Matching Data';
		      $status='Data Sync Failed';
		    }
		    $course="select * from $dbname_sis.course_periods where title='".$grades_row['fullname']."'";
		    $course_result = $conn->query($course);
		    if ($course_result->num_rows > 0) {
		      $course_row = $course_result->fetch_assoc();
		    }else{
		      $course_row['course_period_id']='No Matching Data';
		      $course_row['title']='No Matching Data';
		      $status='Data Sync Failed';
		    }
                    $period="select * from $dbname_sis.course_period_var where course_period_id='".$course_row['course_period_id']."'";
                    $period_result = $conn->query($period);
                    if ($period_result->num_rows > 0) {
                      $period_row = $period_result->fetch_assoc();
                    }else{
                      $period_row['period_id']='No Matching Data';
                      $status='Data Sync Failed';
                    }
                    $assignment="select * from $dbname_sis.gradebook_assignments where title='".$grades_row['name']."'";
                    $assignment_result = $conn->query($assignment);
                    if ($assignment_result->num_rows > 0) {
                      $assignment_row = $assignment_result->fetch_assoc();
                    }else{
		      $assignment_row['title']='No Matching Data';
		      $status='Data Sync Failed';
		    }
		    //echo '<tr><td>'.$grades_row['userid'].'</td><td>'.$grades_row['lastname'].'</td><td>'.$grades_row['firstname'].
			 //'</td><td>'.$grades_row['sis_id'].'</td><td>'.$grades_row['fullname'].'</td><td>'.$grades_row['name'].
			 //'</td><td>'.$grades_row['grade'].'</td><td>'.$students_row['student_id'].'</td><td>'.$students_row['last_name'].
			 //'</td><td>'.$students_row['first_name'].'</td><td>'.$period_row['period_id'].'</td><td>'.$course_row['course_period_id'].
			 //'</td><td>'.$course_row['title'].'</td><td>'.$assignment_row['assignment_id'].'</td><td>'.$assignment_row['title'].
			 //'</td><td>'.$status.'</td></tr>';
		    if($status=='Data Sync Successful'){
		      $sync="insert into $dbname_sis.gradebook_grades (student_id,period_id,course_period_id,assignment_id,points) values ".
			    "(".$students_row['student_id'].
			    ",".$period_row['period_id'].
			    ",".$course_row['course_period_id'].
			    ",".$assignment_row['assignment_id'].
			    ",".$grades_row['grade'].")";
		      if($conn->query($sync)){
		        $status="Record Added. $status.";
		      }else{
		        $status="Record Already Exists.";
		      }
		    }
		    $log="insert into editor.sync_logs (table_name,log) values ".
			 "('gradebook_grades','student_id:".$students_row['student_id'].
			 ",period_id:".$period_row['period_id'].
			 ",course_period_id:".$course_row['course_period_id'].
			 ",assignment_id:".$assignment_row['assignment_id'].
			 ",points:".$grades_row['grade'].
			 ",result:$status".
			 ",userid:$wp_id')";
		    $conn->query($log);
		  }
		}

$conn->close();
header("Location: sync_grades_list.php?message=1");
?>
