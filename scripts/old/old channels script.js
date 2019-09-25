/*
var textBox = document.getElementById("messageInputToSend");
var container = document.getElementById("messageContainer");

var host = 'ws://localhost:8080';
var socket = new WebSocket(host);
socket.onmessage = function(e) {
    appendMessage(e.data,container);
};

window.onbeforeunload = function(){
    socket.close();
}

function sendMessage(messageInput){
    var ajaxSend = new XMLHttpRequest();

    ajaxSend.open("POST", "save_messages.php", true);
    ajaxSend.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajaxSend.send("message="+messageInput);
}

function getMessageInput(element, event){
    if(event.keyCode == 13 && element.value != ""){
        sendMessage(element.value);
        element.value = "";
    }
}

function appendMessage(input, container){
    container.innerHTML += input;
    container.scrollTop = container.scrollHeight;
}

/*
function getMessagesOnLoad(container){
    var ajaxGet = new XMLHttpRequest();

    ajaxGet.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            appendMessage(this.responseText, container);

            setInterval(function(){getMessages(container);},200); // as soon as first messages found, check every .2s for more
        }
    }

    ajaxGet.open("POST", "getMessagesOnLoad.php", true);
    ajaxGet.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajaxGet.send("");
}

function getMessages(container){
    var id = container.lastElementChild.lastElementChild.id;

    var ajaxGet = new XMLHttpRequest();

    ajaxGet.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            if(this.responseText != ""){
                console.log(this.responseText);
                appendMessage(this.responseText, container);
            }
        }
    }

    ajaxGet.open("POST", "getMessages.php", true);
    ajaxGet.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajaxGet.send("id="+id);
}
*/

//textBox.addEventListener('keyup',function(event){getMessageInput(textBox, event);});