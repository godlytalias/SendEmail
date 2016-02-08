<?php
date_default_timezone_set('Etc/UTC');

require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 3;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.mail.yahoo.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "username";  //username
$mail->Password = "password";  //password

$from_mail = "from_mail";
$from_name = "from_name";
$test_mail_id = "test_mail_id";
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "db_test";
$tablename = "maildb";
     
 
function died($error) {
	echo $error."<br /><br />"; 
	echo "Please go back and fix these errors.<br /><br />";
	die();
}

if(!isset($_POST['content'])) {
	died('Incomplete contents');       
}
if(!isset($_POST['subject'])) {
	died('Subject field is empty.');       
}

$contents = $_POST['content']; // required
$subject = $_POST['subject'];

$error_message = "";

if(strlen($contents) < 2) {
	$error_message .= 'The contents you entered do not appear to be valid.<br />';
}
if(strlen($error_message) > 0) {
	died($error_message);
}
 
$mail->setFrom($from_mail, $from_name);
$mail->Subject = $subject;
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';


if ($_POST['testing'] == "testing")
{
	$name = "Tester";
	$email = $test_mail_id;
	$mailbody = "<p>Hi ". $name . ", </p>" . $contents;
	$mail->msgHTML($mailbody);
	$mail->addAddress($email, $name);
	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
	$mail->clearAddresses();
}
else
{
	// Create connection
	$conn = new mysqli($servername, $username, $password);
	mysqli_select_db($conn, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Database Connection failed: " . $conn->connect_error);
	} 
	echo "Database Connected successfully";

	$sql = "SELECT firstname, email FROM " . $tablename;
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$name = $row['firstname'];
			$email = $row['email'];
			if (strlen($name) < 1) $name = "Friend";
			$mailbody = "<p>Hi ". $name . ", </p>" . $contents;
			$mail->msgHTML($mailbody);
			$mail->addAddress($email, $name);
			//send the message, check for errors
			if (!$mail->send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "Message sent!";
			}
			$mail->clearAddresses();
		}
	} else {
		echo "NO MAILS FOUND";
	}
}
$conn->close();
?>
