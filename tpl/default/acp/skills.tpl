<div class="regular">
	<div class="title">Skill database
		<a style="float:right;" href="./?s=website&module=skill&new=true">Create a Skill</a>
	</div>
	<div class="content">
		<table class="character-division" cellpadding="0" cellspacing="0" style="background-color:#1d1e1e;color:white;">
			{SKILLS}
		</table>
	</div>

	<!-- BEGIN new-skill -->
	<div class="regular">
		<div class="title">Skill database > New Skill <a style="font-size: 12px; font-weight:bolder; float:right"
				href="./?s=website&module=skill" class="globaltab">Cancel</a></div>
		<div class="content">
			<form action method="post" enctype="multipart/form-data" target="_self">
				Skill Picture <input name="image" type="file" class="globaltab" size="50" maxlength="50"
					class="globaltab">
				<input name="name" type="text" class="globaltab" id="name" value="Skill Name" size="40" maxlength="40">
				<br>
				<br>
				<div>
					<textarea rows="2" name="description" cols="20" style="width:100%;"
						class="globaltab">Skill Description</textarea>
				</div>
				Skill Cost <input name="cost" type="text" class="globaltab" id="cost" value="0" size="40"
					maxlength="40">
				Cooldown <input name="cooldown" type="text" class="globaltab" id="cooldown" value="None" size="40"
					maxlength="40">
				<br />
				<div style="text-align:center;"><input type="submit" name="submit" value="Add Skill" class="globaltab">
				</div>
			</form>
		</div>
	</div>
	<!-- END new-skill -->

	<!-- BEGIN edit-skill -->
	<div class="regular">
		<div class="title">Skill Manager > Editing {NAME}</div>
		<div class="content">
			<div style="float:right;">
				<form action method="post" target="_self">
					<input type="submit" name="submit" value="Save changes" class="globaltab">
					<input type="submit" name="delete" value="Delete" class="globaltab">
					<a href="./?s=website&module=skill" class="globaltab">Cancel</a>
			</div>
			<div>
				<div style="font-size:15px; font-weight:bold; text-align:center;">
					{NAME}
					<br />
					{PICTURE}<br />
					<a href="./?s=website&module=skill&id={ID}&change-picture=true" class="globaltab">Change skill
						picture</a>
				</div><br />
				<div style="text-align:center;">
					Skill Targets <input name="targets" type="text" class="globaltab" id="targets"
						value="{TARGETS}"><br />
					This skill requires (Skill id glued by | ) <input name="requires" type="text" class="globaltab"
						id="requires" value="{REQUIRES}"><br />
					Share cooldown with (skill id) <input name="sharing" type="text" class="globaltab" id="sharing"
						value="{SHARED}"><br />
					Start match cooldown<input name="starting" type="text" class="globaltab" id="starting"
						value="{STARTING}"><br />
					<input type="checkbox" name="bypass" value="1" {INVUL}>Bypass Invulnerability
					<input type="checkbox" name="invisible" value="1" {INVISIBLE}>Invisible to the enemy
					<input type="checkbox" name="status" value="1" {STATUS}>Status (Can't be removed)
					<input type="checkbox" name="uncounterable" value="1" {UNCOUNTERABLE}>Uncounterable (Can't be
					countered)
					<input type="checkbox" name="unreflectable" value="1" {UNREFLECTABLE}>Unreflectable (Can't be
					reflected)
					<input type="checkbox" name="dead" value="1" {DEAD}>Can now also target the dead
				</div>
			</div>
			<input type="submit" name="add-effect" value="Add new effect" style="float:right;" class="globaltab">

			{EFFECTS}
			</form>
		</div>
	</div>
	<!-- END edit-skill -->

	<!-- BEGIN change-avatar -->
	<div class="regular">
		<div class="transparent">
			<p class="navfont">Skill Manager > <a href="./?s=website&module=skill&id={ID}">{NAME}</a> Image Modification
			</p>
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
								<input name="image" type="file" class="globaltab" size="50" maxlength="50"
									class="globaltab">
							</div>
							<div align="center">
								<input name="Avi" type="submit" class="globaltab" value="{L_SUBMIT}">
							</div>
						</td>
					</tr>

				</table>
			</form>
		</div>
	</div>
	<!-- END change-avatar -->
</div>