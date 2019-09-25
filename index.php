<?php
error_reporting(E_ALL ^ E_NOTICE);

session_start();

include 'include/db.info.php';

if(!($_SESSION['username'] == "" || $_SESSION['id'] == "" || $_SESSION['password'] == "" || $_SESSION['email'] == "" || !isset($_SESSION['username']) || !isset($_SESSION['id']) || !isset($_SESSION['password'])  || !isset($_SESSION['email']))){
    $pdo = new PDO("mysql:host=".$dbAddress.";port=3306;dbname=".$dbName."", $dbUsername, $dbPass);
    $id = filter_var($_SESSION['id'],FILTER_SANITIZE_NUMBER_INT);
    $originalPassword = $_SESSION['password'];
	$emailOriginal = $_SESSION['email'];
    $validate = $pdo->prepare("SELECT * FROM users WHERE id=:id");
    $validate->bindParam(':id',$id);
    $validate->execute();
	
	$row = $validate->fetch(PDO::FETCH_ASSOC);
	
	$password = $row['password'];
	$username = hex2bin(openssl_decrypt($row['username'], $method, $passcode));
	$originalUsername = $_SESSION['username'];
		
	if($originalPassword == $password && $username == $originalUsername){
		header('location: channels.php');
	}
}

?>
<html>
    <head>
        <title>Erewhon</title>
		<meta name="mobile-web-app-capable" content="yes">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/styles.css" type="text/css">
		<link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <div id="background">
            <video autoplay muted loop>
                <source src="videos/background.mp4" type="video/mp4">
            </video>
        </div>
        
        <div class="background-overlay"></div>

        <div id="welcome">
            <h1>Welcome to Erewhon</h1>
            <button class="welcome_btns" id="login">Login</button>
            <button class="welcome_btns" id="sign_up">Sign Up</button>
        </div>

        <div id="loginBox" class="dropDown">
            <span id="loginFailed">Login failed. Try again.</span>
            <button id="closeDropDown_login">Close</button>
            <div class="mainBox">
                    <h1>Login</h1>
                    <input type="email" id="emailInput" placeholder="Email">
                    <input type="password" id="passwordInput" placeholder="Password">
                    <button id="loginReq_btn">Login</button>
            </div>
        </div>
		
		<div id="signUpBox" class="dropDown">
            <span id="signUpFailed">Sign Up failed. Try again.</span>
            <span id="signUpSuccess">Sign Up succeeded. Check your E-mails.</span>
            <button id="closeDropDown_signUp">Close</button>
            <div class="mainBox">
                    <h1>Sign Up</h1>
                    <input type="text" autocomplete="new-password" id="usernameInput" placeholder="Username">
                    <input type="email" autocomplete="off" id="emailInput_signUp" placeholder="Email">
                    <input type="password" id="passwordInput_signUp" placeholder="Password">
                    <input type="password" id="passwordInputRepeat" placeholder="Repeat the password">
                    <button id="signUpReq_btn">Sign Up</button>
            </div>
        </div>

        <script src="scripts/welcome_script.js"></script>
    </body>
</html>