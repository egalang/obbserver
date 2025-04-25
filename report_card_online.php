<!DOCTYPE html>
<?php
include('enrollment_config.php');
include('../wp-load.php');

if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// get school details
$sql = "SELECT * FROM school_settings LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$school_name = strtoupper($row['name']);
$school_principal = strtoupper($row['contact']);
$school_year = $row['sy'];

// get student details
$student_id = $_GET['id'];
$sql = "SELECT *,grade_levels.name as lname,sections.name as sname FROM enrollment_list 
        LEFT JOIN grade_levels on grade_levels.id = enrollment_list.level
        LEFT JOIN sections on sections.id = enrollment_list.section_id
        WHERE enrollment_list.id=$student_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$student_name = strtoupper($row['lastname'].", ".$row['firstname']);
$student_age = $row['age'];
//calculate age
    // $student_birth = $row['birth'];
    // $today = date("Y-m-d");
    // $student_age = strtotime($today) - strtotime($student_birth);
    // $student_age = abs(round($student_age / 31556952));
//end calculate age
$student_sex = $row['sex'];
$student_lrn = $row['lrn'];
$grade_section = strtoupper($row['lname']." ".$row['sname']);

?>
<html lang="en">
    <head>
        <style> 
            input[type=text] {
                outline: none;
                border: none;  
            }
            @media print {
                footer {page-break-after: always;}
            }
        </style>
        <title>Report Card</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src='select2/dist/js/select2.min.js' type='text/javascript'></script>
        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link href='select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
        <script>
            function showEdit(editableObj) {
                $(editableObj).css("background","#FFF");
            } 
            
            function saveToDatabase(editableObj,column,id) {
                $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
                $.ajax({
                    url: "saveedit.php",
                    type: "POST",
                    data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
                    success: function(data){
                        $(editableObj).css("background","#FDFDFD");
                    }        
            });
            }
        </script>
    </head>
<body>
    <div class="container">
    <br>
    <?php
        // pre-populate report card
        $marking_period_id = 1;
        while($marking_period_id <= 4) {
            $sql0="SELECT DISTINCT assignment_types.course_id FROM `gradebook`
                   LEFT JOIN assignments ON assignments.id=gradebook.assignment_id
                   LEFT JOIN assignment_types ON assignment_types.id=assignments.type_id
                   WHERE student_id = $student_id;";
            $result = $conn->query($sql0);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $course_id = $row['course_id'];
                    $sql = "INSERT INTO report_card (student_id, course_id, period_id, grade) 
                            VALUES ($student_id, $course_id, $marking_period_id, null);";
                    if ($conn->query($sql) === TRUE) {
                        //echo "New record created successfully";
                    } else {
                        //echo "Error: " . $sql . "<br>" . $conn->error;
                    }									
                }
            }
            $marking_period_id++;
        }
        // pre-populate character building
        $marking_period_id = 1;
        while($marking_period_id <= 4) {
            $value_id = 1;
            while($value_id <= 7) {
                $sql = "INSERT INTO character_building (period_id, student_id, value_id, value) 
                        VALUES ($marking_period_id, $student_id, $value_id, '');";
                if ($conn->query($sql) === TRUE) {
                    //echo "New record created successfully";
                } else {
                    //echo "Error: " . $sql . "<br>" . $conn->error;
                }									
                $value_id++;
            }
            $marking_period_id++;
        }
        // pre-populate comments and attendance
        $marking_period_id = 0;
        $value_id = 29;
        while($value_id <= 80) {
            $sql = "INSERT INTO character_building (period_id, student_id, value_id, value) 
                    VALUES ($marking_period_id, $student_id, $value_id, '');";
            if ($conn->query($sql) === TRUE) {
                //echo "New record created successfully";
            } else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
            }									
            $value_id++;
        }								
        require_once("dbcontroller.php");
        $db_handle = new DBController();
        $sql = "SELECT * from character_building where student_id=$student_id";
        $faq = $db_handle->runQuery($sql);
    ?>
    <table style="width:100%">
        <tr>
            <td style="vertical-align:top">
                <table class="table table-bordered table-condensed">
                    <tr><td rowspan='2' style="width:50px"><img src="uploads/ljcsilogo.png" height="100"></td><td style="vertical-align:bottom"><strong><?php echo $school_name; ?></strong></td></tr>
                    <tr><td colspan='3'>REPORT CARD</td></tr>
                </table>
                <table class="table table-bordered table-condensed" style="font-size:80%">
                    <tr><td><strong>NAME</strong></td><td colspan='3'><?php echo $student_name; ?></td></tr>
                    <tr><td><strong>AGE</strong></td><td><?php echo $student_age; ?></td><td><strong>SEX</strong></td><td><?php echo $student_sex; ?></td></tr>
                    <tr><td><strong>LRN</strong></td><td><?php echo $student_lrn; ?></td><td><strong>GRADE & SECTION</strong></td><td><?php echo $grade_section; ?></td></tr>
                    <tr><td><strong>SCHOOL YEAR</strong></td><td><?php echo $school_year; ?></td><td><strong>ADVISER</strong></td><td><?php echo strtoupper($current_user_name); ?></td></tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width:100%">
        <tr>
            <td style="width:49%; vertical-align:top">
                <table class="table table-bordered table-condensed" style="font-size:80%">
                    <tr class="success"><td colspan="7"><center><strong>PERIODIC RATING</strong></center></td></tr>
                    <tr>
                        <td>Learning Areas</td>
                        <td width='36'>1</td>
                        <td width='36'>2</td>
                        <td width='36'>3</td>
                        <td width='36'>4</td>
                        <td>Final Rating</td>
                        <td>Remarks</td>
                    </tr>
                        <?php
                            $sql = "SELECT DISTINCT lms_courses.name,lms_courses.sort_order,lms_courses.id 
                                    FROM report_card
                                    LEFT JOIN enrollment_list ON enrollment_list.id=report_card.student_id
                                    LEFT JOIN lms_courses ON lms_courses.id=report_card.course_id
                                    WHERE report_card.student_id=$student_id
                                    ORDER BY lms_courses.sort_order ASC;";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $mapeh_count = 0;
                                $non_mapeh_count = 0;
                                while($row = $result->fetch_assoc()) {
                                    $course_id = $row['id'];
                                    $course_name = strtoupper($row['name']);
                                    echo "<tr><td>".$course_name."</td>";
                                    $grade = "SELECT lms_courses.name,lms_courses.sort_order,lms_courses.id,report_card.period_id,report_card.grade FROM report_card
                                            LEFT JOIN enrollment_list ON enrollment_list.id=report_card.student_id
                                            LEFT JOIN lms_courses ON lms_courses.id=report_card.course_id
                                            WHERE report_card.student_id=$student_id AND report_card.course_id=$course_id
                                            ORDER BY lms_courses.sort_order ASC,report_card.period_id ASC;";
                                    $grade_result = $conn->query($grade);
                                    if ($grade_result->num_rows > 0) {
                                        $subject_ave = 0; $subject_ave_count = 0;
                                        $mapeh[] = 0; $non_mapeh[] = 0;
                                        while($grade_row = $grade_result->fetch_assoc()) {
                                            echo "<td>".$grade_row['grade']."</td>";
                                            $subject_ave = $subject_ave + $grade_row['grade'];
                                            if($grade_row['grade']!=null){
                                                $subject_ave_count++;
                                            }
                                            
                                            if($grade_row['sort_order']>=10){
                                                $mapeh[$subject_ave_count]=$mapeh[$subject_ave_count]+$grade_row['grade'];
                                            } else {
                                                $non_mapeh[$subject_ave_count]=$non_mapeh[$subject_ave_count]+$grade_row['grade'];
                                            }
                                        }
                                    }
                                    if($row['sort_order']>=10){
                                        $mapeh_count++;
                                    } else {
                                        $non_mapeh_count++;
                                    }
                                    $subject_ave = number_format( $subject_ave / $subject_ave_count,2 );
                                    // hide subject average and remarks if incomlete quarterly grades
                                    if($subject_ave_count==4){
                                        echo "<td>".$subject_ave."</td>";
                                        if($subject_ave>=75){
                                            echo "<td>Passed</td>";
                                        } else {
                                            echo "<td>Failed</td>";
                                        }
                                    } else {
                                        echo "<td>&nbsp;</td><td>&nbsp;</td>";
                                    }
                                }
                            } else {
                                echo "<tr><td>0 results</td>";
                            }
                        ?>
                    </tr>
                    <tr>
                        <td>MAPEH</td>
                        <?php 
                            // $mapeh[1]=number_format($mapeh[1]/$mapeh_count,2);
                            // $mapeh[2]=number_format($mapeh[2]/$mapeh_count,2);
                            // $mapeh[3]=number_format($mapeh[3]/$mapeh_count,2);
                            // $mapeh[4]=number_format($mapeh[4]/$mapeh_count,2);
                            // $mapeh_ave=number_format((($mapeh[1]+$mapeh[2]+$mapeh[3]+$mapeh[4])/$subject_ave_count),2);
                            $mapeh[1]=number_format($mapeh[1]/$mapeh_count,0);
                            $mapeh[2]=number_format($mapeh[2]/$mapeh_count,0);
                            $mapeh[3]=number_format($mapeh[3]/$mapeh_count,0);
                            $mapeh[4]=number_format($mapeh[4]/$mapeh_count,0);
                            $mapeh_ave=number_format((($mapeh[1]+$mapeh[2]+$mapeh[3]+$mapeh[4])/$subject_ave_count),2);
                        ?>
                        <td>
                            <?php
                                if($mapeh[1]!=0){
                                    echo $mapeh[1];
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($mapeh[2]!=0){
                                    echo $mapeh[2];
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($mapeh[3]!=0){
                                    echo $mapeh[3];
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($mapeh[4]!=0){
                                    echo $mapeh[4];
                                }
                            ?>
                        </td>
                        <?php
                            // hide subject average and remarks if incomlete quarterly grades
                            if($subject_ave_count==4){
                                echo "<td>".$mapeh_ave."</td>";
                                if($mapeh_ave>=75){
                                    echo "<td>Passed</td>";
                                } else {
                                    echo "<td>Failed</td>";
                                }
                            } else {
                                echo "<td>&nbsp;</td><td>&nbsp;</td>";
                            }
                        ?>
                    </tr>
                    <tr>
                        <td>ECA</td><td colspan="2">&nbsp;</td><td colspan="2">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>General Average</td>
                        <?php 
                            // $non_mapeh[1]=number_format(($non_mapeh[1]+$mapeh[1])/($non_mapeh_count+1),2);
                            // $non_mapeh[2]=number_format(($non_mapeh[2]+$mapeh[2])/($non_mapeh_count+1),2);
                            // $non_mapeh[3]=number_format(($non_mapeh[3]+$mapeh[3])/($non_mapeh_count+1),2);
                            // $non_mapeh[4]=number_format(($non_mapeh[4]+$mapeh[4])/($non_mapeh_count+1),2);
                            // $non_mapeh_ave=number_format((($non_mapeh[1]+$non_mapeh[2]+$non_mapeh[3]+$non_mapeh[4])/$subject_ave_count),2);
                            $non_mapeh[1]=number_format(($non_mapeh[1]+$mapeh[1])/($non_mapeh_count+1),2);
                            $non_mapeh[2]=number_format(($non_mapeh[2]+$mapeh[2])/($non_mapeh_count+1),2);
                            $non_mapeh[3]=number_format(($non_mapeh[3]+$mapeh[3])/($non_mapeh_count+1),2);
                            $non_mapeh[4]=number_format(($non_mapeh[4]+$mapeh[4])/($non_mapeh_count+1),2);
                            $non_mapeh_ave=number_format((($non_mapeh[1]+$non_mapeh[2]+$non_mapeh[3]+$non_mapeh[4])/$subject_ave_count),3);
                        ?>
                        <td>
                            <?php
                                if($non_mapeh[1]!=0){
                                    echo $non_mapeh[1];
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($non_mapeh[2]!=0){
                                    echo $non_mapeh[2];
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($non_mapeh[3]!=0){
                                    echo $non_mapeh[3];
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($non_mapeh[4]!=0){
                                    echo $non_mapeh[4];
                                }
                            ?>
                        </td>
                        <?php
                            // hide subject average and remarks if incomlete quarterly grades
                            if($subject_ave_count==4){
                                echo "<td>".$non_mapeh_ave."</td>";
                                if($non_mapeh_ave>=75){
                                    echo "<td>Passed</td>";
                                } else {
                                    echo "<td>Failed</td>";
                                }
                            } else {
                                echo "<td>&nbsp;</td><td>&nbsp;</td>";
                            }
                        ?>
                    </tr>
                </table>						
                <table class="table table-bordered table-condensed" style="font-size:80%">
                    <tr><td class="success" colspan="13"><center><strong>ATTENDANCE RECORD</strong></center></td></tr>
                    <tr><td>Month</td><td>A</td><td>S</td><td>O</td><td>N</td><td>D</td><td>J</td><td>F</td><td>M</td><td>A</td><td>M</td><td>J</td><td>Total</td></tr>
                    <tr>
                        <td>Days of School</td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[32]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[32]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[33]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[33]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[34]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[34]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[35]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[35]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[36]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[36]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[37]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[37]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[38]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[38]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[39]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[39]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[40]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[40]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[41]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[41]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[42]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[42]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[43]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[43]["value"]; ?></td>
                    </tr>
                    <tr>
                        <td>Days Present</td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[44]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[44]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[45]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[45]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[46]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[46]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[47]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[47]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[48]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[48]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[49]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[49]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[50]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[50]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[51]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[51]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[52]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[52]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[53]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[53]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[54]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[54]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[55]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[55]["value"]; ?></td>
                    </tr>
                    <tr>
                        <td>Days Absent</td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[56]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[56]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[57]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[57]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[58]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[58]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[59]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[59]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[60]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[60]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[61]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[61]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[62]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[62]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[63]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[63]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[64]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[64]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[65]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[65]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[66]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[66]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[67]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[67]["value"]; ?></td>
                    </tr>
                    <tr>
                        <td>Days Tardy</td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[68]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[68]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[69]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[69]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[70]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[70]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[71]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[71]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[72]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[72]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[73]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[73]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[74]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[74]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[75]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[75]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[76]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[76]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[77]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[77]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[78]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[78]["value"]; ?></td>
                        <td contenteditable="false" onBlur="saveToDatabase(this,'value','<?php echo $faq[79]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[79]["value"]; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table></div>
</body>
</html>
