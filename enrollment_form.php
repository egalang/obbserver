<?php
  include('header.php');

  if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
  }
  
  $wp = wp_get_current_user();
  $wp_id = $wp->ID;
  $wp_email = $wp->user_email;
  $wp_firstname = $wp->first_name;
  $wp_lastname = $wp->last_name;
?>
function vaxOn() {
  document.getElementById("vax1").readOnly = false;
  document.getElementById("vax2").readOnly = false;
}

function vaxOff() {
  document.getElementById("vax1").readOnly = true;
  document.getElementById("vax2").readOnly = true;
}
</script>
</head>
<body class="dt-example dt-example-bootstrap">
	<div class="container">
		<section>
			<div class="demo-html"></div>
      <form action="enroll.php" method="post" enctype="multipart/form-data">
      <!-- <form method="get" action="action_page.php"> -->
        <!-- top -->
        <?php
          if(isset($_GET['success'])){
            if($_GET['success']==1){
              $alert="alert-success";
            }else{
              $alert="alert-danger";
            }
          }
          if(isset($_GET['message'])){
            echo "<div class='alert ".$alert."'>";
            echo $_GET['message'];
            echo '</div>';
          }
        ?>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="schoolyear">School Year</label>
              <select class="form-control" id="schoolyear" name="sy">
                <!-- <option>2020-2021</option> -->
                <option selected>2024-2025</option>
              </select>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- space -->
          </div>
          <div class="col-sm-5">
            <div class="form-group">
              <p><b>Check the appropriate box only.<b></p>
              <label class="radio-inline"><input type="radio" name="wlrn" value="1">No LRN</label>
              <label class="radio-inline"><input type="radio" name="wlrn" value="2" checked>With LRN</label>
              <label class="radio-inline"><input type="radio" name="wlrn" value="3">Returning (Balik-Aral)</label>
            </div>
          </div>
        </div>
        <div class="well well-sm">Student Information</div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="bcno">PSA Birth Certificate No.</label>
              <input type="text" maxlength="15" class="form-control" id="bcno" name="psa" placeholder="PSA Birth Certificate Number">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="lrn">Learner Reference No. (LRN)</label>
              <input type="text" maxlength="15" class="form-control" id="lrn" name="lrn" placeholder="Learner Reference Number">
            </div>
          </div>
        </div>
        <!-- Name -->
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="firstname">First Name</label>
              <input type="text" maxlength="30" class="form-control" id="firstname" name="firstname" required placeholder="First Name">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="middlename">Middle Name</label>
              <input type="text" maxlength="20" class="form-control" id="middlename" name="middlename" placeholder="Middle Name">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="lastname">Last Name</label>
              <input type="text" maxlength="30" class="form-control" id="lastname" name="lastname" required placeholder="Last Name">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="extname">Extension Name (if applicable)</label>
              <input type="text" maxlength="15" class="form-control" id="extname" name="extname" placeholder="e.g. Jr., III">
            </div>
          </div>
        </div>
        <!-- birth/age/sex -->
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="birth">Date of Birth</label>
              <input type="date" class="form-control" id="birth" name="birth" required>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="age">Age</label>
              <input type="number" class="form-control" id="age"name="age" required placeholder="Age">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="sex">Sex</label>
              <select class="form-control" id="sex" name="sex">
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
				<label for="level">Level</label>
				<select class="form-control" id="level" name="level" required>
					<option value=''>&nbsp;</option>
					<?php
					
					$levels="select * from grade_levels";
					$levels_result = $conn->query($levels);
					if ($levels_result->num_rows > 0) {
						// output data of each row
						while($levels_row = $levels_result->fetch_assoc()) {
							echo "<option value='".$levels_row['id']."'>".$levels_row['name']."</option>";
						}
					}
					
					?>
				</select>
            </div>
          </div>
        </div>
        <!-- ip group -->
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <p><b>Belonging to any Indigenous Peoples (IP) Community/Indigenous Cultural Community?</b></p>
              <label class="radio-inline"><input type="radio" name="ip">Yes</label>
              <label class="radio-inline"><input type="radio" name="ip" checked>No</label>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="iptext">If yes, please specify.</label>
              <input type="text" maxlength="15" class="form-control" id="iptext" name="iptext">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="mt">Mother Tongue</label>
              <input type="text" maxlength="15" class="form-control" id="mt" name="mt" required placeholder="Mother Tongue">
            </div>
          </div>
        </div>
        <div class="well well-sm">Vaccination Information</div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <p><b>Is the learner vaccinated against COVID-19?</b></p>
              <label class="radio-inline"><input type="radio" name="vax" value="Y" onclick="vaxOn()">Yes</label>
              <label class="radio-inline"><input type="radio" name="vax" value="N" checked onclick="vaxOff()">No</label>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="vax1">First Shot</label>
              <input type="date" class="form-control" id="vax1" name="vax1" readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="vax2">Full Vaccination</label>
              <input type="date" class="form-control" id="vax2" name="vax2" readonly>
            </div>
          </div>
        </div>
        <p><i>Note: If the learner was vaccinated with Janssen, enter the date under full vaccination</i></p>
        <div class="well well-sm">Address Information</div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="add1">House No. & Street</label>
              <input type="text" maxlength="70" class="form-control" id="add1" placeholder="House No. & Street" name="add1" required>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="add2">Barangay</label>
              <input type="text" maxlength="30" class="form-control" id="add2" placeholder="Barangay" name="add2" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group">
              <label for="add3">City/Municipality/Province/Country</label>
              <input type="text" maxlength="30" class="form-control" id="add3" placeholder="City/Municipality/Province/Country" name="add3" required>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="zip">Zip Code</label>
              <input type="text" class="form-control" id="add2" placeholder="Zip Code" name="zip" required>
            </div>
          </div>
        </div>
        <div class="well well-sm">Parent's / Guardian's Information</div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="father">Father's Name</label>
              <input type="text" maxlength="50" class="form-control" id="father" placeholder="Last Name, First Name Middle Name" name="father" required>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="mother">Mother's Name</label>
              <input type="text" maxlength="50" class="form-control" id="mother" placeholder="Last Name, First Name Middle Name" name="mother" required>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="guardian">Guardian's Name</label>
              <input type="text" maxlength="50" class="form-control" id="guardian" placeholder="Last Name, First Name Middle Name" name="guardian">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="phone1">Home No.</label>
              <input type="text" maxlength="15" class="form-control" id="phone1" placeholder="Home No." name="phone1">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="phone2">Office No.</label>
              <input type="text" maxlength="15" class="form-control" id="phone2" placeholder="Office No." name="phone2">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="phone3">Mobile No.</label>
              <input type="text" maxlength="15" class="form-control" id="phone3" placeholder="Mobile No." name="phone3">
            </div>
          </div>
        </div>
        <div class="well well-sm">For Returning Learners (Balik-Aral) and Those Who Shall Transfer/Move In</div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="lastgradelevel">Last Grade Level Completed</label>
              <input type="text" maxlength="15" class="form-control" id="lastgradelevel" placeholder="Last Grade Level Completed" name="lastgradelevel">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="lastSYcompleted">Last School Year Completed</label>
              <input type="text" maxlength="15" class="form-control" id="lastSYcompleted" placeholder="Last School Year Completed" name="lastSYcompleted">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="schoolname">School Name</label>
              <input type="text" maxlength="30" class="form-control" id="schoolname" placeholder="School Name" name="schoolname">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="schoolid">School ID</label>
              <input type="text" maxlength="15" class="form-control" id="schoolid" placeholder="School ID" name="schoolid">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="schooladd">School Address</label>
              <input type="text" maxlength="110" class="form-control" id="schooladd" placeholder="School Address" name="schooladd">
            </div>
          </div>
        </div>
        <!--<div class="well well-sm">For Learners in Senior High School</div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="semester">Semester</label>
              <select class="form-control" id="semester" name="semester">
                <option>1st</option>
                <option>2nd</option>
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="track">Track</label>
              <input type="text" maxlength="15" class="form-control" id="track" placeholder="Track" name="track">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="strand">Strand</label>
              <input type="text" maxlength="15" class="form-control" id="strand" placeholder="Strand" name="strand">
            </div>
          </div>
        </div>-->
        <!-- <div class="well well-sm">Document Requirements</div>
        <div class="row">
          <div class="col-sm-4">
            <p>Please upload scanned images of your document requirements. Only JPG, JPEG, PNG & GIF files are allowed.  Maximum file size is 500 kilobytes.</p>
          </div>
          <div class="col-sm-8">
            <iframe src="files_list.php" width="100%" height="500" style="border:none;"></iframe>
            <div class="form-group">
              <label for="fileToUpload">File Upload</label>
              <input type="file" name="fileToUpload" id="fileToUpload" required>
            </div>
          </div>
        </div> -->
        <div class="well well-sm">Payment Information</div>
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="wp_lastname">Last Name</label>
              <input type="text" class="form-control" id="wp_lastname" value="<?php echo $wp_lastname; ?>" placeholder="Last Name" name="wp_lastname" readonly>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="wp_firstname">First Name</label>
              <input type="text" class="form-control" id="wp_firstname" value="<?php echo $wp_firstname; ?>" placeholder="First Name" name="wp_firstname" readonly>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="wp_email">Email</label>
              <input type="email" class="form-control" id="wp_email" value="<?php echo $wp_email; ?>" placeholder="example@email.com" name="wp_email" readonly>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="form-group">
              <label for="terms">Payment Terms</label>
              <select class="form-control" id="terms" name="terms" required>
                <option value="">&nbsp;</option>
                                        <?php
                                        
                                        $terms="select * from payment_terms";
                                        $terms_result = $conn->query($terms);
                                        if ($terms_result->num_rows > 0) {
                                                // output data of each row
                                                while($terms_row = $terms_result->fetch_assoc()) {
                                                        echo "<option value='".$terms_row['id']."'>".$terms_row['name']."</option>";
                                                }
                                        }
                                        
                                        ?>
              </select>
            </div>
          </div>
          <div class="col-sm-1">
            <div class="form-group">
              <!-- <label for="books">Books</label>
              <select class="form-control" id="books" name="books">
                  <option>No</option>
                  <option>Yes</option>
              </select> -->
              <p>Books</p>
              <div class="checkbox">
                <label><input type="checkbox" value="Y" name="books" checked disabled></label>
              </div>
            </div>
          </div>
        </div>
        <div class="well well-sm">Privacy Statement</div>
        <div class="checkbox">
          <label><input type="checkbox" id="myCheck" name="test" required>I hereby certify that the above information given are true and correct to the best of my knowledge and I allow the Department of Education to use my child’s details to create and/or update his/her learner profile in the School Information System. The information herein shall be treated as confidential in compliance with the Data Privacy Act of 2012.</label>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
    </section>
  </div>
</body>
<?php
	$conn->close();
?>
</html>

