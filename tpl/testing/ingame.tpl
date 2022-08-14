<!--[if IE]>
<style>
    .rankwing{
		margin-left: -50px !important;
	}
</style>
<![endif]-->
<div id="wrapper" {BG}>
	{MOBILE}
	<div id="popup" class="winner">
		<img class="mobile" src="./tpl/beta/css/images/win.png">
		<br>
		You have won a {TYPE} battle against {EUSER}<br>
		<span></span>
		<p><a href="./ingame" class="button blue">CONTINUE</a></p>
	</div>
	<div id="popup" class="loser">
		<img class="mobile" src="./tpl/beta/css/images/lose.png">
		<br>
		You have lost a {TYPE} battle against {EUSER}<br>
		<span></span>
		<p><a href="./ingame" class="button red">CONTINUE</a></p>
	</div>
	<div class="transparency">
		<div class="character-layout">
			<h2>Dark Inferno Blast</h2>
			<p class="fl-l description">Zeref deals 20 piercing and 10 affliction damage to one enemy. Zeref will become invulnerable for one turn after this skill is used.</p>
			<div class="classes"></div>
			<div class="character-portrait">
			</div>
			<p class="details">Cooldown: 2 <span>40 Mana</span></p>
			<div class="skillset"></div>
			<br class="clearfix">
		</div>
	</div>
	<div class="landscape"><img src="./tpl/beta/css/images/mobile.png"></div>
	<div class="scrn l">
        {SLOTS}
		{FORMAT}
	</div>
	<div class="scrn r">
		{OSLOTS}
	</div>
    
    <div class="settings alts" tabindex="-1">
		<div class="container" role="dialog" aria-modal="true">
			<div class="content-title">MENU<span class="close-menu">Close</span></div>
			<div class="setup">
				<div class="skip">
					<img src="./tpl/beta/css/images/skipTrack.png" class="prevTrack">SKIP BGM
					<img src="./tpl/beta/css/images/skipTrack.png" class="nextTrack">
				</div>
                <p class="buffering">Buffering</p>
				<div class="soundControllers">
                	<p>Music Volume</p>
                	<div class="musicControls player">
                    	<span class="minus"><img style="padding-top:4px" src="./tpl/beta/css/images/volumeDOWN.png"></span>
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="0.2" class="rotate ratio">
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="0.4" class="ratio">
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="0.6" class="rotate ratio">
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="0.8" class="ratio">
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="1" class="rotate mute ratio">
                   		<img src="./tpl/beta/css/images/volumeUP.png" class="plus">
                	</div>
                	<br class="clearfix">
                	<p>SFX Volume</p>
                	<div class="musicControls Sounds">
                	<span class="minus"><img style="padding-top:4px" src="./tpl/beta/css/images/volumeDOWN.png"></span>
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="0.2" class="rotate ratio">
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="0.4" class="ratio">
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="0.6" class="rotate ratio mute">
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="0.8" class="ratio mute">
                    	<img src="./tpl/beta/css/images/triangle.png" data-volume="1" class="rotate mute ratio">
                    	<img src="./tpl/beta/css/images/volumeUP.png" class="plus">
                	</div>
				<br class="clearfix">
				<span class="surrender">Surrender</span>
				<br class="clearfix">
				
				</div>
			</div>
		</div>
	</div>
	<span class="menu"><span></span>MENU</span>
    
	<div class="scrn b">
		<div class="part A">
            {PLAYER}
		</div><!-- Left user details -->
		
		<div class="part B">
		    <h1 class="turnText">End Turn</h1>
		    <div class="timerBar"><div class="timerBar Left"></div></div>
			<h1 class="numberTurn">{STATUS}</h1>
		</div><!-- Center : Match status, timer, timer bar -->
		
		<div class="part C">
            {OPPONENT}
		</div><!-- right user details -->
		
	    </div>
	    
	    <div class="clearfix"></div>            
    </div>