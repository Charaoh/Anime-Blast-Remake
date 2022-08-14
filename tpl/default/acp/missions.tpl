<!-- BEGIN REQUIREMENTS -->
<div class="transparent">Requirements for missions <div style="float:right;"><a
			href="./?s=website&module=missions&new=true" />New mission</a> | <a href="./?s=website&module=missions" />Go
		back to missions</a></div>
</div>
<div class="transparent">Create new requirement</div>
<div class="content">
	<form action="" method="post">
		<label>
			<p class="navfont">Required Streak?</p>
		</label>
		<input type="input" class="globaltab" name="streak" value="" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Beat a certain character or group</p>
		</label>
		<input type="text" class="globaltab" name="beata" value="" placeholder="Who?" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Win with a certain character? Set this and beat a certain character to make</p>
		</label>
		<input type="text" class="globaltab" name="win" value="" placeholder="Who?" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Description of this to replace the basic text</p>
		</label>
		<textarea class="globaltab" name="description" value="" placeholder="Who?"
			style="margin: 5px 5px 0px 0px; width: 526px; height: 19px;"></textarea>
		<br class="clearfix" />
		<label>
			<p class="navfont">Count</p>
		</label>
		<input type="text" class="globaltab" name="counter" value="" placeholder="Count for this requirement" />
		<div align="center"><input type="submit" name="submit" value="Add Requirement" class="globaltab" /><input
				type="hidden" name="addrequire" value="1" /></div>
	</form>
</div>
<div class="transparent">Requirements</div>
<div class="content">
	{REQUIREMENTS}
</div>
<!-- END REQUIREMENTS -->
<!-- BEGIN MISSIONSPAGE -->
<div class="transparent">Missions<div style="float:right;"><a href="./?s=website&module=missions&new=true" />New
		mission</a> | <a href="./?s=website&module=missions&requirements=true" />Requirements</a></div>
</div>
<div class="content">
	{MISSIONS}
</div>

<!-- END MISSIONSPAGE -->
<!-- BEGIN mission -->
<div class="missions {ROW}">
	<h1 class="header">ID {ID} - {NAME}</h1>
	<form action="" method="post" enctype="multipart/form-data">
		<!-- BEGIN tag -->{PICTURE} <br class="clearfix" />
		<label class="transparent"><a href="./?s=website&module=missions&change=true&id={ID}" /> Change Picture
			</a></label><!-- END tag -->
		<!-- BEGIN picture --><input name="image" type="file" class="globaltab" size="50" maxlength="50"
			class="globaltab">
		<br class="clearfix" /><!-- END picture -->

		<input type="text" class="globaltab" name="name" value="{NAME}"
			placeholder="Mission name here" /><br /><textarea class="globaltab" name="description"
			placeholder="Mission description here" width="640px" height="200px"
			style="margin: 5px 5px 0px 0px; width: 493px; height: 56px;">{DESCRIPTION}</textarea>
		<br class="clearfix" /><br class="clearfix" />
		<label>
			<p class="navfont">What anime is this mission for? (REQUIRED!) </p>
		</label>{WHO}<br class="clearfix" /><br class="clearfix" />
		<label>
			<p class="navfont">Is this mission hidden to public eye? </p>
		</label>
		<input type="checkbox" class="globaltab" name="hiddenMission" value="1" {HIDDEN} /><br class="clearfix" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Required missions to do this one (if various glued with commas)</p>
		</label>
		<input type="text" class="globaltab" name="previous" value="{PREVIOUSLY}"
			placeholder="Missions needed done before here" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Requirements (if various glued with commas)</p>
		</label>
		<input type="text" class="globaltab" name="required" value="{REQUIRE}" placeholder="Requirements to complete" />
		<br class="clearfix" />
		<label>
			<p class="navfont">Level Required</p>
		</label>
		<input type="text" class="globaltab" name="level" value="{LEVEL}" placeholder="Level requirement" />
		<br class="clearfix" />
		<label>
			<p class="navfont">On complete, here if various rewards are given seperated by "|" - Character reward
				C:[Character-id], G:[BC Reward]</p>
		</label>
		<input type="text" class="globaltab" name="reward" value="{OC}" placeholder="On complete..." /><input
			type="hidden" name="id" value="{ID}" class="globaltab" />
		<div align="center"><input type="submit" name="submit" value="{WHAT}" class="globaltab" />
			<!-- BEGIN tag --><input type="submit" name="delete" value="Delete" class="globaltab" /><!-- END tag -->
		</div>
	</form>
</div>
<!-- END mission -->
<!-- BEGIN change -->
<div class="transparent">
	<p class="navfont">Mission database > {NAME} Change picture</p>
</div>
<div class="content">
	<form action method="post" enctype="multipart/form-data" target="_self">
		<table width="100%">
			<tr>
				<td valign="middle" rowspan="2" width="5">
					<div align="center">{AVATAR}</div>
				</td>
				<td>
					<div align="center">
						<input name="image" type="file" class="globaltab" size="50" maxlength="50" class="globaltab">
					</div>
					<div align="center">
						<input name="Avi" type="submit" class="globaltab" value="Update">
					</div>
				</td>
			</tr>

		</table>
	</form>
</div>
<!-- END change -->
<!-- BEGIN require -->
<form action="" method="post">
	<label>
		<p class="navfont">ID {ID}</p>
	</label>
	<label>
		<p class="navfont">Required Streak</p>
	</label>
	<input type="input" class="globaltab" name="streak" value="{STREAK}" />
	<br class="clearfix" />
	<label>
		<p class="navfont">Beat a certain character or group</p>
	</label>
	<input type="text" class="globaltab" name="beata" value="{BEAT}" placeholder="Who?" />
	<br class="clearfix" />
	<label>
		<p class="navfont">Win with a certain character</p>
	</label>
	<input type="text" class="globaltab" name="win" value="{WIN}" placeholder="Who?" />
	<br class="clearfix" />
	<label>
		<p class="navfont">Description of this to replace the basic text</p>
	</label>
	<textarea class="globaltab" name="description" placeholder="Who?"
		style="margin: 5px 5px 0px 0px; width: 526px; height: 19px;">{DESCRIPTION}</textarea>
	<br class="clearfix" />
	<label>
		<p class="navfont">Counter</p>
	</label>
	<input type="text" class="globaltab" name="counter" value="{COUNT}" placeholder="Count for this requirement" />
	<div align="center"><input type="submit" name="submit-required" value="Edit Requirement" class="globaltab" /><input
			type="submit" name="delete-required" value="Delete Requirement" class="globaltab" /><input type="hidden"
			name="id" value="{ID}" /></div>
</form>
<!-- END require -->