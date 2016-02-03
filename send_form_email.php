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
$mail->Username = "USERNAME";  //username
$mail->Password = "PASSWORD";  //password
  
 
     
 
    function died($error) {
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
        echo "These errors appear below.<br /><br />"; 
        echo $error."<br /><br />"; 
        echo "Please go back and fix these errors.<br /><br />";
        die();
     }

    if(!isset($_POST['first_name']) ||
       !isset($_POST['last_name']) ||
       !isset($_POST['email']) ||
       !isset($_POST['contents'])) {
       died('We are sorry, but there appears to be a problem with the form you submitted.');       
    }
 
    $first_name = $_POST['first_name']; // required
    $last_name = $_POST['last_name']; // required
    $email_from = $_POST['email']; // required
    $contents = $_POST['contents']; // required
     
 
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
  if(!preg_match($email_exp,$email_from)) {
    $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
  }
    $string_exp = "/^[A-Za-z .'-]+$/";
  if(!preg_match($string_exp,$first_name)) {
    $error_message .= 'The First Name you entered does not appear to be valid.<br />';
  }
  if(!preg_match($string_exp,$last_name)) {
    $error_message .= 'The Last Name you entered does not appear to be valid.<br />';
  }
  if(strlen($contents) < 2) {
    $error_message .= 'The contents you entered do not appear to be valid.<br />';
  }
  if(strlen($error_message) > 0) {
    died($error_message);
  }
 
    $email_message = "Form details below.\n\n";
 
     
 
    function clean_string($string) {
       $bad = array("content-type","bcc:","to:","cc:","href");
       return str_replace($bad,"",$string);
     }
 
    $email_message .= "First Name: ".clean_string($first_name)."\n";
    $email_message .= "Last Name: ".clean_string($last_name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
    $email_message .= "contents: ".clean_string($contents)."\n";

 
$mail->setFrom('godlytalias@yahoo.com', '');
$mail->addAddress($email_from, $first_name);
$mail->Subject = $contents;
$mail->msgHTML(file_get_contents('emailform.html'), dirname(__FILE__));
$mail->AltBody = 'This is a plain-text message body';

	for ($x = 0; $x < 10; $x++) {
		$mail->addAddress($email_from, $first_name);
		//send the message, check for errors
		if (!$mail->send()) {
 		   echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
  		  echo "Message sent!";
		}
	}
 
?>
