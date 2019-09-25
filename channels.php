<?php
include 'include/logged_in_head.php';
include 'include/navBar.php';

$pdo = new PDO("mysql:host=".$dbAddress.";dbname=".$dbName."", $dbUsername, $dbPass);

$subChannelArray = array();
$channelArray = array();

$subChannel = $pdo->prepare("SELECT name FROM subchannels");
$subChannel->bindParam(':id',$channelID);
$subChannel->execute();

while($row2 = $subChannel->fetch(PDO::FETCH_ASSOC)){
	array_push($subChannelArray, $row2['name']);
}

$channelName = $pdo->prepare("SELECT `name` FROM `channels`");
$channelName->bindParam(':id',$channelID);
$channelName->execute();

while($row = $channelName->fetch(PDO::FETCH_ASSOC)){
	array_push($channelArray, $row['name']);
}

if(!isset($_GET['channel']) || !isset($_GET['subchannel']) || $_GET['subchannel'] == "" || $_GET['channel'] == "" || !in_array($_GET['channel'], $channelArray) || !in_array($_GET['subchannel'], $subChannelArray)){
	$_SESSION['channel_name'] = "";
	$_SESSION['sub_channel_name'] = "";
	
	if($_GET['subchannel'] != "" || $_GET['channel'] != "" || isset($_GET['channel']) || isset($_GET['subchannel'])){
		header('location: channels.php');
	}
	
	$statement = $pdo->prepare("SELECT * FROM channels");
	$statement->execute();

	$tableRows = "";

	foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$tableRows .= "
					<div class='aPossibleSelection'>
						<div class='imageContainer'>
							<img src='".$row['icon']."'>
						</div>
						<div class='textContainer'>
							<span class='title'>".$row['name']."</span><br>
							<span class='desc'>".$row['description']."</span>
						</div>
						<div class='overlay'  id=".$row['id']."></div>
					</div>
					";
	}
	
	echo "
		<style>
		html{
			overflow-y:scroll;
		}
		</style>
		<body>
			<div id=\"channelSelection\">
				$tableRows
			</div>
			<script>document.getElementsByTagName(\"div\")[0].style.marginTop = document.getElementsByTagName(\"nav\")[0].offsetHeight;</script>
			<script src=\"scripts/channelSelection.js\"></script>
		</body>
	</html>";
}else{
	$_SESSION['channel_name'] = filter_var($_GET['channel'], FILTER_SANITIZE_STRING);
	$_SESSION['sub_channel_name'] = filter_var($_GET['subchannel'], FILTER_SANITIZE_STRING);

	echo "
		<body>
			<div id=\"chat_container\">
				<div id=\"messageContainer\"></div>
				<div id=\"inputHolder\">
<input type=\"text\" id=\"messageInputToSend\" placeholder=\"Message {$_SESSION['sub_channel_name']}...\"></input>
				</div>
			</div>
			<script src=\"scripts/channel_script.js\"></script>
		</body>
	</html>
	";
}
?>