<?php

session_start();

$sessionVars = new \StdClass();

$username = $_SESSION['username'];
$channel_name = $_SESSION['channel_name'];
$sub_channel_name = $_SESSION['sub_channel_name'];
$color = $_SESSION['color'];

$sessionVars->color = $color;
$sessionVars->username = $username."#".$_SESSION['id'];
$sessionVars->channelName = $channel_name;
$sessionVars->subChannelName = $sub_channel_name;

$jsonSessionVar = json_encode($sessionVars);

echo $jsonSessionVar;

?>