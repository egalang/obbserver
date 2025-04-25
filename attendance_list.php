<?php
include('header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}
$barcode = $_GET['id'];
?>
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
	<div class="container">
		<section>
			<div class="demo-html"></div>
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
		</section>
	</div>
</body>
<?php
	$conn->close();
?>
</html>
