<?php
include 'include/db.info.php';

if(!isset($_GET['token']) || !isset($_GET['email'])){
	header('location: /');
}

$token = filter_var($_GET['token'], FILTER_VALIDATE_INT);
$email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

$pdo = new PDO("mysql:host=".$dbAddress.";port=3306;dbname=".$dbName."", $dbUsername, $dbPass);

$rand_valCheck = $pdo->prepare("SELECT * FROM temp_user WHERE email=:email");
$rand_valCheck->bindParam(':email', $email);
$rand_valCheck->execute();

if($rand_valCheck->rowCount() <= 0){
	header('location: /');
}

$row = $rand_valCheck->fetch(PDO::FETCH_ASSOC);

$deleteTemp = $pdo->prepare("DELETE FROM temp_user WHERE email=:email");
$deleteTemp->bindParam(':email', $email);
$deleteTemp->execute();

if($row['rand_val'] == $token){
	$username = openssl_encrypt(bin2hex($row['username']), $method, $passcode);
	$password = password_hash($row['password'], PASSWORD_DEFAULT);
	$email = openssl_encrypt($email, $method, $passcode);
	
	$insertVals = $pdo->prepare("INSERT INTO users (`username`, `password`, `email`) VALUES (:username, :password, :email)");
	$insertVals->bindParam(':username', $username);
	$insertVals->bindParam(':email', $email);
	$insertVals->bindParam(':password', $password);
	$insertVals->execute();
		
	#echo "INSERT INTO users (`username`, `password`, `email`) VALUES ('$username', '$password', '$email')";
		
	echo '
	<html>
		<head>
			<title>Erewhon</title>
			<meta charset="UTF-8">
			<link rel="stylesheet" href="css/styles.css" type="text/css">
		</head>
		<body>
			<div id="background">
				<video autoplay muted loop>
					<source src="videos/background.mp4" type="video/mp4">
				</video>
			</div>
			
			<div class="background-overlay"></div>

			<div id="welcome">
				<h1>Registration successful</h1>
				<h3>You may now proceed to login from the home page</h3>
			</div>
		</body>
	</html>
	';
	
}else{
	
	echo '
	<html>
		<head>
			<title>Erewhon</title>
			<meta charset="UTF-8">
			<link rel="stylesheet" href="css/styles.css" type="text/css">
		</head>
		<body>
			<div id="background">
				<video autoplay muted loop>
					<source src="videos/background.mp4" type="video/mp4">
				</video>
			</div>
			
			<div class="background-overlay"></div>

			<div id="welcome">
				<h1>Registration unsuccessful</h1>
				<h3>Try signing up again</h3>
			</div>
		</body>
	</html>
	';
}
?>