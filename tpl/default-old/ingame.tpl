
<div id="wrapper">
	
	    <div class="scrn t">
		
		<div class="part A">
                    {PLAYER}
		</div><!-- Left user details -->
		
		<div class="part B">
		    <h1 class="turnText">{STATUS}</h1>
		    <div class="timerBar"><div class="timerBar Left"></div></div>
			<h1 class="numberTurn">TURN {TURN}</h1>
		</div><!-- Center : Match status, timer, timer bar -->
		
		<div class="part C">
                    {OPPONENT}
		</div><!-- right user details -->
		
	    </div>
            
            <div class="scrn l">
                {SLOTS}
		{FORMAT}
	    </div>
            <!-- BEGIN Render 1 -->
	    <div class="render a"></div>
            <!-- END Render 1 -->
            <!-- BEGIN Render 2 -->
	    <div class="render b"></div>
            <!-- END Render 2 -->
            <div class="scrn r">
		{OSLOTS}
	    </div>
	    
	    <div class="clearfix scrn b">
		<div class="options">
		    <!-- <div class="item A opacity">TURN LOG</div> -->
		    <div class="item B"><a style="text-decoration:none;color:white;" href="https://discordapp.com/invite/UJCRTd2" target="_blank">OPEN CHAT</a></div>
		    <div class="item C">SURRENDER</div>
		</div>
	    </div>
	    
	    <div class="clearfix"></div>            
    </div>