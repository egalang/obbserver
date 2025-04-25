<?php
  include('header.php');

  if ( !is_user_logged_in() ) {
	header("Location: /index.php/login");
  }
  if(isset($_GET["id"])){
    $id = $_GET["id"];
    $sql = "SELECT * FROM `enrollment_list` WHERE id = $id;";
    $result = $conn->query($sql);
    $enrollee_row = $result->fetch_assoc();
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
      <form action="reserve.php" method="post" enctype="multipart/form-data">
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
          <div class="form-group">
              <label for="type">New Student?</label>
              <select class="form-control" id="type" name="type" required>
                <option value=""></option>
                <option>New</option>
                <option>Old</option>
              </select>
            </div>
          </div>
          <div class="col-sm-5">
            <p>&nbsp;</p>
            <span class="pull-right"><a href="reserve_child_list.php" class="btn btn-default">Use Data of Old Student</a></span>
          </div>
        </div>
        <div class="well well-sm">Student Information</div>
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
              <?php
              $today = date("Y-m-d");
              $age_today = date_diff(date_create($enrollee_row['birth']), date_create($today));
              //echo $age_today->format('%y');
              ?>
              <input type="number" class="form-control" id="age"name="age" required placeholder="Age" value="<?php echo $age_today->format('%y'); ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="sex">Sex</label>
              <select class="form-control" id="sex" name="sex">
                <option value=''>Select Gender</option>
                <option <?php if($enrollee_row['sex']=="Male"){ echo "selected"; } ?>>Male</option>
                <option <?php if($enrollee_row['sex']=="Female"){ echo "selected"; } ?>>Female</option>
              </select>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
				<label for="level">Level</label>
				<select class="form-control" id="level" name="level" required>
					<option value=''>Select Grade Level</option>
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
              <input type="text" class="form-control" id="phone1" placeholder="Home No." name="phone1" maxlength="15" value="<?php echo $enrollee_row['phone1']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="phone2">Office No.</label>
              <input type="text" class="form-control" id="phone2" placeholder="Office No." name="phone2" maxlength="15" value="<?php echo $enrollee_row['phone2']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="phone3">Mobile No.</label>
              <input type="text" class="form-control" id="phone3" placeholder="Mobile No." name="phone3" maxlength="15" value="<?php echo $enrollee_row['phone3']; ?>">
            </div>
          </div>
        </div>
        <div class="well well-sm">Payment Information</div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wp_lastname">Last Name</label>
              <input type="text" class="form-control" id="wp_lastname" value="<?php echo $wp_lastname; ?>" placeholder="Last Name" name="wp_lastname" readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wp_firstname">First Name</label>
              <input type="text" class="form-control" id="wp_firstname" value="<?php echo $wp_firstname; ?>" placeholder="First Name" name="wp_firstname" readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="wp_email">Email</label>
              <input type="email" class="form-control" id="wp_email" value="<?php echo $wp_email; ?>" placeholder="example@email.com" name="wp_email" readonly>
            </div>
          </div>
        </div>
        <div class="well well-sm">Privacy Statement</div>
        <div class="checkbox">
          <label><input type="checkbox" id="myCheck" name="test" required>I hereby certify that the above information given are true and correct to the best of my knowledge and I allow the Department of Education to use my child’s details to create and/or update his/her learner profile in the School Information System. The information herein shall be treated as confidential in compliance with the Data Privacy Act of 2012.</label>
        </div>
        <button type="submit" class="btn btn-default">Submit Reservation Request</button>
      </form>
    </section>
  </div>
</body>
<?php
	$conn->close();
?>
</html>

