<?php
session_start();
if(!isset($_SESSION["display_name"])){
    header("location: index.php");
}
$barcode = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>OBBServer HRIS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../editor/css/editor.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../editor/examples/resources/syntax/shCore.css">
    <link rel="stylesheet" type="text/css" href="../editor/examples/resources/demo.css">
    <style type="text/css" class="init">

    </style>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <script type="text/javascript" language="javascript" src="../editor/js/dataTables.editor.min.js"></script>
    <script type="text/javascript" language="javascript" src="../editor/js/editor.bootstrap.min.js"></script>
    <script type="text/javascript" language="javascript" src="../editor/examples/resources/syntax/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../editor/examples/resources/demo.js"></script>
    <script type="text/javascript" language="javascript" src="../editor/examples/resources/editor-demo.js"></script>
    <script>
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		// ajax: "attendance.php",
		ajax: {
				url: "attendance.php",
				type: "POST",
				data: {
					barcode: "<?php echo $barcode ?>"
				}
			},
		table: "#example",
		fields: [ {
			//	label: "ID:",
 			//	name:  "id",
			// }, {
				label: "Log Type:",
 				name:  "logtype",
				type: 'select',
            options: [
                { label: 'School In', value: 'SI' },
                { label: 'School Out', value: 'SO' },
                { label: 'Class In', value: 'CI' },
                { label: 'Class Out', value: 'CO' }
            ]
			}, {
 				//label: "Barcode:",
 				name:  "barcode",
				type: "hidden",
				def:  "<?php echo $barcode ?>",
			}, {
 				type: "datetime",
				format:  'YYYY-MM-DD h:mm:ss',
				label: "Date:",
 				name:  "date",
			}
		]
	} );

	$('#example').DataTable( {
		dom: "Bfrtip",
		// ajax: "attendance.php",
		ajax: {
				url: "attendance.php",
				type: "POST",
				data: {
					barcode: "<?php echo $barcode ?>"
				}
			},
		columns: [
			//{ data: "id" },
			//{ data: "barcode" },
			{ data: "date" },
			{ data: null, render: function ( data, type, row, meta ) {
				if ( data.logtype=="SI" ) {
						return 'School In';
					} else if ( data.logtype=="SO") {
						return 'School Out';
					} else if ( data.logtype=="CI") {
                        return 'Class In';
                    } else if ( data.logtype=="CO") {
                        return 'Class Out';
                    } else {
                        return '';
                    }
				}
			}
		],
		select: true,
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			// { extend: "remove", editor: editor }
		]
	} );
} );
    </script>
</head>

<body class="dt-example dt-example-bootstrap">

<nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">OBBServer HRIS</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <?php
      if(isset($_SESSION["display_name"])){
      ?>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Employee<span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="employee_list.php">Master List</a></li>
                        <li><a href="employee_attendance.php">Time Sheet</a></li>
                    </ul>
                </li>
                <li><a href="#">Reports</a></li>
                <?php
      }
      ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
                <?php 
        if(!isset($_SESSION["display_name"])){
          ?>
                <li><a href="#" data-toggle="modal" data-target="#myModal"><span
                            class="glyphicon glyphicon-log-in"></span> Login</a></li>
                <?php
        } else {
          ?>
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> Welcome back
                        <?php echo ucwords($_SESSION["display_name"]); ?> &nbsp; </a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                <?php
        }
      ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h3>Employee Time Sheet</h3>
        <table id="example" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>Date</th>
						<th>Type</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Date</th>
						<th>Type</th>
					</tr>
				</tfoot>
			</table>
    </div>

</body>

</html>