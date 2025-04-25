<?php
  include('header.php');
	if ( !is_user_logged_in() ) {
		header("Location: /index.php/login");
	}

  //get school data
  $settings="select * from school_settings";
  $settings_result = $conn->query($settings);
  $settings_row = $settings_result->fetch_assoc();

?>
</script>
</head>
<body class="dt-example dt-example-bootstrap">
  <div class="container">
		<section>
			<div class="demo-html"></div>
      <?php
      if(isset($_GET['message'])){
        if($_GET['message']==0){
          echo "<div class='alert alert-success'>";
          echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
          echo "<strong>Success!</strong> Settings updated.";
          echo "</div>";
        }
      }
      if(isset($_GET['message'])){
        if($_GET['message']==1){
          echo "<div class='alert alert-danger'>";
          echo "<a href=''#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
          echo "<strong>Alert!</strong> Something went wrong. Record not updated.";
          echo "</div>";
        }
      }
      ?>
      <form action="update_settings.php" method="post" enctype="multipart/form-data">
        <button type="submit" class="btn btn-info">Save</button><br><br>
        <div class="well well-sm">School Information</div>
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="id">School ID</label>
              <input type="text" class="form-control" id="id" name="id" value="<?php echo $settings_row['id']; ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="name">School Name</label>
              <input type="text" class="form-control" id="name" name="name" value="<?php echo $settings_row['name']; ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="domain">Domain</label>
              <input type="text" class="form-control" id="domain" name="domain" value="<?php echo $settings_row['domain']; ?>">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="sy">School Year</label>
              <select class="form-control" id="sy">
                <option value="2024-2025">2024-2025</option>
              </select>
              <!-- <input type="text" class="form-control" id="domain" name="domain" value="<?php echo $settings_row['domain']; ?>"> -->
            </div>
          </div>
          <!-- <div class="col-sm-5">
            <div class="form-group">
              <p><b>Check the appropriate box only.<b></p>
              <label class="radio-inline"><input type="radio" name="wlrn" value="1" <?php if($enrollee_row['wlrn']==1){ echo "checked"; } ?>>No LRN</label>
              <label class="radio-inline"><input type="radio" name="wlrn" value="2" <?php if($enrollee_row['wlrn']==2){ echo "checked"; } ?>>With LRN</label>
              <label class="radio-inline"><input type="radio" name="wlrn" value="3" <?php if($enrollee_row['wlrn']==3){ echo "checked"; } ?>>Returning (Balik-Aral)</label>
            </div>
          </div> -->
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="address">Address</label>
              <input type="text" class="form-control" id="address" name="address" value="<?php echo $settings_row['address']; ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="city">City</label>
              <input type="text" class="form-control" id="city" name="city" value="<?php echo $settings_row['city']; ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="contact">Contact Person</label>
              <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $settings_row['contact']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="position">Position</label>
              <input type="text" class="form-control" id="position" name="position" value="<?php echo $settings_row['position']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" id="email" name="email" value="<?php echo $settings_row['email']; ?>">
            </div>
          </div>
        </div>
	<div class="well well-sm">Additional Fee for Online Payments</div>
	<div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="service_fee">Additional Fee (%)</label>
              <input type="text" class="form-control" id="service_fee" name="service_fee" value="<?php echo $settings_row['service_fee']; ?>">
            </div>
          </div>
	</div>
        <div class="well well-sm">Mailing Templates</div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="enroll_message">Application for Enrollment Received Message</label>
			  <textarea class="form-control" rows="5" id="enroll_message" name="enroll_message"><?php echo $settings_row['enroll_message']; ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="request_message">Request for Clarification or Additional Information/Files</label>
			  <textarea class="form-control" rows="5" id="request_message" name="request_message"><?php echo $settings_row['request_message']; ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="accept_message">Acceptance Letter Message</label>
			  <textarea class="form-control" rows="5" id="accept_message" name="accept_message"><?php echo $settings_row['accept_message']; ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="soa_message">SOA Message</label>
			  <textarea class="form-control" rows="5" id="soa_message" name="soa_message"><?php echo $settings_row['soa_message']; ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="payment_received">Payment Received Message</label>
			  <textarea class="form-control" rows="5" id="payment_received" name="payment_received"><?php echo $settings_row['payment_received']; ?></textarea>
            </div>
          </div>
        </div>
        <div class="well well-sm">Additional Settings</div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="bc_series">Barcode Text</label>
              <input type="text" class="form-control" id="bc_series" name="bc_series" value="<?php echo $settings_row['bc_series']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="bc_digits">Barcode Digits</label>
              <input type="text" class="form-control" id="bc_digits" name="bc_digits" value="<?php echo $settings_row['bc_digits']; ?>">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <div class="form-group">
                <label for="bc_count">Barcode Count</label>
                <input type="text" class="form-control" id="bc_count" name="bc_count" value="<?php echo $settings_row['bc_count']; ?>">
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <p>Only JPG, JPEG, PNG & GIF files are allowed.  Maximum file size is 500 kilobytes.</p>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="fileToUpload">File Upload</label>
              <input type="file" name="fileToUpload" id="fileToUpload">
            </div>
          </div>
          <div class="col-sm-4">
            <img src="uploads/<?php echo $settings_row['logo']; ?>" width="50%"/>
          </div>
        </div>
        <button type="submit" class="btn btn-info">Save</button>
      </form>
    </section>
  </div>
</body>
<?php
	$conn->close();
?>
</html>
