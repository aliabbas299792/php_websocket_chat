<nav>
	<a id='channelBtn' href="channels.php">Channels</a>
	
	<script>document.getElementsByTagName("nav")[0].style.height = document.getElementsByTagName("nav")[0].offsetHeight;</script>
	
	<span id='userProfile'>
		<span id='usernameIDholder'>
			<span><?php echo $_SESSION['username']; ?></span>
			<br>
			<span>#<?php echo $_SESSION['id']; ?></span>
		</span>
	
		<img src="<?php echo $_SESSION['pfpImg']; ?>">
	</span>
	
	<span id="settings">
		<img src="https://res.cloudinary.com/deylrqt2d/image/upload/v1532020970/settings_kwal5g.png">
	</span>
	
	<a href="logout.php" id="logout">
		<img src="https://res.cloudinary.com/deylrqt2d/image/upload/v1532020970/logout_xtkdkk.png">
	</a>
</nav>
<section id="settingsBox">
	<span id="saveFailed">Saving failed, try again.</span>
	<article>
		<h2>Settings</h2>
		<h3>Change account details:</h3>
		<table>
			<tr>
				<td>Username: </td><td><input id="username" type="text" placeholder="<?php echo $_SESSION['username']; ?>"></td>
			</tr>	
			<tr>
				<td>Email address: </td><td><input id="email" type="email" placeholder="<?php echo $_SESSION['email']; ?>"></td>
			</tr>
			<tr>
				<td>New password: </td><td><input id="newPass" type="password"></td>
			</tr>
			<tr>
				<td>Repeat new password: </td><td><input id="newPassRepeat" type="password"></td>
			</tr>
		</table>
		<button id="saveSettings" style='border-radius:5px;'>Save changes</button>
	</article>
    <button id="closeDropDown_settings">Close</button>
</section>
<script>
var settingsBtn = document.getElementById('settings');
var settingsBox = document.getElementById('settingsBox');
var closeSettingsBox = document.getElementById('closeDropDown_settings');
var saveSettings = document.getElementById('saveSettings');
var inputs = [document.getElementById('username'), document.getElementById('email'), document.getElementById('newPass'), document.getElementById('newPassRepeat')];
var saveFailed = document.getElementById('saveFailed');

saveSettings.onclick = function(){
	if(inputs[0].value != "" || inputs[1].value != "" || inputs[2].value != "" || inputs[3].value != ""){
		if(inputs[2].value == inputs[3].value){
			var ajaxGet = new XMLHttpRequest();
						
			ajaxGet.onreadystatechange = function(){ //get session vars
				if(this.readyState == 4 && this.status == 200){
					if(this.response == true){
						location.href = "/";
					}else{
						saveFailed.style.opacity = 1;
						saveFailed.style.top = "20px";

						setTimeout(function(){saveFailed.style = "";},1000)
					}
				}
			}

			ajaxGet.open("POST", "ajax_reqs/saveSettings.php", true);
			ajaxGet.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxGet.send("username="+inputs[0].value+"&email="+inputs[1].value+"&password="+inputs[2].value+"&passwordRep="+inputs[3].value);
		}else{
			saveFailed.style.opacity = 1;
			saveFailed.style.top = "20px";

			setTimeout(function(){saveFailed.style = "";},1000);
		}
	}else{
		saveFailed.style.opacity = 1;
		saveFailed.style.top = "20px";

		setTimeout(function(){saveFailed.style = "";},1000);
	}
}

settingsBtn.onclick = function(){
	settingsBox.style = "z-index:110;";
	setTimeout(function(){
		settingsBox.style.top = 0;
		settingsBox.style.opacity = 1;
	}, 50);
}

closeSettingsBox.onclick = function(){
	settingsBox.style = "top:-30%;opacity:0;z-index:110;";
	
	setTimeout(function(){
		settingsBox.style = "top:-30%;opacity:0;";
	}, 260);
}
</script>