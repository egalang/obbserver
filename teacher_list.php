<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

?>


var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "teachers.php",
		table: "#example",
		fields: [ {
				label: "WP ID:",
 				name:  "wp_id",
				type:  "select",
				options: [
					{ label: "Please select teacher", value: "" },
					<?php
						$sql = "SELECT ID, display_name FROM ljcsi_cms.wp_users
								LEFT JOIN ljcsi_cms.wp_pp_group_members ON ljcsi_cms.wp_pp_group_members.user_id = ljcsi_cms.wp_users.ID
								WHERE ljcsi_cms.wp_pp_group_members.group_id = 11";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								echo "{ label: '".$row['ID']." - ".$row['display_name']."', value: '".$row['ID']."' },";
							}
						}
					?>
					//{ label: "1 (highest)", value: "1" },
					//{ label: "2",           value: "2" },
					//{ label: "3",           value: "3" },
					//{ label: "4",           value: "4" },
					//{ label: "5 (lowest)",  value: "5" }
				]
			}, {
				label: "Last Name:",
 				name:  "lastname",
			}, {
				label: "First Name:",
 				name:  "firstname",
			}, {
				label: "Middle Name:",
 				name:  "middlename",
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "teachers.php",
		columns: [
			{ data: "id" },
			{ data: "wp_id" },
			{ data: "lastname" },
			{ data: "firstname" },
			{ data: "middlename" }
		],
		select: true,
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			{ extend: "remove", editor: editor }
		]
	} );
} );








	</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<div class="demo-html"></div>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>WP ID</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Middle Name</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>WP ID</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Middle Name</th>
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
<?php
$conn->close();
?>
</html>
