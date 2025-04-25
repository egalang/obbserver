<?php
	include('../../moodledata/enrollment_config.php');
	include('../wp-load.php');
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header('Location: http://reg.poc.8layertech.io');
		exit();
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&" . http_build_query($_POST));
	$response = curl_exec($ch);
	curl_close($ch);

	//file_put_contents(filename:"test.txt",$response);

	//if ($response == "VERIFIED" && $_POST['receiver_email'] == "erwing.geo-facilitator@yahoo.com") {
		$cEmail = $_POST['payer_email'];
		$name = $_POST['first_name'] . " " . $_POST['last_name'];

		$price = $_POST['mc_gross'];
		$currency = $_POST['mc_currency'];
		$item = $_POST['item_number'];
		$paymentStatus = $_POST['payment_status'];

$payment = "insert into payment_details(cEmail, name, paymentStatus) values ('$cEmail','$name','$paymentStatus')";
$conn->query($payment);

		// if ($item == "wordpressPlugin" && $currency == "USD" && $paymentStatus == "Completed" && $price == 67) {
		// 	$mail = new PHPMailer();
		//
		// 	$mail->setFrom("your-email@hotmail.com", "Sales");
		// 	$mail->addAttachment("attachment/wordpress-plugin.zip", "WordPress Plugin");
		// 	$mail->addAddress($cEmail, $name);
		// 	$mail->isHTML(true);
		// 	$mail->Subject = "Your Purchase Details";
		// 	$mail->Body = "
		// 		Hi, <br><br>
		// 		Thank you for purchase. In the attachment you will find my
		// 		Amazing WordPress Plugin.<br><br>
		//
		// 		Kind regards,
		// 		My Name
		// 	";
		//
		// 	$mail->send();
		// }
	//}












	$conn->close();
?>
