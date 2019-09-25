var loginBtn = document.getElementById("login");
var signUpBtn = document.getElementById("sign_up");
var loginBox = document.getElementById("loginBox");
var signUpBox = document.getElementById("signUpBox");
var closeDropDown_signUp = document.getElementById("closeDropDown_signUp");
var closeDropDown_login = document.getElementById("closeDropDown_login");
var emailInput = document.getElementById("emailInput");
var passwordInput = document.getElementById("passwordInput");
var loginReq_btn = document.getElementById("loginReq_btn");
var loginFailed = document.getElementById("loginFailed");
var usernameInput = document.getElementById("usernameInput");
var emailInput_signUp = document.getElementById("emailInput_signUp");
var signUpReq_btn = document.getElementById("signUpReq_btn");
var signUpFailed = document.getElementById("signUpFailed");
var passwordInput_signUp = document.getElementById("passwordInput_signUp");
var passwordInputRepeat = document.getElementById("passwordInputRepeat");
var signUpSuccess = document.getElementById("signUpSuccess");

////Sign Up below

usernameInput.onkeyup = function(event){
	if(event.keyCode === 13){
		emailInput_signUp.focus();
	}
}

emailInput_signUp.onkeyup = function(event){
	if(event.keyCode === 13){
		passwordInput_signUp.focus();
	}
}

passwordInput_signUp.onkeyup = function(event){
	if(event.keyCode === 13){
		passwordInputRepeat.focus();
	}
}

passwordInputRepeat.onkeyup = function(event){
	if(event.keyCode === 13){
		signUpFunction();
	}
}

function validateInputs(){
	var emailValue = emailInput_signUp.value;
	var passwordValue = passwordInput_signUp.value;
	var passwordInputRepeatValue = passwordInputRepeat.value;
	var usernameValue = usernameInput.value;
	
	if(passwordValue == passwordInputRepeatValue && passwordValue != ""){
		if(emailValue != ""){
			if(usernameValue != ""){
				return true;
			}
		}
	}
	return false;
}

function signUpFunction(){
	if(validateInputs() == true){
		var ajaxReq = new XMLHttpRequest();

		ajaxReq.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				signUpResponse(this.responseText);
				//console.log(this.responseText);
				
				emailInput_signUp.value = '';
				passwordInput_signUp.value = '';
				passwordInputRepeat.value = '';
				usernameInput.value = '';
			}
		}

		ajaxReq.open("POST", "ajax_reqs/signup_processing.php", true);
		ajaxReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxReq.send("email="+emailInput_signUp.value+"&password="+passwordInput_signUp.value+"&username="+usernameInput.value+"&");
	}else{
		signUpFailed.style.opacity = 1;
        signUpFailed.style.top = "20px";

        setTimeout(function(){signUpFailed.style = "";},1000); //login failed thing disappears
	}
}

function signUpResponse(responseText){
	if(responseText == "true"){
		signUpSuccess.style.opacity = 1;
        signUpSuccess.style.top = "20px";

        setTimeout(function(){signUpSuccess.style = "";},1000); //login failed thing disappears
	}else{
		signUpFailed.style.opacity = 1;
        signUpFailed.style.top = "20px";

        setTimeout(function(){signUpFailed.style = "";},1000); //login failed thing disappears
	}
}

signUpReq_btn.addEventListener('click', signUpFunction);

////Login below


emailInput.onkeyup = function(event){
	if(event.keyCode === 13){
		passwordInput.focus();
	}
}

passwordInput.onkeyup = function(event){
	if(event.keyCode === 13){
		login(emailInput.value,passwordInput.value);
	}
}

function boxFadeIn(boxName){
    boxName.style = "z-index:30;";
    boxName.style.marginTop = "0";
}

function boxFadeOut(boxName){
    boxName.style.marginTop = "";
    setTimeout(function(){boxName.style = "";},400);
}

function loginResponse(response){
    if(response == 0){
        loginFailed.style.opacity = 1;
        loginFailed.style.top = "20px";

        setTimeout(function(){loginFailed.style = "";},1000); //login failed thing disappears
    }else{
        window.location.href = "channels.php";
    }
}

function login(emailInput, passwordInput){
    var ajaxReq = new XMLHttpRequest();

    ajaxReq.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            loginResponse(this.responseText);
        }
    }

    ajaxReq.open("POST", "ajax_reqs/login_validation.php", true);
    ajaxReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajaxReq.send("email="+emailInput+"&password="+passwordInput);
}

loginReq_btn.addEventListener("mouseup",function(){login(emailInput.value,passwordInput.value);});

loginBtn.addEventListener("mouseup", function(){boxFadeIn(loginBox);});
signUpBtn.addEventListener("mouseup", function(){boxFadeIn(signUpBox);});

closeDropDown_login.addEventListener("mouseup",function(){boxFadeOut(loginBox);});
closeDropDown_signUp.addEventListener("mouseup",function(){boxFadeOut(signUpBox);});



