<?php
include('header.php');
//if ( !is_user_logged_in() ) {
//	header("Location: /index.php/login");
//}
if( isset($_GET['email']) and isset($_GET['id']) and $_GET['id']<>'' and $_GET['email']<>'' ){
	$email = $_GET['email'];
	$id = $_GET['id'];
	$sql = "select * from help where email='$email' and ticket_id=$id";
	$result = $conn->query($sql);
	if ($result->num_rows < 1) {
		header("Location: help_login.php?alert=1");
	}
} else {
	header("Location: help_login.php");
}
?>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		//ajax: "help.php",
		ajax: {
				url: "help.php",
				type: "POST",
				data: {
					email: <?php echo "'".$email."'" ?>
				}
			},
		table: "#example",
		fields: [ {
				label: "Issue:",
 				name:  "help.title",
			}, {
				type: "textarea",
				label: "Description:",
 				name:  "help.description",
				attr: {
        			placeholder: "Please describe your issue."
    			}
			}, {
				label: "Email:",
 				name:  "help.email",
			}, {
				type: "hidden",
				name: "help.ticket_id",
				default: "<?php echo rand(100000,999999); ?>", 
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		//ajax: "help.php",
		ajax: {
				url: "help.php",
				type: "POST",
				data: {
					email: <?php echo "'".$email."'" ?>
				}
			},
		columns: [
			{ data: "help.ticket_id" },
			//{ data: "help.title" },
			{ data: null, render: function ( data, type, row, meta ) {
				return  '<a href="help_talk_list.php?id=' + data.help.ticket_id + '">' + data.help.title + '</a>';
				}
			},
			{ data: "help.description" },
			{ data: "help.email" },
			{ data: "help.status" },
			{ data: "help.date" }
		],
		select: true,
		pageLength: 5,
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			//{ extend: "remove", editor: editor }
            {
                text: 'Refresh',
                action: function ( dt ) {
                    location.reload();
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
			<h3><img src="tickets.png" class="responsive"> My Tickets</h3>
			<br>
			<table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Issue</th>
						<th>Description</th>
						<th>Email</th>
						<th>Status</th>
						<th>Date</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Description</th>
						<th>Email</th>
						<th>Status</th>
						<th>Date</th>
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
