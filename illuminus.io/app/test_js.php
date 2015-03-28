<!DOCTYPE html>
<html>
<head>
<title>Facebook Login JavaScript Example</title>
<meta charset="UTF-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<style>
html,body { margin: 0; padding: 0; background: #000; color: #ddd; font:11px/11px Arial}
#infobar { background-color: rgba(0,0,0,.5); width: 100%; height: 20px; position: absolute; top:0; left: 0; z-index: 999999}
iframe { display:none; position: fixed; top:0px; left:0px; bottom:0px; right:0px; border: none; margin: 0 0 0 0; padding: 0; overflow:hidden; z-index:999998; width: 100%; height: 100%; }
</style>
</head>
<body>
<div id='fb-root'></div>
<div id="infobar">
	<span id="login_btn"></span>
	<span id="status"></span>
</div>
<iframe id="app_frame" src="app.php?start" frameBorder="0"></iframe>





<script>


document.domain = "dnt.dev";

/**
 *	Facebook
 */

// 0. load the Javascript SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));



var login_status = false; 
var accessToken = false;

// 1. run after SDK loads. 
window.fbAsyncInit = function() {
	
	// init FB...
	FB.init({
		appId      : '761116317308745',
		cookie     : true,  // enable cookies to allow the server to access the session
		xfbml      : true,  // parse social plugins on this page
		version    : 'v2.2' // use version 2.1
	});
	
	/*	After JavaScript SDK init, FB.getLoginStatus() gets status of visitor.
		These 3 cases are handled in the getLoginStatus(checkLoginStatus) callback function.
	
		1. connected: Logged into your app
		2. not_authorized: Logged into Facebook, but not your app
		3. [else]: Not logged into Facebook / can't tell if they are logged into app
	*/
	
	// 3. check login
	FB.getLoginStatus(checkLoginStatus);
};
	
// Check the result of the user status and display login button if necessary
function checkLoginStatus(response) {
	if(response && response.status == 'connected') {
		console.log('user is logged in AND has authorized app');
	
	// first try to send access token to php	
	//accessToken = response.authResponse.accessToken;
	
	
		logout_prompt();
		loadIframe();
	} else if (response.status === 'not_authorized') {
		console.log('user is logged in BUT has not authorized app');
		login_prompt();
	} else {
		console.log('user is not logged into Facebook');
		login_prompt();
	}
}
// Login user
function login_user(_scope) {
	FB.login(function(response) {
		// handle the response
		if (response.authResponse) {
			logout_prompt();
			loadIframe();
		} else {
			console.log('User cancelled login or did not fully authorize.');
		}
	}, { scope: _scope });
}
// Logout user
function logout_user() {
	FB.api('/me/permissions', 'DELETE', function(res){
	    if(res.success === true){
	        console.log('app deauthorized');
	        login_prompt();
	    } else if(res.error){
	        console.log('res.error');
	        console.error(res.error.type + ': ' + res.error.message);
	    } else {
	        console.log(res);
	    }
	}); 
}

// show logout button / text
function logout_prompt(){
	// show login button
	$('#login_btn').html('<input id="fb_logout_btn" type="button" value="logout" /><input id="reload_btn" type="button" value="reload" />');
	$('#status').html('Welcome!!');
	$("#app_frame").attr('src','');
}
// show login button / text
function login_prompt(){
	$('#login_btn').html('<input id="fb_login_btn" type="button" value="login" />');
	$('#status').html('Please log into this app.');
	$("#app_frame").attr('src','');
}
     
function loadIframe(url) {
    $("#app_frame")
    	.attr('src','app.php?v' + Math.random())
    	.css('display','block');

}
	


$(document).on('click','#fb_login_btn',function() {
	login_user('email,user_birthday,user_likes');
});
$(document).on('click','#reload_btn',function() {
	loadIframe();
});
$(document).on('click','#fb_logout_btn',function() {
	logout_user();
});	



	
	

	

// Called when someone finishes with the Login Button. See the onlogin handler attached to it in the sample code.
function checkLoginState() {
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
}
</script>


</body>
</html>