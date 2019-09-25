<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpMailerSrc/Exception.php';
require '../phpMailerSrc/PHPMailer.php';
require '../phpMailerSrc/SMTP.php';

include '../include/db.info.php';

function sendMail($to, $name, $url){
	$smtpUsername = "insertyouremail.com";
	$smtpPassword = "pass";
	
	$mail = new PHPMailer;
	
	//Set PHPMailer to use SMTP.
	$mail->isSMTP();            
	//Set SMTP host name                          
	$mail->Host = "smtp.gmail.com";
	//Set this to true if SMTP host requires authentication to send email
	$mail->SMTPAuth = true;   

	$mail->Host = 'tls://smtp.gmail.com'; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
	$mail->Port = 587; // TLS only
	$mail->SMTPSecure = 'tls'; // ssl is depracated

	$mail->Username = $smtpUsername;
	$mail->Password = $smtpPassword;

	$mail->From = "insertyouremail.com";
	$mail->FromName = "Erewhon";

	$mail->addAddress($to, $name); //recipient details

	$mail->isHTML(true);
	$mail->Subject = 'Welcome to Erewhon';
	$mail->msgHTML("<h1>Welcome, $name</h1><br><a href='www.site.xyz/validate_account.php?$url'>Click here to validate your account.</a>"); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
	$mail->AltBody = "Click on this link to validate your account, $name: www.site.xyz/validate_account?$url";
	// $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file

	$mail->send();
}

$username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);

$pdo = new PDO("mysql:host=".$dbAddress.";port=3306;dbname=".$dbName."", $dbUsername, $dbPass);

//checks if email already used
$checkEmailTemp = $pdo->prepare("SELECT username FROM temp_user WHERE email=:email");
$checkEmailTemp->bindParam(':email', $email);
$checkEmailTemp->execute();

if($username == "" || $email == "" || $password == "" || strlen($email) > 60 || strlen($password) > 60 || strlen($username) > 60){
	echo "false";
}else{
	if($checkEmailTemp->rowCount() > 0){
		echo "false";
	}else{
		$checkEmailUsers = $pdo->prepare("SELECT username FROM users WHERE email=:email");
		$checkEmailUsers->bindParam(':email', $email);
		$checkEmailUsers->execute();

		if($checkEmailUsers->rowCount() > 0){
			echo "false";
		}else{
			
			$url = random_int( 100000 , 1000000 );
			
			$insertIntoTempUsers = $pdo->prepare("INSERT INTO temp_user (`username`, `email`, `password`, `rand_val`) VALUES (:username, :email, :password, :url)");
			$insertIntoTempUsers->bindParam(':email',$email);
			$insertIntoTempUsers->bindParam(':username',$username);
			$insertIntoTempUsers->bindParam(':password',$password);
			$insertIntoTempUsers->bindParam(':url',$url);
			$insertIntoTempUsers->execute();
			
			sendMail($email, $username, "token=$url&email=$email");
			
			echo "true";
		}
	}
}
?>