<div class="transparent">{NAME}<div style="float:right;"><a href="./?s=game&module=season&end=true"/>End {NAME}</a></div></div>
<div class="content">
<h2 class="header">Seasons > {NAME} started on {START}</h2>
<p>The season rewards are listed below, when you have listed all of them and the time for the season is up hit end season above to start a new one!</p>
{SEASON}

<br class="clearfix"/>
<h4 class="header">New reward!</h4>
<p>Below you can assign rewards by total xp or a specific rank!</p>
<form action="" method="post" enctype="multipart/form-data">
	<label><font class="navfont">Reward for -></font></label>
	<select name="type">
		<option value="exp">Experience</option>
		<option value="rank">Rank</option>
	</select>
	<br class="clearfix"/>
	<label><font class="navfont">Value</font></label>
	<input type="text" class="globaltab" name="value" value="" placeholder="Value needed for this reward" />
	<br class="clearfix"/>
	<label><font class="navfont">Item to reward</font></label>
	<input type="text" class="globaltab" name="item" value="" placeholder="Item to reward" />
	<br class="clearfix"/>
	<div align="center"><input type="submit" name="submit" value="Save Reward" class="globaltab"/></div>
 </form>
</div>