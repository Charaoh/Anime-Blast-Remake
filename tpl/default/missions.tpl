<h2 class="header">Missions</h2>
<p class="wordbreak px-1">Complete missions to unlock additional content. You can search missions via the anime's name.
	<span data-tooltip="Click on View this mission and it will slide down the requirements!"
		data-tooltip-persistent=""><i class="fas fa-search-plus"></i></span>
</p>

<div class="searchbg my-1"><label for="myAnime"></label>
	<img class="search-icon" src="../favicon.ico" />
	<input type="text" class="search" id="myAnime" placeholder="Search for a mission group...">
</div>

<div class="regular">
	<div class="content header-mission">
		{MISSIONS}

		<!-- BEGIN mission -->
		<div id="mission{ID}" style="padding:5px;">
			<h3 class="header mb-1">{NAME}</h3>
			<p class="wordbreak" style="float: left;border-bottom: 1px dashed #303030;width:100%;">{IMG}{DESCRIPTION}
			</p>
			<p style="text-align:center;margin-top: .5em;">{REQUIREMENTS}</p>
			<!-- BEGIN view -->
			<p class="view-mission" style="float: right;">View this mission<br> {PROGRESS}</p><br class="clearfix">
			<div class="achieve" style="display:none;">
				<p style="clear: both;border-bottom: 1px dotted;margin-bottom: 5px;"><img
						src="./tpl/default/img/read.png" class="me" alt="" style="width: 25px;">Mission goals</p>
				<p style="padding-left: 10px;">{GOALS}</p>
				<p style="clear: both;border-bottom: 1px dotted;margin-bottom: 5px;"><img
						src="./tpl/default/img/read.png" class="me" alt="" style="width: 25px;">Mission Rewards</p>
				<p style="text-align: center;">{REWARDS}</p>
			</div><!-- END view -->
		</div>
		<!-- END mission -->
</div>