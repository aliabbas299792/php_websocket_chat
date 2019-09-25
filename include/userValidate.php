<?php
if($_SESSION['username'] == "" || $_SESSION['id'] == "" || $_SESSION['password'] == "" || $_SESSION['email'] == "" || !isset($_SESSION['username']) || !isset($_SESSION['id']) || !isset($_SESSION['password'])  || !isset($_SESSION['email'])){
    header("location: index.php");
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
		
	if($originalPassword != $password){
		header('location: index.php');
	}
	
    if($username != $originalUsername){
        header('location: index.php');
    }
}
?>