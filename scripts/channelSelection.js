var container = document.getElementById('channelSelection');
var response;
var elementsID;
var ajaxReq;

function setSession(event){
	elementsID = event.target.id;
	
	ajaxReq = new XMLHttpRequest();
	
	ajaxReq.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
			response = this.response.split("%%%");
			
			//console.log(response[0].trim() == "Streaming Channel");
			
			if(response[0].trim() == "Streaming Channel"){
				location.href = 'streaming_channel.php';
			}else{
				location.href = 'channels.php?channel='+response[0]+'&subchannel='+response[1];
			}
        }
    }

    ajaxReq.open("POST", "ajax_reqs/channelSetter.php", true);
    ajaxReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajaxReq.send("channelID="+elementsID);
}

container.addEventListener('click',function(event){setSession(event);});

