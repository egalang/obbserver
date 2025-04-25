<?php

include('header.php');
if(isset($_GET['id'])){
	$id = $_GET['id'];
}

$sql = "SELECT * FROM help where ticket_id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $title = $row['title'];
	$email = $row['email'];
	$date = $row['date'];
	$description = $row['description'];
	$status = $row['status'];
  }
} else {
  echo "0 results";
}
$conn->close();

?>

var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "help_talk_admin.php?id=<?php echo $id ?>",
		table: "#example",
		fields: [ {
				type:  "hidden",
 				name:  "help_talk.help_id",
				default: <?php echo $id ?>
			}, {
				type: "textarea",
                label: "Comments:",
                name: "help_talk.comments"
            }, {
				label: "Images:",
				name: "files[].id",
				type: "uploadMany",
				display: function ( fileId, counter ) {
					return '<img src="'+editor.file( 'files', fileId ).web_path+'"/>';
				},
				noFileText: 'No images'
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		ajax: "help_talk_admin.php?id=<?php echo $id ?>",
		columns: [
			//{ data: "help_talk.id" },
			//{ data: "help_talk.help_id" },
			{ data: "help_talk.comments" },
			{ data: "help_talk.date" },
			{
          		data: "files",
          		render: function ( d ) {
            		return d.length ?
            		d.length+' image(s)' :
            		'No image';
        		},
        		title: "Image"
    		}
		],
		select: true,
		//pageLength: 5,
		//order: [ 0, 'desc' ],
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			{ extend: "remove", editor: editor },
            {
                text: 'Back',
                action: function ( dt ) {
                    window.history.go(-1); return false;
                }
            }
		]
	} );
} );

	</script>
	<style type="text/css" class="init">
	.responsive {
		width: 24%;
		height: auto;
	}
	</style>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<div class="demo-html"></div>
			<table class="table table-striped table-bordered display responsive nowrap" style="width:100%">
			<tr><td>Ticket No.</td><td><?php echo $id ?></td><td>Date</td><td><?php echo $date ?></td></tr>
			<tr><td>Email</td><td><?php echo $email ?></td><td>Status</td><td><?php echo $status ?></td></tr>
			<tr><td>Issue</td><td colspan="3"><?php echo $title ?></td></tr>
			<tr><td>Description</td><td colspan="3"><?php echo $description ?></td></tr>
			</table>
			<br>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<!-- <th>ID</th>
						<th>Help ID</th> -->
						<th>Comments</th>
						<th>Date</th>
						<th>Attachments</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<!-- <th>ID</th>
						<th>Help ID</th> -->
						<th>Comments</th>
						<th>Date</th>
						<th>Attachments</th>
					</tr>
				</tfoot>
			</table>
		</section>
	</div>
</body>
</html>