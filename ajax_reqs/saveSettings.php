<?php
include "../include/db.info.php";

$usernameUpdate = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
$usernameOG = $usernameUpdate;
$emailUpdate = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$emailOG = $emailUpdate;
$passwordUpdate = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);
$passwordRep = filter_var($_POST["passwordRep"], FILTER_SANITIZE_SPECIAL_CHARS);

if($_SESSION['username'] == "" || $_SESSION['id'] == "" || $_SESSION['password'] == "" || $_SESSION['email'] == "" || !isset($_SESSION['username']) || !isset($_SESSION['id']) || !isset($_SESSION['password'])  || !isset($_SESSION['email'])){
    echo "false";
}else{
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
	
    if($username != $originalUsername || $originalPassword != $password){
		echo "false";
    }else{
		if($usernameUpdate == ""){
			$usernameUpdate = $_SESSION['username'];
		}
		
		if($emailUpdate == ""){
			$emailUpdate = $_SESSION['email'];
		}
		
		if($passwordUpdate == ""){
			$passwordUpdate = $_SESSION['password'];
		}else{
			$passwordUpdate = password_hash($passwordUpdate, PASSWORD_DEFAULT);
		}
		
		#echo $usernameUpdate." ||||| ";
		#echo $emailUpdate;
		
		$usernameUpdate = openssl_encrypt(bin2hex($usernameUpdate), $method, $passcode);
		$emailUpdate = openssl_encrypt($emailUpdate, $method, $passcode);
		
		$updateVals = $pdo->prepare("UPDATE `users` SET `username`=:username,`email`=:email,`password`=:password WHERE id=:id");
		$updateVals->bindParam(':username', $usernameUpdate);
		$updateVals->bindParam(':password', $passwordUpdate);
		$updateVals->bindParam(':email', $emailUpdate);
		$updateVals->bindParam(':id', $id);
		$updateVals->execute();
		
		#echo $_SESSION['username']." <br>".$_SESSION['email']." <Br>".$_SESSION['password']."<br>";
		#echo "$emailUpdate <Br>$usernameUpdate <br>$passwordUpdate<br>";
		#echo "$emailOG <Br>$usernameOG <br>$passwordRep";
		
		$_SESSION['username'] = $usernameOG;
		$_SESSION['email'] = $emailOG;
		$_SESSION['password'] = $passwordRep;
		
		echo "true";
	}
}
?>