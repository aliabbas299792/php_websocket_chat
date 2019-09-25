<?php
include "../include/db.info.php";

$messages = "";

$pdo = new PDO("mysql:host=".$dbAddress.";dbname=".$dbName."", $dbUsername, $dbPass);

$channelName = $_SESSION['channel_name'];
$subChannelName = $_SESSION['sub_channel_name'];

$statement = $pdo->prepare("SELECT * FROM messages WHERE channel_name=:channelName AND subChannel_name=:subChannel_name ORDER BY id desc LIMIT 200");
$statement->bindParam(':channelName',$channelName);
$statement->bindParam(':subChannel_name',$subChannelName);
$statement->execute();

foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $userId = $row['user_id'];

    $stmt = $pdo->prepare("SELECT username FROM users WHERE id=:id");
    $stmt->bindParam(':id',$userId);
    $stmt->execute();
    $username = hex2bin(openssl_decrypt($stmt->fetch(PDO::FETCH_ASSOC)['username'], $method, $passcode))."#".$userId; //retrieve, decrypt, back to text

    $message = $row['message_content'];
    $message_id = $row['id'];
	$color = $row['color'];
    $dateTime = $row['time'];
    $messages =
        "
        <div class='message_container'>
            <div class='message' id='$message_id'>
                <span class='username' style='color:$color'>$username</span>
                <br> 
                <span class='message_content'>$message</span>
                <br>
                <span class='dateTime'>$dateTime</span>
            </div>
        </div>
        "
        .$messages;
}

echo $messages;