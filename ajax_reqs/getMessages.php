<?php
include "../include/db.info.php";

$messages = "";

$pdo = new PDO("mysql:host=".$dbAddress.";dbname=".$dbName."", $dbUsername, $dbPass);

$channelName = $_SESSION['channel_name'];
$subChannelName = $_SESSION['sub_channel_name'];
$latest_id = $_POST['id'];

//echo $latest_id."---".$channelName."---".$subChannelName;

$statement = $pdo->prepare("SELECT * FROM messages WHERE channel_name=:channelName AND subChannel_name=:subChannel_name AND id>:id ORDER BY id desc LIMIT 200");
$statement->bindParam(':channelName',$channelName);
$statement->bindParam(':subChannel_name',$subChannelName);
$statement->bindParam(':id',$latest_id);
$statement->execute();

foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $userId = $row['user_id'];

    $stmt = $pdo->prepare("SELECT username FROM users WHERE id=:id");
    $stmt->bindParam(':id',$userId);
    $stmt->execute();
    $username = hex2bin(openssl_decrypt($stmt->fetch(PDO::FETCH_ASSOC)['username'], $method, $passcode)); //retrieve, decrypt, back to text

    $message = $row['message_content'];
    $message_id = $row['id'];
    $dateTime = $row['time'];
    $messages =
        "
        <div class='message_container'>
            <div class='message' id='$message_id'>
                <span class='username'>$username</span>
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