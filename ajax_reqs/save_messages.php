<?php
include '../include/db.info.php';
include '../include/userValidate.php';

$user_id = $_SESSION['id'];
$channel = $_SESSION['channel_name'];
$sub_channel = $_SESSION['sub_channel_name'];
$color = $_SESSION['color'];
$message = $_POST['message'];

$pdo = new PDO("mysql:host=".$dbAddress.";dbname=".$dbName."", $dbUsername, $dbPass);

$stmt = $pdo->prepare('INSERT INTO messages (channel_name, subChannel_name, user_id, message_content,color) VALUES (:channel_name, :subChannel_name, :user_id, :message_content,:color)');
$stmt->bindParam(':channel_name', $channel);
$stmt->bindParam(':subChannel_name', $sub_channel);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':message_content', $message);
$stmt->bindParam(':color', $color);

$stmt->execute();

$messageID = $pdo->prepare("SELECT id, time FROM messages WHERE subChannel_name=:subChannel_name AND channel_name=:channel_name AND user_id=:user_id AND message_content=:message_content ORDER BY id desc LIMIT 1");
$messageID->bindParam(':subChannel_name',$sub_channel);
$messageID->bindParam(':channel_name',$channel);
$messageID->bindParam(':user_id',$user_id);
$messageID->bindParam(':message_content',$message);
$messageID->execute();

$row = $messageID->fetch(PDO::FETCH_ASSOC);

$messageVars = new \StdClass();

$messageVars->dateTime = $row['time'];
$messageVars->id = $row['id'];

$messageVar = json_encode($messageVars);

echo $messageVar;
?>