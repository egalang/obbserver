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
$school_address = strtoupper($row['address']." ".$row['city']);
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

			table, th, td {
				border-collapse: collapse;
			}
		</style>

		<title>Report Card</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="js/jquery-3.3.1.min.js"></script>
		<!--<script src='select2/dist/js/select2.min.js' type='text/javascript'></script>-->
		<script src="js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!--<link href='select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>-->
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
			while($marking_period_id <= 5) {
				$value_id = 1;
				while($value_id <= 25) {
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
			$value_id = 126;
			while($value_id <= 177) {
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
				<td style="width:49%; vertical-align:top">
					<table class="table table-bordered table-condensed" style="font-size:80%">
						<tr><td><center>Teachers Comment</center></td></tr>
						<tr><td>First Grading:</td></tr>
						<tr><td style='height:40px' contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[125]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[125]["value"]; ?></td></tr>
						<tr><td>Second Grading:</td></tr>
						<tr><td style='height:40px' contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[126]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[126]["value"]; ?></td></tr>
						<tr><td>Third Grading:</td></tr>
						<tr><td style='height:40px' contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[127]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[127]["value"]; ?></td></tr>
						<tr><td>Fourth Grading:</td></tr>
						<tr><td style='height:40px' contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[128]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[128]["value"]; ?></td></tr>
					</table>
					<table class="table table-bordered table-condensed" style="font-size:80%">
						<tr><td><center>Parents Comment</center></td></tr>
						<tr><td>First Grading:</td></tr>
						<tr><td style='height:40px'></td></tr>
						<tr><td>Second Grading:</td></tr>
						<tr><td style='height:40px'></td></tr>
						<tr><td>Third Grading:</td></tr>
						<tr><td style='height:40px'></td></tr>
						<tr><td>Fourth Grading:</td></tr>
						<tr><td style='height:40px'></td></tr>
					</table>
					<table class="table table-bordered table-condensed" style="font-size:80%">
						<tr><td>Eligible for transfer and adminission to</td><td width='200'>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td align='center' colspan='2' style='height:20px'>EVANGELINE P. DIZON, Ed.D.</td></tr>
						<tr><td>&nbsp;</td><td align='center' colspan='2' style='height:20px'>Administrator/Principal</td></tr>
					</table>
				</td>
				<td style="width:2%">&nbsp;</td>
				<td style="vertical-align:top">
					<table class="table table-bordered table-condensed" style="font-size:80%">
						<tr>
							<td colspan="6">
								<center>
								<h4><?php echo $school_name; ?><h4>
								<h5><?php echo $school_address; ?><h5>
								<h4>PROGRESS REPORT CARD<h4>
								<h5>School Year <?php echo $school_year; ?><h5>
								<br><img src="uploads/ljcsilogo.png" width="200"><br>
								<h5>Pre-School Level<h5>
								<h5>LRN: <?php echo $student_lrn; ?><h5>
								<br>
								</center>
							</td>
						</tr>
						<tr><td>Name:</td>
							<td colspan='3'><?php echo $student_name; ?></td>
						</tr>
						<tr>
							<td>Grade & Section:</td>
							<td><?php echo $grade_section; ?></td>
							<td>Sex:</td>
							<td><?php echo $student_sex; ?></td>
						</tr>
						<tr>
							<td colspan='4'>
								<br><i>&nbsp;&nbsp;Dear Parent, </i><br>
								<br><p align = "justify"><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This report card shows the ability and the progress your child has made based on the inside list of goals for achievement desired by the end of the year regarding his/her total personality. However,there is no way for this report to replace parent-teachers conference to arrive at a more complete assessment of your child's progress</i></p><br>
								<br><br>
							</td>
						</tr>
						<tr><td colspan='2' width='50%'><center>EVANGELINE P. DIZON, Ed.D.</center></td><td colspan='2'><center><?php echo strtoupper($current_user_name); ?></center></td></tr>
						<tr><td colspan='2'><center>Administrator/Principal</center></td><td colspan='2'><center>Teacher</center></td></tr>
					</table>
				</td>
			</tr>
		</table>
		<table  style="width:100%">
			<tr>
				<td style="width:49%; vertical-align:top">
					<b><i>I. ACADEMIC PROGRESS</i></b>
					<table class="table table-bordered table-condensed" style="font-size:80%">                       
						<tr>
							<td style="width:50%">Subjects</td>
							<td width='36'>1st</td>
							<td width='36'>2nd</td>
							<td width='36'>3rd</td>
							<td width='36'>4th</td>
							<td align="center">Final Rating</td>
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
                                    	echo "<td>".$subject_ave."</td></tr>";
                                    //     if($subject_ave>=75){
                                    //         echo "<td>Passed</td>";
                                    //     } else {
                                    //         echo "<td>Failed</td>";
                                    //     }
                                    } else {
                                        echo "<td>&nbsp;</td></tr>";
                                    }
                                }
                            } else {
                                echo "<tr><td>0 results</td></tr>";
                            }
                        ?>
						<tr>
							<td>General Average</td>
								<?php 
									$non_mapeh[1]=number_format(($non_mapeh[1])/($non_mapeh_count),3);
									$non_mapeh[2]=number_format(($non_mapeh[2])/($non_mapeh_count),3);
									$non_mapeh[3]=number_format(($non_mapeh[3])/($non_mapeh_count),3);
									$non_mapeh[4]=number_format(($non_mapeh[4])/($non_mapeh_count),3);
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
								} else {
									echo "<td>&nbsp;</td>";
								}
							?>
						</tr>
					</table>
					<table class='table table-bordered table-condensed' style='font-size:80%'><b><i>II. WORK AND STUDY HABITS</b></i>
						<tr>
							<td width="60%">Follows direction carefully</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[0]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[0]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[1]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[1]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[2]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[2]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[3]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[3]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[4]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[4]["value"]; ?></td>
							</form>
						</tr> 
						<tr>
							<td>Completes assigned task accurately and independently</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[5]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[5]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[6]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[6]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[7]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[7]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[8]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[8]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[9]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[9]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Works well alone</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[10]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[10]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[11]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[11]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[12]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[12]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[13]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[13]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[14]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[14]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Demonstrates proper attitude towards work</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[15]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[15]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[16]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[16]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[17]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[17]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[18]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[18]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[19]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[19]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>AVERAGE</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[20]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[20]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[21]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[21]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[22]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[22]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[23]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[23]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[24]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[24]["value"]; ?></td>
							</form>
						</tr>
					</table>
					<table class='table table-bordered table-condensed' style='font-size:80%'><b><i>III. SOCIAL SKILLS</b></i>
						<tr>
							<td width="60%">Shows respect for property and rights of others</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[25]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[25]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[26]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[26]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[27]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[27]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[28]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[28]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[29]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[29]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Shares talent and resources generously with others</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[30]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[30]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[31]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[31]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[32]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[32]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[33]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[33]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[34]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[34]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Practices honesty</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[35]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[35]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[36]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[36]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[37]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[37]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[38]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[38]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[39]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[39]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Demonstrates responsible obidience and courtesy</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[40]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[40]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[41]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[41]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[42]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[42]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[43]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[43]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[44]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[44]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Works and plays well with others</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[45]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[45]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[46]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[46]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[47]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[47]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[48]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[48]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[49]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[49]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>AVERAGE</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[50]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[50]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[51]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[51]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[52]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[52]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[53]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[53]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[54]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[54]["value"]; ?></td>
							</form>
						</tr>
					</table>
					<table class="table table-bordered table-condensed" style="font-size:80%">
						<tr>
							<td colspan='6'><b>LEGEND</td>
						</tr>
						<tr>
							<td><b><i>O</td>
							<td><i>Outstanding</td>
							<td><b><i>93-100</td>
							<td><b><i>MS</td>
							<td><i>Moderately Satisfactory</td>
							<td><b><i>75-80</td>
						</tr>
						<tr>
							<td><b><i>VS</td>
							<td><i>Very Satisfactory</td>
							<td><b><i>87-92</td>
							<td><b><i>NI</td><td><i>Needs Improvement</td><td><b><i>Below 75</td>
						</tr>
						<tr>
							<td><b><i>S</td>
							<td><i>Satisfactory</td>
							<td><b><i>81-86</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</td>
				<td style="width:2%">&nbsp;</td>	
				<td style='width:49%;vertical-align:top'>		
					<table class='table table-bordered table-condensed' style='font-size:80%'>
						<b><i>IV. MOTOR SKILLS</b></i>
						<tr>
							<td width="60%">Writes letters of the alphabet in manuscript form</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[55]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[55]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[56]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[56]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[57]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[57]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[58]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[58]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[59]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[59]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Copies simple words, phrases and sentences</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[60]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[60]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[61]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[61]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[62]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[62]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[63]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[63]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[64]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[64]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Colors within the lines</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[65]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[65]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[66]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[66]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[67]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[67]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[68]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[68]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[69]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[69]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Handles art materials correctly and properly</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[70]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[70]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[71]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[71]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[72]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[72]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[73]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[73]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[74]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[74]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Handles scissors well</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[75]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[75]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[76]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[76]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[77]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[77]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[78]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[78]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[79]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[79]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Claps and marches in time with music</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[80]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[80]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[81]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[81]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[82]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[82]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[83]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[83]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[84]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[84]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Able to run, jump, hop and skip</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[85]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[85]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[86]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[86]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[87]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[87]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[88]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[88]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[89]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[89]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Able to throw and catch a ball</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[90]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[90]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[91]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[91]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[92]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[92]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[93]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[93]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[94]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[94]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>AVERAGE</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[95]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[95]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[96]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[96]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[97]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[97]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[98]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[98]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[99]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[99]["value"]; ?></td>
							</form>
						</tr>
					</table>
					<table class='table table-bordered table-condensed' style='font-size:80%'>
						<b><i>V. SPIRITUAL PERFORMANCE</b></i>
						<tr>
							<td width="60%">Able to sing praises to God</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[100]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[100]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[101]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[101]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[102]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[102]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[103]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[103]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[104]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[104]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Says simple prayers</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[105]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[105]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[106]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[106]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[107]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[107]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[108]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[108]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[109]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[109]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Recalls and understands some bible stories</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[110]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[110]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[111]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[111]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[112]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[112]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[113]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[113]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[114]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[114]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>Recites memory verses</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[115]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[115]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[116]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[116]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[117]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[117]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[118]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[118]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[119]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[119]["value"]; ?></td>
							</form>
						</tr>
						<tr>
							<td>AVERAGE</td>
							<form>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[120]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[120]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[121]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[121]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[122]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[122]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[123]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[123]["value"]; ?></td>
								<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[124]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[124]["value"]; ?></td>
							</form>
						</tr>
					</table>
					<table class="table table-bordered table-condensed" style="font-size:80%">
						<tr>
							<td colspan="13"><center><b>ATTENDANCE RECORD</b></center></td>
						</tr>
						<tr><td>Month</td><td>A</td><td>S</td><td>O</td><td>N</td><td>D</td><td>J</td><td>F</td><td>M</td><td>A</td><td>M</td><td>J</td><td>Total</td></tr>
						<tr>
							<td>Days of School</td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[129]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[129]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[130]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[130]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[131]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[131]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[132]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[132]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[133]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[133]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[134]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[134]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[135]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[135]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[136]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[136]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[137]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[137]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[138]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[138]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[139]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[139]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[140]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[140]["value"]; ?></td>
						</tr>
						<tr>
							<td>Days Present</td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[141]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[141]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[142]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[142]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[143]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[143]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[144]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[144]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[145]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[145]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[146]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[146]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[147]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[147]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[148]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[148]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[149]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[149]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[150]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[150]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[151]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[151]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[152]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[152]["value"]; ?></td>
						</tr>
						<tr>
							<td>Days Absent</td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[153]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[153]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[154]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[154]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[155]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[155]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[156]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[156]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[157]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[157]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[158]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[158]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[159]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[159]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[160]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[160]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[161]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[161]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[162]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[162]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[163]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[163]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[164]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[164]["value"]; ?></td>
						</tr>
						<tr>
							<td>Days Tardy</td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[165]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[165]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[166]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[166]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[167]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[167]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[168]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[168]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[169]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[169]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[170]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[170]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[171]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[171]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[172]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[172]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[173]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[173]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[174]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[174]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[175]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[175]["value"]; ?></td>
							<td contenteditable="true" onBlur="saveToDatabase(this,'value','<?php echo $faq[176]["id"]; ?>')" onClick="showEdit(this);"><?php echo $faq[176]["value"]; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
