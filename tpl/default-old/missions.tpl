<div class="transparent"><input type="text" id="myAnime" placeholder="Search for anime specific"></div>
<div class="content header-mission">
		<h2 class="header">Missions</h2>
		<p class="wordbreak">Missions are tasks that can be done to unlock more characters. <span data-tooltip="Click on View this mission and it will slide down the requirements!" data-tooltip-persistent=""><i class="fas fa-search-plus"></i></span></p>
{MISSIONS}
<!-- BEGIN mission -->
<div id="mission{ID}" style="padding:5px;">
	<h3 class="header" style="margin-bottom: 3px;border-bottom: 1px dotted white;margin-top: 0;">{NAME}</h3>
	<p class="wordbreak" style="float: left;border-bottom: 1px dotted white;width:100%;">{IMG}{DESCRIPTION}</p>
	<p style="text-align:center;margin-bottom: 10px;">{REQUIREMENTS}</p>
	<!-- BEGIN view -->
	<p class="view-mission" style="float: right;">View this mission<br> {PROGRESS}</p><br class="clearfix"> 
	<div class="achieve" style="display:none;">
	<p style="clear: both;border-bottom: 1px dotted;margin-bottom: 5px;"><img src="./tpl/default/img/read.png" class="me" alt="" style="width: 25px;">Mission goals</p>
	<p style="padding-left: 10px;">{GOALS}</p>
	<p style="clear: both;border-bottom: 1px dotted;margin-bottom: 5px;"><img src="./tpl/default/img/read.png" class="me" alt="" style="width: 25px;">Mission Rewards</p>
	<p style="text-align: center;">{REWARDS}</p>
	</div><!-- END view -->
</div>
<!-- END mission -->
</div>