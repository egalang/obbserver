<?php
include('enrollment_config.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// empty join_enrollees table
$empty_table = "TRUNCATE TABLE `join_enrollees`";
$conn->query($empty_table);

$students = "SELECT * FROM lms_enrollees";
$students_result = $conn->query($students);
if ($students_result->num_rows > 0) {
    while($students_row = $students_result->fetch_assoc()) {
        $course_id = $students_row['course_id'];
        $user_id = $students_row['user_id'];
        $role = 'student';
        $enrollees = "INSERT INTO `join_enrollees` (`course_id`, `user_id`, `role`) VALUES ('$course_id','$user_id','$role')";
        if ($conn->query($enrollees) === TRUE) {
            echo "New user ".$user_id." created in ".$course_id." as ".$role."\r\n";
        } else {
            echo "Error: ". $conn->error . "\r\n";
        }
    }
}
$teachers = "SELECT * FROM `lms_courses`";
$teachers_result = $conn->query($teachers);
if ($teachers_result->num_rows > 0) {
    while($teachers_row = $teachers_result->fetch_assoc()) {
        $course_id = $teachers_row['id'];
        $user_id = $teachers_row['teacher_id'];
        $role = "editingteacher";
        $enrollees = "INSERT INTO `join_enrollees` (`course_id`, `user_id`, `role`) VALUES ('$course_id','$user_id','$role')";
        if ($conn->query($enrollees) === TRUE) {
            //echo "New record created successfully\r\n";
            echo "New user ".$user_id." created in ".$course_id." as ".$role."\r\n";
        } else {
            echo "Error: ". $conn->error . "\r\n";
        }
    }
}

$conn->close();
?>