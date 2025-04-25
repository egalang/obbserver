<?php
include('../../enrollment_config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  //echo "Connected to editor database. <br>";
}

//get school year information
$mp = "select * from $dbname_sis.marking_periods where marking_period_id=1";
$mp_result = $conn->query($mp);
if ($mp_result->num_rows > 0) {
  $mp_row = $mp_result->fetch_assoc();
}

//get courses
$courses = "select * from $dbname_sis.course_periods_view";
$courses_result = $conn->query($courses);
if ($courses_result->num_rows > 0) {
  while($courses_row = $courses_result->fetch_assoc()){
    echo $courses_row['title']."<br>";
      $student_count = 0;
      $students = "select * from $dbname_sis.student_enrollment where grade_id=".$courses_row['grade_level'];
      $students_result = $conn->query($students);
      if ($students_result->num_rows > 0) {
        while($students_row = $students_result->fetch_assoc()){
          echo "-- Student No. ".$students_row['student_id']." ";
          //check if already enrolled
          $check = "SELECT * FROM $dbname_sis.schedule where student_id=".$students_row['student_id']." and course_period_id=".$courses_row['course_period_id'];
          $check_result = $conn->query($check);
	  if ($check_result->num_rows > 0) {
            echo "already added.<br>";
          } else {
            echo "will be added -- ";
            $new = "INSERT INTO $dbname_sis.schedule ".
                   "(syear, school_id, student_id, start_date, end_date, modified_date, modified_by, course_id, course_period_id, mp, marking_period_id, dropped) ".
                   "VALUES ('2020', '1', '".
                   $students_row['student_id']."', '2020-08-24', '2021-04-16', '2020-08-12', '1', '".
                   $courses_row['course_id']."', '".
                   $courses_row['course_period_id']."', 'FY', 1, 'N')";
            if ($conn->query($new) === TRUE) {
              echo "success.<br>";
            } else {
              echo "Error: " . $sql . $conn->error . "<br>";
            }
          }
          $student_count++;
        }
      }
    echo "-- No. of Students: ".$student_count." ";
    $seats = "update $dbname_sis.course_periods set filled_seats=$student_count where course_period_id=".$courses_row['course_period_id'];
    if ($conn->query($seats) === TRUE) {
      echo "Seats updated successfully<br>";
    } else {
      echo "Error updating record: " . $conn->error . "<br>";
    }
  }
}

$conn->close();
?>
