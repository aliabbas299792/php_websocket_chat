/*

Ok, so the worst case scenario actually happened......

Now everything you worked on is gone, so we gotta restart:

1) Format the text response and print thing how I wanted, use old channel script.js as well
2) One AJAX call to save the messages in the database, with the other one looking for session variables inside of that, and then the websocket send msg inside of that
3) One other AJAX call thing for setting up the rooms and stuff

//Resolved
*/

var msgContainer = document.getElementById('messageContainer');
var inputField = document.getElementById('messageInputToSend');
var websocket;
var navBar = document.getElementsByTagName('nav')[0];
var inputHolder = document.getElementById('inputHolder');

inputField.onkeyup = function(event){
	if(event.keyCode === 13){
		doSend(inputField.value);
	}
}

document.onresize = function(){
	msgContainer.style.height = window.innerHeight-inputHolder.offsetHeight-navBar.offsetHeight;	
};

window.onload = function WebSocketSupport()
{
	msgContainer.style.marginTop = navBar.offsetHeight;
	msgContainer.style.height = window.innerHeight-inputHolder.offsetHeight-navBar.offsetHeight;	
	
    if (browserSupportsWebSockets() === false) {
        document.write = "<h1>Sorry! Your web browser does not supports web sockets</h1>";

        return;
    }

    websocket = new WebSocket('ws:127.0.0.1:999');

    websocket.onopen = function(e) {
		    var ajaxGet = new XMLHttpRequest();

			ajaxGet.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					appendMessage(this.responseText, msgContainer);
					
					//////////////////////////////////////////////
					
					var ajaxGet = new XMLHttpRequest();

					ajaxGet.onreadystatechange = function(){
						if(this.readyState == 4 && this.status == 200){
							
							var returningJSON = JSON.parse(this.responseText);
								
							returningJSON.settingRooms = true;
							
							returningJSON = JSON.stringify(returningJSON);
							
							websocket.send(returningJSON);
								
						}
					}

					ajaxGet.open("POST", "ajax_reqs/getSessionVariables.php", true);
					ajaxGet.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					ajaxGet.send("");
							
					//////////////////////////////////////////////
					
				}
			}

			ajaxGet.open("POST", "ajax_reqs/getMessagesOnLoad.php", true);
			ajaxGet.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxGet.send("");
    };


    websocket.onmessage = function(e) {
        onMessage(e.data)
    };

    websocket.onerror = function(e) {
        onError(e.data)
    };
}

function appendMessage(input, msgContainer){
    msgContainer.innerHTML += input;
    msgContainer.scrollTop = msgContainer.scrollHeight;
}

function onMessage(e) {
	var ajaxGet = new XMLHttpRequest()
	var channel = "";
	var subchannel = "";
				
	ajaxGet.onreadystatechange = function(){ //get session vars
		if(this.readyState == 4 && this.status == 200){
			var responseJSON = JSON.parse(this.responseText);
					
			channel = responseJSON.channelName;
			subchannel = responseJSON.subChannelName;
		}
	}

	ajaxGet.open("POST", "ajax_reqs/getSessionVariables.php", true);
	ajaxGet.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxGet.send();
	
	//console.log(e.includes(channel) + " " + e.includes(subchannel));
	
	if(e.includes(channel) && e.includes(subchannel)){
		appendMessage(e, msgContainer);
	}
}

function onError(e) {
    appendMessage('<span style="color: red;">ERROR:</span> ' + e.data, msgContainer);
}

function doSend(message) {

    inputField.value = "";
	
	var ajaxGet = new XMLHttpRequest();

    ajaxGet.onreadystatechange = function(){ //gets the message date time and id
        if(this.readyState == 4 && this.status == 200){
			//////////////////////////////////////////////////
			var responseJSON = JSON.parse(this.responseText);
			
			var msgDateTime = responseJSON.dateTime;
			var msgID = responseJSON.id;
			
			var ajaxGet = new XMLHttpRequest();

			ajaxGet.onreadystatechange = function(){ //get session vars
				if(this.readyState == 4 && this.status == 200){
					var responseJSON = JSON.parse(this.responseText);
					
					responseJSON.time = msgDateTime;
					responseJSON.messageID = msgID;
					responseJSON.settingRooms = false;
					responseJSON.message = message;
					
					//console.log(responseJSON.color);
					
					writeToScreen(message, msgID, responseJSON.username, msgDateTime, responseJSON.color);
					
					responseJSON = JSON.stringify(responseJSON);
					
					websocket.send(responseJSON);
				}
			}

			ajaxGet.open("POST", "ajax_reqs/getSessionVariables.php", true);
			ajaxGet.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxGet.send(message);

			//////////////////////////////////////////////////
        }
    }

    ajaxGet.open("POST", "ajax_reqs/save_messages.php", true);
    ajaxGet.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajaxGet.send("message="+message);
}

function writeToScreen(message, messageID, username, dateTime, color) {
	var messageFormatted = "<div class='message_container'><div class='message' id='"+messageID+"'><span class='username' style='color:"+color+"'>"+username+"</span><br> <span class='message_content'>"+message+"</span><br><span class='dateTime'>"+dateTime+"</span></div></div>";
    msgContainer.innerHTML += messageFormatted;
    msgContainer.scrollTop = msgContainer.scrollHeight;
}

function browserSupportsWebSockets() {
    if ("WebSocket" in window)
    {
        return true;
    }
    else
    {
        return false;
    }
}
