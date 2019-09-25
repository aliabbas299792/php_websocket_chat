<?php
//$email = bin2hex(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
//$password = bin2hex($_POST["password"]);

$passcode = 123456789;
$method = "AES-128-ECB";
$row = [];

$email = "sammar@sammar.com";
$username = "Sammar";
$password = "sammar123";

$username = bin2hex($username);  //hex

$email = openssl_encrypt($email, $method, $passcode); //encrypt
$username = openssl_encrypt($username, $method, $passcode); //encrypt
$originalPassword = $password;
$password = password_hash($password, PASSWORD_DEFAULT);

echo $email."<br>";
echo $username."<br>";
echo $password."<br>";

$pdo = new PDO("mysql:host=127.0.0.1;dbname=erewhon", "root", "");


//following checks if a user with the same email address already exists
$checkEmail = $pdo->prepare("SELECT username FROM users WHERE email=:email");
$checkEmail->bindParam(':email',$email);
$checkEmail->execute();
$row = $checkEmail->fetch(PDO::FETCH_ASSOC);

//this is the conditional statement; if the users email isn't used, insert into table, otherwise ignore
if($row['username'] == "") {

    $stmt = $pdo->prepare('INSERT INTO users (email, username, password) VALUES (:email, :username, :password)');
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    $stmt->execute();

    $statement = $pdo->query("SELECT * FROM users");

    foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $email = $row['email'];
        $username = $row['username'];
        $password = $row['password'];

        $email = openssl_decrypt($email, $method, $passcode); //encrypt
        $username = openssl_decrypt($username, $method, $passcode); //encrypt

        $username = hex2bin($username);

        echo "<br>";
        echo $email . "<br>";
        echo $username . "<br>";
		
		if(password_verify($originalPassword, $password)){
			echo "true tbh";
		}else{
			echo "false tbh";
		}
    }
}

//if ever need testing again