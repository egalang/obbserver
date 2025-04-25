<?php
  include('header.php');
  if ( !is_user_logged_in() ) {
  	header("Location: /index.php/login");
  }

  //get enrollee data
  $enrollee_id = $_GET['id'];
  $enrollee="select * from enrollment_list where id=$enrollee_id";
  $enrollee_result = $conn->query($enrollee);
  $enrollee_row = $enrollee_result->fetch_assoc();

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
      <form action="update.php" method="post" enctype="multipart/form-data" name="myForm">
        <input type="hidden" name="review" value="0">
        <input type="hidden" name="id" value="<?php echo $enrollee_id; ?>">
        <button type="submit" class="btn btn-info">Update</button>
        <button formaction="reviewed.php?id=<?php echo $enrollee_id; ?>" class="btn btn-success">Mark as Reviewed</button>
        <!-- <a href="reviewed.php?id=<?php echo $enrollee_id ?>" class="btn btn-success">Mark as Reviewed</a> -->
        <a href="request.php?id=<?php echo $enrollee_id ?>" class="btn btn-warning">Send an Email Request</a>
        <a href="enrollment_list.php" class="btn btn-danger">Cancel</a><br><br>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="schoolyear">School Year</label>
              <select class="form-control" id="schoolyear" name="sy" readonly>
                <option <?php if($enrollee_row['sy']=='2020-2021'){ echo "selected"; } ?>>2020-2021</option>
                <option <?php if($enrollee_row['sy']=='2021-2022'){ echo "selected"; } ?>>2021-2022</option>
                <option <?php if($enrollee_row['sy']=='2022-2023'){ echo "selected"; } ?>>2022-2023</option>
                <option <?php if($enrollee_row['sy']=='2023-2024'){ echo "selected"; } ?>>2023-2024</option>
                <option <?php if($enrollee_row['sy']=='2024-2025'){ echo "selected"; } ?>>2024-2025</option>
              </select>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- space -->
          </div>
          <div class="col-sm-5">
            <div class="form-group">
              <p><b>Check the appropriate box only.<b></p>
              <label class="radio-inline"><input type="radio" name="wlrn" value="1" <?php if($enrollee_row['wlrn']==1){ echo "checked"; } ?> required >No LRN</label>
              <label class="radio-inline"><input type="radio" name="wlrn" value="2" <?php if($enrollee_row['wlrn']==2){ echo "checked"; } ?>>With LRN</label>
              <label class="radio-inline"><input type="radio" name="wlrn" value="3" <?php if($enrollee_row['wlrn']==3){ echo "checked"; } ?>>Returning (Balik-Aral)</label>
            </div>
          </div>
        </div>
        <div class="well well-sm">Student Information</div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="bcno">PSA Birth Certificate No.</label>
              <input type="text" class="form-control" id="bcno" name="psa" placeholder="PSA Birth Certificate Number" value="<?php echo $enrollee_row['psa']; ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="lrn">Learner Reference No. (LRN)</label>
              <input type="text" class="form-control" id="lrn" name="lrn" placeholder="Learner Reference Number" value="<?php echo $enrollee_row['lrn']; ?>">
            </div>
          </div>
        </div>
        <!-- Name -->
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="firstname">First Name</label>
              <input type="text" class="form-control" id="firstname" name="firstname" required placeholder="First Name" value="<?php echo $enrollee_row['firstname']; ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="middlename">Middle Name</label>
              <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" value="<?php echo $enrollee_row['middlename']; ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="lastname">Last Name</label>
              <input type="text" class="form-control" id="lastname" name="lastname" required placeholder="Last Name" value="<?php echo $enrollee_row['lastname']; ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="extname">Extension Name (if applicable)</label>
              <input type="text" class="form-control" id="extname" name="extname" placeholder="e.g. Jr., III" value="<?php echo $enrollee_row['extname']; ?>">
            </div>
          </div>
        </div>
        <!-- birth/age/sex -->
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="birth">Date of Birth</label>
              <input type="date" class="form-control" id="birth" name="birth" required value="<?php echo $enrollee_row['birth']; ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="age">Age</label>
              <input type="number" class="form-control" id="age"name="age" required placeholder="Age" value="<?php echo $enrollee_row['age']; ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="sex">Sex</label>
              <select class="form-control" id="sex" name="sex">
                <option <?php if($enrollee_row['sex']=="Male"){ echo "selected"; } ?>>Male</option>
                <option <?php if($enrollee_row['sex']=="Female"){ echo "selected"; } ?>>Female</option>
              </select>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
                <label for="level">Level</label>
                <select class="form-control" id="level" name="level" required <?php if($enrollee_row['reviewed']=='Y'){ echo "readonly"; } ?>>
                        <option value=''>&nbsp;</option>
                        <?php
                        $levels="select * from grade_levels";
                        $levels_result = $conn->query($levels);
                        if ($levels_result->num_rows > 0) {
                                // output data of each row
                                while($levels_row = $levels_result->fetch_assoc()) {
                                        if ($enrollee_row['level']==$levels_row['id']) {
                                                echo "<option selected value='".$levels_row['id']."'>".$levels_row['name']."</option>";
                                        } else {
                                                echo "<option value='".$levels_row['id']."'>".$levels_row['name']."</option>";
                                        }
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
              <p><b>Belonging to any Indigenous Peoples (IP) Community/Indigenous Cultural Community?<b></p>
              <label class="radio-inline"><input type="radio" name="ip" value="Y" <?php if($enrollee_row['ip']=="Y"){ echo "checked"; } ?>>Yes</label>
              <label class="radio-inline"><input type="radio" name="ip" value="N" <?php if($enrollee_row['ip']=="N"){ echo "checked"; } ?>>No</label>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="iptext">If yes, please specify.</label>
              <input type="text" class="form-control" id="iptext" name="iptext" value="<?php echo $enrollee_row['iptext']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="mt">Mother Tongue</label>
              <input type="text" class="form-control" id="mt" name="mt" required placeholder="Mother Tongue" value="<?php echo $enrollee_row['mt']; ?>">
            </div>
          </div>
        </div>
        <div class="well well-sm">Vaccination Information</div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <p><b>Is the learner vaccinated against COVID-19?<b></p>
              <label class="radio-inline"><input type="radio" name="vax" value="Y" <?php if($enrollee_row['vax']=="Y"){ echo "checked"; } ?> <?php if($enrollee_row['reviewed']=='Y'){ echo "disabled"; } ?> onclick="vaxOn()">Yes</label>
              <label class="radio-inline"><input type="radio" name="vax" value="N" <?php if($enrollee_row['vax']=="N"){ echo "checked"; } ?> <?php if($enrollee_row['reviewed']=='Y'){ echo "disabled"; } ?> onclick="vaxOff()">No</label>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="vax1">First Shot</label>
              <input type="date" class="form-control" id="vax1" name="vax1" value="<?php echo $enrollee_row['vax1']; ?>" <?php if($enrollee_row['reviewed']=='Y'){ echo "readonly"; } ?> <?php if($enrollee_row['vax']=="N"){ echo "readonly"; } ?>>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="vax2">Full Vaccination</label>
              <input type="date" class="form-control" id="vax2" name="vax2" value="<?php echo $enrollee_row['vax2']; ?>" <?php if($enrollee_row['reviewed']=='Y'){ echo "readonly"; } ?> <?php if($enrollee_row['vax']=="N"){ echo "readonly"; } ?>>
            </div>
          </div>
        </div>
        <p><i>Note: If the learner was vaccinated with Janssen, enter the date under full vaccination</i></p>
        <div class="well well-sm">Address Information</div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="add1">House No. & Street</label>
              <input type="text" class="form-control" id="add1" placeholder="House No. & Street" name="add1" required  value="<?php echo $enrollee_row['add1']; ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="add2">Barangay</label>
              <input type="text" class="form-control" id="add2" placeholder="Barangay" name="add2" required value="<?php echo $enrollee_row['add2']; ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group">
              <label for="add3">City/Municipality/Province/Country</label>
              <input type="text" class="form-control" id="add3" placeholder="City/Municipality/Province/Country" name="add3" required value="<?php echo $enrollee_row['add3']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="zip">Zip Code</label>
              <input type="number" class="form-control" id="add2" placeholder="Zip Code" name="zip" required value="<?php echo $enrollee_row['zip']; ?>">
            </div>
          </div>
        </div>
        <div class="well well-sm">Parent's / Guardian's Information</div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="father">Father's Name</label>
              <input type="text" class="form-control" id="father" placeholder="Last Name, First Name Middle Name" name="father" required value="<?php echo $enrollee_row['father']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="mother">Mother's Name</label>
              <input type="text" class="form-control" id="mother" placeholder="Last Name, First Name Middle Name" name="mother" required value="<?php echo $enrollee_row['mother']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="guardian">Guardian's Name</label>
              <input type="text" class="form-control" id="guardian" placeholder="Last Name, First Name Middle Name" name="guardian" value="<?php echo $enrollee_row['guardian']; ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="phone1">Home No.</label>
              <input type="text" class="form-control" id="phone1" placeholder="Home No." name="phone1" value="<?php echo $enrollee_row['phone1']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="phone2">Office No.</label>
              <input type="text" class="form-control" id="phone2" placeholder="Office No." name="phone2" value="<?php echo $enrollee_row['phone2']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="phone3">Mobile No.</label>
              <input type="text" class="form-control" id="phone3" placeholder="Mobile No." name="phone3" value="<?php echo $enrollee_row['phone3']; ?>">
            </div>
          </div>
        </div>
        <div class="well well-sm">For Returning Learners (Balik-Aral) and Those Who Shall Transfer/Move In</div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="lastgradelevel">Last Grade Level Completed</label>
              <input type="text" class="form-control" id="lastgradelevel" placeholder="Last Grade Level Completed" name="lastgradelevel" value="<?php echo $enrollee_row['lastgradelevel']; ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="lastSYcompleted">Last School Year Completed</label>
              <input type="text" class="form-control" id="lastSYcompleted" placeholder="Last School Year Completed" name="lastSYcompleted" value="<?php echo $enrollee_row['lastSYcompleted']; ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="schoolname">School Name</label>
              <input type="text" class="form-control" id="schoolname" placeholder="School Name" name="schoolname" value="<?php echo $enrollee_row['schoolname']; ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="schoolid">School ID</label>
              <input type="text" class="form-control" id="schoolid" placeholder="School ID" name="schoolid" value="<?php echo $enrollee_row['schoolid']; ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="schooladd">School Address</label>
              <input type="text" class="form-control" id="schooladd" placeholder="School Address" name="schooladd" value="<?php echo $enrollee_row['schooladd']; ?>">
            </div>
          </div>
        </div>
        <div class="well well-sm">For Learners in Senior High School</div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="semester">Semester</label>
              <select class="form-control" id="semester" name="semester">
                <option <?php if($enrollee_row['semester']=='1st'){ echo "selected"; } ?>>1st</option>
                <option <?php if($enrollee_row['semester']=='2nd'){ echo "selected"; } ?>>2nd</option>
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="track">Track</label>
              <input type="text" class="form-control" id="track" placeholder="Track" name="track" value="<?php echo $enrollee_row['track']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="strand">Strand</label>
              <input type="text" class="form-control" id="strand" placeholder="Strand" name="strand" value="<?php echo $enrollee_row['strand']; ?>">
            </div>
          </div>
        </div>
        <!-- <div class="well well-sm">Document Requirements</div>
        <div class="row">
          <div class="col-sm-4">
            <p>Please upload scanned images of your document requirements. Only JPG, JPEG, PNG & GIF files are allowed.  Maximum file size is 500 kilobytes.</p>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="fileToUpload">File Upload</label>
              <input type="file" name="fileToUpload" id="fileToUpload" required>
            </div>
          </div>
          <div class="col-sm-4">
            <form action="multi_file_upload.php" class="dropzone"></form>
          </div>
        </div> -->
        <div class="well well-sm">Payment Information</div>
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="wp_lastname">Last Name</label>
              <input type="text" class="form-control" id="wp_lastname" value="<?php echo $enrollee_row['wp_lastname']; ?>" placeholder="Last Name" name="wp_lastname" readonly>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="wp_firstname">First Name</label>
              <input type="text" class="form-control" id="wp_firstname" value="<?php echo $enrollee_row['wp_firstname']; ?>" placeholder="First Name" name="wp_firstname" readonly>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="wp_email">Email</label>
              <input type="email" class="form-control" id="wp_email" value="<?php echo $enrollee_row['wp_email']; ?>" placeholder="example@email.com" name="wp_email" readonly>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="form-group">
              <label for="terms">Payment Terms</label>
              <select class="form-control" id="terms" name="terms" required <?php if($enrollee_row['reviewed']=='Y'){ echo "readonly"; } ?>>
                <option value="">&nbsp;</option>
					<?php
					$terms="select * from payment_terms";
					$terms_result = $conn->query($terms);
					if ($terms_result->num_rows > 0) {
						// output data of each row
						while($terms_row = $terms_result->fetch_assoc()) {
							if ($enrollee_row['terms']==$terms_row['id']) {
								echo "<option selected value='".$terms_row['id']."'>".$terms_row['name']."</option>";
							} else {
								echo "<option value='".$terms_row['id']."'>".$terms_row['name']."</option>";
							}
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
        <button type="submit" class="btn btn-info">Update</button>
        <button formaction="reviewed.php?id=<?php echo $enrollee_id; ?>" class="btn btn-success">Mark as Reviewed</button>
        <!-- <a href="reviewed.php?id=<?php echo $enrollee_id ?>" class="btn btn-success">Mark as Reviewed</a> -->
        <a href="request.php?id=<?php echo $enrollee_id ?>" class="btn btn-warning">Send an Email Request</a>
        <a href="enrollment_list.php" class="btn btn-danger">Cancel</a>
      </form>
    </section>
  </div>
</body>
<?php
	$conn->close();
?>
</html>
