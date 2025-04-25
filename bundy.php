<?php
include('../custom/header.php');
if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
}

// employee details
$wp = wp_get_current_user();
$wp_id = $wp->ID;
$wp_email = $wp->user_email;
$wp_firstname = $wp->first_name;
$wp_lastname = $wp->last_name;

// today's date
date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d");

?>
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}

</script>
</head>
<body onload="startTime()">

	<!-- CSS -->
    <style>
    #my_camera{
        width: 320px;
        height: 240px;
        border: 1px solid black;
    }
	</style>
	<div>
		<h1>Welcome, <?php echo $wp_firstname.' '.$wp_lastname; ?>!</h1>
		<h4><span>Today is <?php echo $date; ?>. Current time is </span><span id="txt"></span></h4>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<button id="inOut" type="button" class="btn btn-default btn-block" onClick="take_snapshot()">Time In / Out</button>
			<button id="teach" type="button" class="btn btn-default btn-block" onClick="teach()">Teach Start / Stop</button>
			<button id="refresh" type="button" class="btn btn-default btn-block" onClick="refresh()">View My Actions</button>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-default">
				<div class="panel-heading">SYSTEM MESSAGES</div>
				<div id="inOutLog" class="panel-body">Loading...</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div id="my_camera"></div>
			<div id="results" hidden></div>
		</div>
	</div>
	
	<!-- Script -->
	<script type="text/javascript" src="webcamjs/webcam.min.js"></script>

	<!-- Code to handle taking the snapshot and displaying it locally -->
	<script language="JavaScript">
		
		// Configure a few settings and attach camera
		configure();
		updateLog();
		
		function configure(){
			Webcam.set({
				width: 320,
				height: 240,
				image_format: 'jpeg',
				jpeg_quality: 90
			});
			Webcam.attach( '#my_camera' );
		}
		// A button for taking snaps
		

		// preload shutter audio clip
		var shutter = new Audio();
		shutter.autoplay = false;
		shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';

		function take_snapshot() {
			// play sound effect
			//shutter.play();

			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
				// display results in page
				document.getElementById('results').innerHTML = 
					'<img id="imageprev" src="'+data_uri+'"/>';
			} );

			Webcam.reset();
			saveSnap();
			configure();
		}

		function saveSnap() {
			// Get base64 value from <img id='imageprev'> source
			var base64image =  document.getElementById("imageprev").src;
			Webcam.upload( base64image, 'bundy_upload.php', function(code, text) {
				 console.log('Save successfully');
				 //console.log(text);
            });
			updateLog();
		}
		
		function teach() {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("inOutLog").innerHTML = this.responseText;
				}
			};
			xmlhttp.open("GET", "teach.php", true);
			xmlhttp.send();
		}

		//update textLog
		function updateLog() {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("inOutLog").innerHTML = this.responseText;
				}
			};
			xmlhttp.open("GET", "getLogs.php", true);
			xmlhttp.send();
		}
		
		function refresh() {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("inOutLog").innerHTML = this.responseText;
				}
			};
			xmlhttp.open("GET", "refresh.php", true);
			xmlhttp.send();
		}
		
		</script>
	
</body>
</html>
