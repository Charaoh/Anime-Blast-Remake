<div class="regular">
	<div class="title">Character Database <a style="font-size: 12px; font-weight:bolder; float:right;"
			href="./?s=website&module=character&new=true">Create a Character</a></div>
	<div class="content">
		<table class="character-division" cellpadding="0" cellspacing="0" style="background-color:#1d1e1e;color:white;">
			{CHARACTERS}
		</table>
	</div>

	<!-- BEGIN new-character -->
	<div class="regular">
		<div class="title">Character Manager > New Character <a style="font-size: 12px; font-weight:bolder; float:right"
				href="./?s=website&module=character">Cancel</a></div>

		<div class="content">
			<div style="text-align:left;">
				<form action method="post" enctype="multipart/form-data" target="_self">
					Character Face Picture <input name="image" type="file" class="formcss" size="50" maxlength="50"
						class="formcss">
					<br /><br />
					<input name="name" type="text" class="formcss" id="name" value="Character Name" size="40"
						maxlength="40">
					<br /><br />
					<textarea rows="2" name="description" cols="20" style="width: 5em;"
						class="formcss">Character Description</textarea>
					<br /><br />
					Starting Health <input name="health" type="text" class="formcss" id="health" value="100" size="40"
						maxlength="40">
					<br /><br /> Starting Mana <input name="mana" type="text" class="formcss" id="mana" value="100"
						size="40" maxlength="40">
					<br /><br />
					<input type="submit" name="submit" value="Add Character" class="globaltab">

				</form>
			</div>
		</div>
	</div>
	<!-- END new-character -->

	<!-- BEGIN edit-character -->
	<div class="regular">
		<div class="title">Character Manager > {NAME}</div>
		<div class="content">
			<div style="text-align:left;">
				<form action method="post" target="_self">
					<input type="submit" name="delete" value="Delete Character" class="formcss" style="float:right;">
				</form>

				<form action method="post" target="_self">
					<input name="name" type="text" class="formcss" id="name" value="{NAME}" size="40" maxlength="40">
					<br /><br />
					<div class="fl-l">
						{AVATAR}<br />
						<a href="./?s=website&module=character&id={ID}&change-picture=true" class="formcss">Change
							character
							avatar</a><br />
					</div>

					Which anime is this character from? (12=webmaster, admin | 13=exclusive (can't be seen) |
					14=inclusive
					(can be seen))
					<input type="input" name="who" value="{WHO}" class="formcss"><br /><br />
					Passive moves that are automatically added at the beginning of the match.
					<input type="input" name="passive-skills" value="{PASSIVES}" class="formcss"><br /><br />
					<textarea rows="2" name="description" cols="20" style="width: 97%; height: 5em;"
						class="formcss">{DESCRIPTION}</textarea>
					<br /><br />

					<input type="submit" name="change-description" value="Change name/description/who" class="formcss">
					<br /><br />
					{NAME}'s Health: <input name="health" type="text" class="formcss" id="health" value="{HEALTH}">
					<br /><br />
					{NAME}'s Mana: <input name="mana" type="text" class="formcss" id="mana" value="{MANA}">
					<br /><br />
					<input type="submit" name="change-stats" value="Change Health/Mana" class="formcss">
				</form>
			</div>
		</div>
	</div>
	<br>

	<div class="regular">
		<div class="title">Skill Database</div>
		<div class="content">

			<form action="" method="post" target="_self">
				<table>
					<tr class="title">
						<td width="50%">
							<div style="text-align:center;" class="title">Skill Database</div>
						</td>
						<td width="50%">
							<div style="text-align:center;" class="title">Character Skills</div>
						</td>
					</tr>
					<tr class="row0">
						<td valign="top">
							<select class="formcss" style="width: 100%" name="all-skills" multiple="multiple" size="5">
								{DATABASE}
							</select>
							<div style="text-align:center;"><input type="submit" name="add" value="{L_ADD}"
									class="formcss" /></div>
						</td>
						<td valign="top">
							<form action="" method="post" target="_self">
								<select class="formcss" style="width: 100%;" name="current-skills" multiple="multiple"
									size="5">
									{CURRENT_SKILLS}
								</select>
								<div style="text-align:center;"><input type="submit" name="remove" value="{L_REMOVE}"
										class="formcss" />
								</div>
						</td>

					</tr>
				</table>
			</form>
		</div>
	</div>
	<br style="clear:both;" /><br />

	<div class="regular">
		<div class="title">{NAME}'s Active Skills</div>
		<div class="content">
			{SKILLS}
		</div>
	</div>
</div>
</div>

<!-- END edit-character -->

<!-- BEGIN skill -->
<form action method="post" target="_self">
	<input type="hidden" id="skill-id" name="skill-id" value="{SID}">
	<div style="float:left; width:50%;">
		<div class="boxone"><input name="sname-{SID}" type="text" class="formcss" id="sname-{SID}" value="{SNAME}">

			<!--- BEGIN remove -->
			<input type="submit" name="skill-delete" value="Remove" class="formcss">
			<!--- END remove -->

			<a href="./?s=gamwebsite&module=skill&id={SID}" class="formcss">Edit</a>
		</div><br />
		<div>
			<div style="float:left;height:auto;width:80px;">
				{PICTURE}
				<a href="./?s=website&module=skill&id={SID}&change-picture=true" class="formcss">Change</a>
			</div><textarea name="description-{SID}" class="formcss">{SD}</textarea>
			<br /><br /><input type="submit" name="change" value="Change name/description" class="formcss">
		</div>

		<div style="clear: both; padding-top:10px;">
			<div>
				Classes:
				<br>{CLASSES}
				<input type="submit" name="update-classes" value="Change classes" class="globaltab">
			</div>
			<br />
			<div>
				Mana Cost: <input name="mc" type="text" class="formcss" id="mc" value="{SC}">
			</div>
			<br />
			<div>
				Cooldown: <input name="cd" type="text" class="formcss" id="cd" value="{CD}">
			</div>
			<br /><input type="submit" name="change-stats" value="Change Mana Cost/Cooldown" class="formcss">
		</div>

	</div>
</form>
<br />
<!-- END skill -->

<!-- BEGIN change-avatar -->
<div class="regular">
	<div class="transparent">
		<p class="navfont">Character Database > <a href="./?s=website&module=character&id={ID}">{NAME}</a> -
			Face
			Modification</p>
	</div>

	<div class="content">
		<div style="text-align:center;">
			<form action method="post" enctype="multipart/form-data" target="_self">
				{AVATAR}
				<div style="text-align:center;">
					<input name="image" type="file" class="formcss" size="50" maxlength="50" class="formcss">
				</div>
				<div style="text-align:center;">
					<input name="Avi" type="submit" class="formcss" value="{L_SUBMIT}">
					<input name="Delete" type="submit" class="formcss" value="{L_DELETE}">
				</div>
			</form>
			<br />
			<form action method="post" enctype="multipart/form-data" target="_self">
				{SLANTED}
				<div style="text-align:center;">
					<input name="image-slanted" type="file" class="formcss" size="50" maxlength="50" class="formcss">
				</div>
				<div style="text-align:center;">
					<input name="Avi-Slanted" type="submit" class="formcss" value="{L_SUBMIT}">
					<input name="Delete-Slanted" type="submit" class="formcss" value="{L_DELETE}">
				</div>
			</form>
		</div>
	</div>
	<!-- END change-avatar -->
</div>