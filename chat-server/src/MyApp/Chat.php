<?php
namespace MyApp;
header("Content-Type: application/json; charset=UTF-8");


//include "../../db.info.php";


use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
	

    protected $clients;
	
	protected $clientInChannel = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Congratulations! the server is now running\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
		$GLOBALS['clientInChannel'][$conn->resourceId]['channel'] = "";
		$GLOBALS['clientInChannel'][$conn->resourceId]['sub_channel'] = "";

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
		
        $numRecv = count($this->clients) - 1;
				
		$sessionVars = json_decode($msg, false);
		
		
					echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
							, $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
		
		if($sessionVars->settingRooms == true){
			$GLOBALS['clientInChannel'][$from->resourceId]['channel'] = $sessionVars->channelName;
			$GLOBALS['clientInChannel'][$from->resourceId]['sub_channel'] = $sessionVars->subChannelName;
		}else{
			$senderChannel = $GLOBALS['clientInChannel'][$from->resourceId]['channel'];
			$senderSubChannel = $GLOBALS['clientInChannel'][$from->resourceId]['sub_channel'];
			
			$msg =
			"
			<div class='message_container' data-channel='$senderChannel' data-subChannel='$senderSubChannel'>
				<div class='message' id='{$sessionVars->messageID}'>
					<span class='username' style='color:{$sessionVars->color}'>{$sessionVars->username}</span>
					<br> 
					<span class='message_content'>{$sessionVars->message}</span>
					<br>
					<span class='dateTime'>{$sessionVars->time}</span>
				</div>
			</div>
			";
			
			
			foreach ($this->clients as $client) {
				$clientChannel = $GLOBALS['clientInChannel'][$client->resourceId]['channel'];
				$clientSubChannel = $GLOBALS['clientInChannel'][$client->resourceId]['sub_channel'];
				
				if ($from !== $client && $senderChannel == $clientChannel && $senderSubChannel == $clientSubChannel) {
					// The sender is not the receiver, send to each client connected
					$client->send($msg);
				}
			}
		}
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}
