<!-- BEGIN main -->
<div class="regular">
	{MESSAGE}
	<div class="transparent">Create a new Keyword</div>
	<div class="content">
		<form action="" method="post" target="_self">
			<label name="name">Keyword: <input type="input" name="name" placeholder="The word you want to search for"
					style="width:20em;" class="globaltab" /></label><br>
			<div style="text-align:center">
				<label name="description">Description: <br><textarea name="description"
						placeholder="The word you want to describe" class="globaltab"
						style="margin: 5px 5px 0px 0px;width: 20em;"></textarea></label>
				<br>
				<label name="replacement">Keyword Replacement: <br><textarea
						placeholder="The word you want to search for replaced with this" name="replacement"
						class="globaltab" style="margin: 5px 5px 0px 0px; width: 20em;"></textarea></label>
			</div>
			<input type="submit" name="new" value="New Keyword" class="globaltab" />
		</form>
	</div>


	<br>
	<div class="transparent">Keyword Database</div>
	<div class="content">
		{LIST}
		<!-- BEGIN option -->
		<div>
			<form action="" method="post" target="_self">
				<div class="quote">
					Word Preview <br> <span data-tooltip="{DESCRIPTION}" data-tooltip-persistent>{REPLACEMENT}</span>
				</div>
				<label name="name">Keyword: <input type="input" name="name" value="{NAME}"
						class="globaltab" /></label><br>
				<div style="text-align:center">
					<label name="description">Description: <br><textarea name="description" value="{DESCRIPTION}"
							class="globaltab"
							style="margin: 5px 5px 0px 0px; width: 20em;">{DESCRIPTION}</textarea></label>
					<br>

					<label name="replacement">Keyword Replacement: <br><textarea name="replacement" value="{VALUE}"
							class="globaltab" style="margin: 5px 5px 0px 0px; width: 20em;">{VALUE}</textarea></label>
				</div>
				<br class="clearfix">
				<div style="text-align:center">
					<input type="hidden" name="keyword" value="{ID}" class="globaltab" />
					<input type="submit" name="edit" value="Save Changes" class="globaltab" />
					<input type="submit" name="delete" value="Delete" class="globaltab" />
				</div>
			</form>
		</div><br>
		<!-- END option -->
	</div>
</div>
<!-- END main -->