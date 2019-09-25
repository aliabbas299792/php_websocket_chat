<?php
include '../include/db.info.php';

$channelID = filter_var($_POST['channelID'], FILTER_VALIDATE_INT);

$pdo = new PDO("mysql:host=".$dbAddress.";dbname=".$dbName."", $dbUsername, $dbPass);

$subChannel = $pdo->prepare("SELECT name FROM subchannels WHERE main_channel=:id AND priority=0");
$subChannel->bindParam(':id',$channelID);
$subChannel->execute();
$row2 = $subChannel->fetch(PDO::FETCH_ASSOC);

$channelName = $pdo->prepare("SELECT `name` FROM `channels` WHERE id=:id");
$channelName->bindParam(':id',$channelID);
$channelName->execute();
$row = $channelName->fetch(PDO::FETCH_ASSOC);

echo $row['name']."%%%".$row2['name'];

?>