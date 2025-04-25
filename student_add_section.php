<?php
include('enrollment_config.php');
include('../wp-load.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$students = "SELECT enrollment_list.id,enrollment_list.lastname,enrollment_list.firstname,enrollment_list.level,sections.id as section FROM enrollment_list LEFT JOIN sections ON sections.level_id = enrollment_list.level WHERE accepted = 'Y' AND deleted = 'N' AND sy = '2024-2025' AND section_id IS null";
$students_result = $conn->query($students);
if ($students_result->num_rows > 0) {
    while($students_row = $students_result->fetch_assoc()) {
        $section_id = $students_row['section'];
        $id = $students_row['id'];
        $section = "UPDATE enrollment_list SET section_id = $section_id WHERE id = $id";
        $conn->query($section);
    }
}
header("Location: student_section_list.php");
?>