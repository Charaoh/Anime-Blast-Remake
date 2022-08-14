<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{BASE}
<link rel=”canonical” href=”{URL}” />
{LANDSCAPE}
<meta name="resource-type" content="document">
<meta name="distribution" content="Global">
<meta name="copyright" content="anime-blast.com">
<meta name="robots" content="Index,Follow">
<meta name="rating" content="General">
<meta name="revisit-after" content="1 day">
<meta name="description" http-equiv="description" content="{METAINFO}" />
<meta name="keywords" http-equiv="keywords" content="{METAKEYWORDS}" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-152357673-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-152357673-1');
</script>
<title>{PAGETITLE}</title>
<!-- BEGIN content -->
<script type="text/javascript" src="{URL}tpl/beta/java/jquery.js"></script>
<link rel="stylesheet" type="text/css"  href="{URL}tpl/default/css/bxslider.css">
<script type="text/javascript" src="{URL}tpl/beta/java/bxslider.js"></script>
<!-- END content -->
<link rel="icon"
type="image/png"
href="{FAV}">
{SCRIPTS}
</head>
<body>
<!-- BEGIN content -->
<div id="wrapper">
<div id="regModal" class="rModal">

  <!-- Modal content -->
  <div class="regModalC">
    <span class="regClose">&times;</span>
    <h1 style="text-align:center;"> Welcome to Anime-Blast!</h1>
    <p style="text-align:center;">Be sure to join our Discord community! We host tourneys and character creation contests on our discord. Talk directly with other players and staff here!</p>
    <a href="https://discord.gg/UJCRTd2" target="_blank"><h2 class="button" style="
    background: url(https://discord.com/assets/e4923594e694a21542a489471ecffa50.svg) 0px -11px no-repeat;
    background-size: 150px;
    background-color: white;
    height: 25px;
">
  </div>

</div>
<div class="leftwing">
<a class="logo" href="{URL}"><img src="{URL}tpl/beta/css/images/logo.png" class="banner"></a>
<div class="first"> 
<!-- BEGIN UCP -->
<!-- BEGIN logged_in -->
<div class="ucp">
{AVATAR}{CLAN}
<p class="level">{LEVEL}</p>
<div class="expBar">
<div class="expFill" style="width:{WIDTH}%;"></div>
<div class="expNumber">{EXPERIENCE}</div>
</div>
<div class="welcome">
<span class="dropdown">
Welcome 
<span class="name" style="color: #FFEB3B;">{USERNAME}</span> 
<img src="{URL}/tpl/default/img/drop.png" style="
	width: 10px;
	margin-left: 5px;
">
<div class="dropdown-content">
{MENU}
</div>
</span>
<a href="{URL}mail"><img src="{URL}/tpl/default/img/notification.png" style="width: 25px;
	margin-left: 15px;" class="icon"></a>
{NOTIFICATION}
<a href="{URL}mail"><img src="{URL}/tpl/default/img/mail.png" style="width: 22px;" class="icon"></a>
<a href="{URL}logout"><img src="{URL}/tpl/default/img/logout.png" style="width: 20px;" class="icon"/></a>
</div>
<br style="clear:both;" />
<p style="font-size:10px;text-align: center;">{TIME}</p>
<div class="divider"></div>
{EXCLUSIVE}
</div>
<h2 class="button">
<img src="{URL}/tpl/default/img/play.png" style="width: 15px;"/><a href="{URL}#" onclick="popup_page('{URL}ingame','','1037','581')" class="tab">Start Playing</a> 
</h2>
<h2 class="button">
<img src="{URL}/tpl/default/img/information.png" style="width: 17px;"/>
<a href="{URL}clans">aBlast Clans</a>
</h2>
<a href="https://discord.gg/UJCRTd2" target="_blank"><h2 class="button" style="
    background: url(https://discord.com/assets/e4923594e694a21542a489471ecffa50.svg) 0px -11px no-repeat;
    background-size: 150px;
    background-color: white;
    height: 25px;
">
</h2></a> 
<div class="container">
<h2 class="button">
<img src="{URL}/tpl/default/img/information.png" style="width: 17px;"/>
<a href="{URL}topic/3" target="_blank">Game Manual</a>
</h2>
<h2 class="button">
<img src="{URL}/tpl/default/img/characters.png" style="width: 17px;"/>
<a href="{URL}characters-and-skills" target="_blank">Characters</a>
</h2>
<h2 class="button">
<img src="{URL}/tpl/default/img/information.png" style="width: 17px;"/>
<a href="{URL}missions" target="_blank">Missions</a>
</h2>



</div>
<br class="clearfix">
<p class="bottom">{LINKS}<br/>{VERSION}</p>

<!-- END logged_in -->
<!-- BEGIN logged_out -->
<h1 class="header" style="
    font-size: 18px;
    color: white;
">Welcome Guest!</h1>
<a href="https://discord.gg/UJCRTd2" target="_blank"><h2 class="button" style="
    background: url(https://discord.com/assets/e4923594e694a21542a489471ecffa50.svg) 0px -11px no-repeat;
    background-size: 150px;
    background-color: white;
    height: 25px;
">
</h2></a> 
<h2 class="button">
<img src="{URL}/tpl/default/img/information.png" style="width: 17px;"/>
<a href="https://www.youtube.com/watch?v=1M_bM-hzTjU" target="_blank">Game Manual</a>
</h2>
<h2 class="button">
<img src="{URL}/tpl/default/img/characters.png" style="width: 17px;"/>
<a href="{URL}characters-and-skills" target="_blank">Characters</a>
</h2>
<hr>
<section class="container-options">
<h2 class="logMe button" style="padding: 10px 0px">
<img src="{URL}/tpl/default/img/login.png" style="width: 15px;">Login to A. Blast ! 
</h2>
<img src="{URL}/tpl/default/img/account.png" class="container-options--login-avatar avatar">
<div class="login">
<p class="error false">Username/Password match incorrect!</p>
<p class="error empty">Please fill out the form!</p>
<p class="error username">Please enter a valid username!</p>
<p class="message Username">Username</p>
<img src="{URL}/tpl/default/img/username.png" class="username"/>
<input id="uname"  type="input" data-placeholder="Username" placeholder="Username" class="input" value=""><br/>
<p class="error password">Please enter a password!</p>
<p class="message Password">Password</p>
<img src="{URL}/tpl/default/img/password.png" class="password">
<input id="pass" type="password" data-placeholder="Password" placeholder="Password" class="input" value="">
<input id="submit-login" type="submit" value="Login" class="submit">
<br>
<input type="checkbox" style="
	margin-bottom: 0px;
	margin-top: 0p;
	top: 2px;
	position: relative;" value="1" name="remember" id="remember"><label for="remember" style="
	font-size: 10px;
	margin-right: 20px;
">Remember me?</label><a href="{URL}password-recovery" target="_blank"><span style="
	font-size: 10px;
	">Password Recovery</span></a>

<!-- BEGIN registration -->
<div style="
	border-top: 1px dotted black;
	width: 80%;
	margin: 0 auto;
	margin-top: 17px;
	font-size: 14px;
	color: white;
	padding-top: 10px;
	font-weight: normal;
	text-align: center;
	text-shadow: 0px 1px 5px #000000;background-size: cover;
	">Dont have an account? <span class="reg"> Click here to register!</span></div><!-- END registration -->
</div>
<!-- BEGIN registration -->
<div class="registration">
<p class="error false">User information taken or information invalid!</p>
<p class="error empty">Please fill out the form!</p>
<p class="error email">Please enter an email!</p>
<p class="message Email">Email</p>
<input id="regemail"  type="input" data-placeholder="Email" placeholder="Email" class="input" value=""><br/>
<p class="error username">Please enter a username!</p>
<p class="message Username">Username</p>
<input id="reguname"  type="input" data-placeholder="Username" placeholder="Username" class="input" value=""><br/>
<p class="error password">Please enter a password!</p>
<p class="message Password">Password</p>
<input id="regpass" type="password" data-placeholder="Password" placeholder="Password" class="input" value=""><br/>
<p class="error conf">Passwords enter the password you entered above!</p>
<p class="error match">Passwords don't match!</p>
		<p class="message Confirm">Confirm Password</p>
		<input id="confpass" type="password" data-placeholder="Confirm" placeholder="Confirm" class="input" value="">
		<input id="submit-register" type="submit" value="Register!" class="submit">
        <br>
       
	<div style="
	border-top: 1px dotted black;
	width: 80%;
	margin: 0 auto;
	margin-top: 17px;
	font-size: 14px;
	color: white;
	padding-top: 10px;
	font-weight: normal;
	text-align: center;
	text-shadow: 0px 1px 5px #000000;
	">Have an account? <br/><span class="log"> Click here to login!</span></div>
</div>
</section>
<!-- END registration -->
<p class="bottom">{VERSION}</p>
	
<!-- END logged_out -->
	
	<br class="clearfix"><br class="clearfix">
	<!-- END UCP -->
</div>
<iframe src="https://discordapp.com/widget?id=634785612246810624&amp;theme=dark" width="243px" height="500" allowtransparency="true" frameborder="0" style="
    margin-top: 10px;
"></iframe>
</div>
<div class="second">
<!-- BEGIN headeroff -->
<!--
	<div class="front slider">
		{NEWS}
	</div> -->
	<!-- BEGIN linktree -->
	<div class="linktree">
		<p>
			<img src="{URL}/tpl/default/img/planet.png" style="width: 15px;padding-right: 5px;float: left;"> 
			{AREA}
		</p>
	</div>
	<!-- END linktree -->
<div class="global-menu">{GLOBAL_MENU}</div>
<!-- END headeroff -->
<!-- END content -->
