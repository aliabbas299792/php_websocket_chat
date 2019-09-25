<?php
include '../include/db.info.php';

$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);

/*
$email = filter_var($_GET["email"], FILTER_SANITIZE_EMAIL);
$password = bin2hex($_GET["password"]);
*/

$pdo = new PDO("mysql:host=".$dbAddress.";port=3306;dbname=".$dbName."", $dbUsername, $dbPass);

$emailInput = $email;
$email = openssl_encrypt($email, $method, $passcode); //encrypt

//following checks if a user with the same email address already exists
$checkEmail = $pdo->prepare("SELECT username, id, password, color, pfpImg FROM users WHERE email=:email");
$checkEmail->bindParam(':email',$email);
$checkEmail->execute();
$row = $checkEmail->fetch(PDO::FETCH_ASSOC);


if($row['username'] != "" && password_verify($password, $row['password'])){
    $_SESSION['username'] = hex2bin(openssl_decrypt($row['username'], $method, $passcode));
	$_SESSION['email'] = $emailInput;
    $_SESSION['id'] = $row['id'];
    $_SESSION['password'] = $row['password'];
	$_SESSION['color'] = $row['color'];
	$_SESSION['pfpImg'] = $row['pfpImg'];

    echo true;
}else{
    echo false;
}