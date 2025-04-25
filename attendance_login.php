<?php
include('../wp-load.php');
$user_login = $_GET['log'];
$user_password = $_GET['pwd'];
$credentials = array(
  'user_login'    => $user_login,
  'user_password' => $user_password,
  'remember'      => false,
);

$result = wp_signon($credentials);
$json = json_encode($result,true);
//echo $json."<br>";
$errors = strpos($json,"errors");
//echo $errors;
if(!$errors){
  echo $json;
} else {
  echo "Login Failed";
}
?>