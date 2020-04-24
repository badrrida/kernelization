function onSignIn(googleUser) {
	var id_token = googleUser.getAuthResponse().id_token;
	var profile = googleUser.getBasicProfile();
	var email = profile.getEmail()
	var name = profile.getName()
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost:8080/src/php/VerifyGoogleToken.php');
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.send('idtoken=' + id_token + '&email=' + email + '&name=' + name);
	//xhr.send('email=' + email);
	//xhr.send('name=' + name);
	
    
	$('.main-nav').off();
	$("#signupbtn").remove();
	$("#signupdiv").append("<div class='dropdown'><a href='#' class='account'><img src='" + profile.getImageUrl() + "' id='profilepic'/> <div class='submenu' style='display: none;'><ul class='root'><li id='rema'><a href='#'>Dashboard</a></li><li><a href='#'>Settings</a></li><li><a href='#' onclick='signOut\(\)'>Sign Out</a></li></ul></div></div>");
	$("#rema a.account").remove();
	dropdowntoggle();
}

function escapesignin() {
	$('.cd-user-modal').removeClass('is-visible');
}


function signOut() {
	var auth2 = gapi.auth2.getAuthInstance();
	auth2.signOut().then(function () {
		console.log('User signed out.');
    });
	location.reload();
}