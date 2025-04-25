<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// empty lms_enrollees table
$empty_table = "TRUNCATE TABLE `lms_enrollees`";
$conn->query($empty_table);

?>
var editor;

$(document).ready(function() {
   $('#example').DataTable();
} );


</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
            <table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
            <thead>
			<tr><th>ID</th><th>Code</th><th>Student ID</th><th>Name</th><th>Result</th></tr>
			</thead><tbody>
            <?php
                $courses = "SELECT * FROM lms_courses";
                $courses_result = $conn->query($courses);
                if ($courses_result->num_rows > 0) {
                    while($courses_row = $courses_result->fetch_assoc()) {
                        $course_id = $courses_row['id'];
                        $course_code = $courses_row['code'];
                        $level_id = $courses_row['level_id'];
                        $section_id = $courses_row['section_id'];
                        //get students based on section
                        $students = "SELECT * FROM enrollment_list WHERE accepted = 'Y' AND deleted = 'N' AND sy = '2024-2025' AND section_id = $section_id";
                        $students_result = $conn->query($students);
                        if ($students_result->num_rows > 0) {
                            while($students_row = $students_result->fetch_assoc()) {
                                $student_id = $students_row['id'];
                                $student_name = $students_row['lastname'].", ".$students_row['firstname'];
                                //check if student is already enrolled
                                $check = "SELECT * FROM lms_enrollees WHERE course_id = $course_id AND user_id = $student_id";
                                $check_result = $conn->query($check);
                                if ($check_result->num_rows > 0) {
                                    while($check_row = $check_result->fetch_assoc()) {
                                        echo "<tr><td>".$course_id."</td><td>".$course_code."</td><td>".$student_id."</td><td>".$student_name."</td><td>Student Already Enrolled</td></tr>";
                                    }
                                } else {
                                    //enroll student
                                    $enroll = "INSERT INTO lms_enrollees (course_id, user_id, role_id) VALUES ($course_id, $student_id, 1)";
                                    if ($conn->query($enroll) === TRUE) {
                                        echo "<tr><td>".$course_id."</td><td>".$course_code."</td><td>".$student_id."</td><td>".$student_name."</td><td>Student Enrolled Successfully</td></tr>";
                                    } else {
                                        echo "<tr><td>".$course_id."</td><td>".$course_code."</td><td>".$student_id."</td><td>".$student_name."</td><td>Error: " . $sql . $conn->error . "</td></tr>";
                                    }
                                }
                            }
                        }
                    }
                }
            ?>
            </tbody></table>
            <p><a href='course_list.php' class='btn btn-default'>Return to Course List</a></p>
        </section>
    </div>
</body>
</html>