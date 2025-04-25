<?php
include('enrollment_config.php');
// include('../wp-load.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// $wp = wp_get_current_user();
// $wp_id = $wp->ID;
// $wp_email = $wp->user_email;
// $wp_firstname = $wp->first_name;
// $wp_lastname = $wp->last_name;
// $wp_id=$_POST['wp_id'];
//$books = $_POST['books'];
$books = 'Y';
$wp_email=$_POST['wp_email'];
$wp_firstname=$_POST['wp_firstname'];
$wp_lastname=$_POST['wp_lastname'];
$review=$_POST['review'];
$enrollee_id=$_POST['id'];
$sy = $_POST['sy'];
$wlrn = $_POST['wlrn'];
$lrn = $_POST['lrn'];
$psa = $_POST['psa'];
$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$extname = $_POST['extname'];
$birth = $_POST['birth'];
$age = $_POST['age'];
$sex = $_POST['sex'];
$ip = $_POST['ip'];
$iptext = $_POST['iptext'];
$mt = $_POST['mt'];
$add1 = $_POST['add1'];
$add2 = $_POST['add2'];
$add3 = $_POST['add3'];
$zip = $_POST['zip'];
$father = $_POST['father'];
$mother = $_POST['mother'];
$guardian = $_POST['guardian'];
$phone1 = $_POST['phone1'];
$phone2 = $_POST['phone2'];
$phone3 = $_POST['phone3'];
$lastgradelevel = $_POST['lastgradelevel'];
$lastSYcompleted = $_POST['lastSYcompleted'];
$schoolname = $_POST['schoolname'];
$schoolid = $_POST['schoolid'];
$schooladd = $_POST['schooladd'];
$semester = $_POST['semester'];
$track = $_POST['track'];
$strand = $_POST['strand'];
$level = $_POST['level'];
$terms = $_POST['terms'];
$vax = $_POST['vax'];
$vax1 = $_POST['vax1'];
if($vax1==''){
  $vax1='1900-01-01';
}
$vax2 = $_POST['vax2'];
if($vax2==''){
  $vax2='1900-01-01';
}
$enrollee="UPDATE enrollment_list SET
                  wp_email='$wp_email',
                  wp_firstname='$wp_firstname',
                  wp_lastname='$wp_lastname',
                  sy='$sy',
                  wlrn=$wlrn,
                  lrn='$lrn',
                  psa='$psa',
                  lastname='$lastname',
                  firstname='$firstname',
                  middlename='$middlename',
                  extname='$extname',
                  birth='$birth',
                  age='$age',
                  sex='$sex',
                  ip='$ip',
                  iptext='$iptext',
                  mt='$mt',
                  add1='$add1',
                  add2='$add2',
                  add3='$add3',
                  zip='$zip',
                  father='$father',
                  mother='$mother',
                  guardian='$guardian',
                  phone1='$phone1',
                  phone2='$phone2',
                  phone3='$phone3',
                  lastgradelevel='$lastgradelevel',
                  lastSYcompleted='$lastSYcompleted',
                  schoolname='$schoolname',
                  schoolid='$schoolid',
                  schooladd='$schooladd',
                  semester='$semester',
                  track='$track',
                  strand='$strand',
                  level=$level,
                  terms=$terms,
                  books='$books',
                  vax='$vax',
                  vax1='$vax1',
                  vax2='$vax2'
                  WHERE id=$enrollee_id";
if ($conn->query($enrollee) === TRUE) {
  // echo "New record updated successfully";
  if($review==0){
    header("Location: enrollment_list.php?message=4");
  }
  if($review==1){
    header("Location: files_list.php?message=0");
  }
} else {
  echo "Error: " . $enrollee . "<br>" . $conn->error;
}
?>
