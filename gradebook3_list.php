<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

$current_user = wp_get_current_user();
$current_user_name = $current_user->first_name . " " . $current_user->last_name;
$current_user_id = $current_user->ID;

if(isset($_GET['cid']) and isset($_GET['pid']) and isset($_GET['tid'])){
	$course_id = $_GET['cid'];
	$period_id = $_GET['pid'];
	$type_id = $_GET['tid'];
	$sql = "SELECT * FROM lms_courses WHERE id = $course_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$course_name = $row['name'];
	$sql = "SELECT * FROM grading_periods WHERE id = $period_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$period_name = $row['name'];
	$sql = "SELECT * FROM assignment_types WHERE id = $type_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$type_name = $row['name'];
}

?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: {
				url: "gradebook3.php",
				type: "POST",
				data: {
					id: <?php echo $type_id ?>,
					pid: <?php echo $period_id ?>
				}
			},
		table: "#example",
		fields: [ {
				type: "hidden",
 				name:  "type_id",
				default: <?php echo $type_id?>
			}, {
				type: "hidden",
 				name:  "period_id",
				 default: <?php echo $period_id?>
			}, {
				label: "Name:",
 				name:  "name",
			}, {
				type: "textarea",
				label: "Description:",
 				name:  "description",
			}, {
				label: "Max Score:",
 				name:  "top_score",
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: {
				url: "gradebook3.php",
				type: "POST",
				data: {
					id: <?php echo $type_id ?>,
					pid: <?php echo $period_id ?>
				}
			},
		columns: [
			{ data: "id" },
			//{ data: "type_id" },
			//{ data: "period_id" },
			{ data: null, render: function ( data, type, row, meta ) {
				return '<a href="gradebook4_list.php?cid=<?php echo $course_id; ?>&pid=<?php echo $period_id; ?>&tid=<?php echo $type_id; ?>&aid='
						+ data.id
						+ '">' + data.name + '</a>';
				}
			},
			{ data: "description" },
			{ data: "top_score" }
		],
		select: true,
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			{ extend: "remove", editor: editor },
            {
                text: 'Add from LMS',
                action: function ( dt ) {
                    window.open("gradebook3.1.php?cid=<?php echo $course_id; ?>&pid=<?php echo $period_id; ?>&tid=<?php echo $type_id; ?>", "_self");
                }
            }
		]
	} );
} );








	</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<!-- <h1><span>You're logged in as <?php echo $current_user_name; ?></span></h1> -->
			<h1>
				<span>
					<a href = "gradebook0_list.php"><?php echo $course_name; ?></a> |
					<a href = "gradebook1_list.php?id=<?php echo $course_id; ?>"><?php echo $period_name; ?></a> |
					<a href = "gradebook2_list.php?cid=<?php echo $course_id; ?>&pid=<?php echo $period_id; ?>"><?php echo $type_name; ?></a> |
					<b>Activities</b>
				</span>
			</h1>
			<?php
			if(isset($_GET['alert'])){
					if($_GET['alert']==0){
						echo "<div class='alert alert-success'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Success!</strong> Quiz added.";
						echo "</div>";
					}
					if($_GET['alert']==1){
						echo "<div class='alert alert-warning'>";
						echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
						echo "<strong>Warning!</strong> Quiz already added.";
						echo "</div>";
					}
				}
			?>
			<br>
			<div class="demo-html"></div>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Description</th>
						<th>Max Score</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Description</th>
						<th>Max Score</th>
					</tr>
				</tfoot>
			</table>
			<!--
			<a href="enrollment_list.php" class="btn btn-default" role="button">Return to Enrollment List</a>
			-->
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
