<div class="regular">
	<div class="title">Skill Pic Database</div>
	<div class="content">
		<table class="character-division" cellpadding="0" cellspacing="0" style="background-color:#1d1e1e;color:white;">
			{SKILLS}
		</table>
	</div>

	<!-- BEGIN new-skill -->

	<!-- END new-skill -->

	<!-- BEGIN edit-skill -->
	<div class="regular">
		<div class="title">Skill Manager > Editing {NAME}</div>
		<div class="content">
			<div style="float:right;">
				<form action method="post" target="_self">
					<input type="submit" name="submit" value="Save changes" class="globaltab">

			</div>
			<div>
				<div style="font-size:15px; font-weight:bold; text-align:center;">
					{NAME}
					<br />
					{PICTURE}<br />
					<a href="./?s=game&module=skill&id={ID}&change-picture=true" class="acptab">Change skill
						picture</a>
				</div><br />

			</div>
			</form>
		</div>
	</div>
	<!-- END edit-skill -->

	<!-- BEGIN change-avatar -->
	<div class="regular">
		<div class="transparent">
			<p class="navfont">Skill Manager > <a href="./?s=game&module=skill&id={ID}">{NAME}</a> Image Modification
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