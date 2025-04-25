<?php
include('enrollment_config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$course_id = $_GET['cid'];
$period_id = $_GET['pid'];
$type_id = $_GET['tid'];
$quiz_id = $_GET['lms_id'];
$assignment_id = $_GET['aid'];
echo $assignment_id."<br>";
$sql = "SELECT idnumber, grade FROM test_lms.mdl_quiz_grades
        left join test_lms.mdl_user on test_lms.mdl_user.id = test_lms.mdl_quiz_grades.userid 
        where quiz = $quiz_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $student_id = $row['idnumber'];
        $grade = $row['grade'];
        echo $student_id."<br>";
        echo $grade."<br>";
        //$grade_sql = "INSERT INTO gradebook (assignment_id, student_id, grade)
        //              VALUES ($assignment_id, $student_id, $grade)";
        $grade_sql = "UPDATE gradebook SET grade = $grade 
                      WHERE assignment_id = $assignment_id and student_id = $student_id";
        if ($conn->query($grade_sql) === TRUE) {
            echo "Record updated successfully<br>";
        } else {
            echo "Error updating record: " . $conn->error."<br>";
        }
        //$grade_result = $conn->query($grade_sql);
    }
}

$conn->close();
header("Location: gradebook4_list.php?cid=$course_id&pid=$period_id&tid=$type_id&aid=$assignment_id");
?>